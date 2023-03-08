/* eslint-disable */

const gulp = require('gulp'),
    deepAssign = require('assign-deep'),
    config = require('./config.js'),
    errors = { sass: [], javascript: [] },
    processed = { sass: [], javascript: [] };

let embed = {},
    browsersync = false;

// load image/sass relation for embed() function
embedHandler('load');

/**
 * Processes a template string with variables and simple if conditions
 *
 * @param {string} content
 * @param {object} variables
 * @returns {string}
 */
function processTemplate(content, variables) {
    let contentOld = '';

    while (content !== contentOld) {
        contentOld = content;

        // repalce all occurrences of {{if|ifeven|ifodd|ifnot|unless variableName: defaultValue}}...{{else}}...{{/if|ifeven|ifodd|ifnot|unless}}
        content = content.replace(/{{\s*(if|ifeven|ifodd|ifnot|unless)(\d*)\s+([^/:}\s]+)(?:\s*:\s*([^}]+?))?\s*}}([^_]*?)(?:{{\s*else\2\s*}}([^]*?))?{{\s*\/\s*\1\2\s*}}/gi, (match, type, typeId, name, defaultValue, contentThen, contentElse) => {
            let value;

            if (typeof variables[name] === 'undefined' || variables[name] === '') {
                switch (defaultValue.toLowerCase()) {
                    case 'true':
                        value = true;
                        break;

                    case 'false':
                        value = false;
                        break;

                    default:
                        value = defaultValue;
                }
            } else {
                value = variables[name];
            }

            type = type.toLowerCase();

            if (type === 'if') {
                return value ? contentThen : contentElse;
            } else if (type === 'ifeven') {
                return value % 2 ? contentElse : contentThen;
            } else if (type === 'ifodd') {
                return value % 2 ? contentThen : contentElse;
            } else if (type === 'ifnot' || type === 'unless') {
                return value ? contentElse : contentThen;
            }
        });
    }

    // replace all occurrences of {{variableName: defaultValue}}
    content = content.replace(/{{\s*([^/:}\s]+)(?:\s*:\s*([^}]+))?\s*}}/g, (match, name, defaultValue) => {
        if (typeof variables[name] === 'undefined' || variables[name] === '') {
            return defaultValue;
        }

        return variables[name];
    });

    return content;
}

/**
 *
 * @param {string} file
 * @param {object} variables
 * @param {boolean} asData
 * @returns {string}
 */
function getImage(file, variables = {}, asData = false) {
    const fs = require('fs'),
        path = require('path'),
        cwd = process.cwd(),
        mimes = {
            gif: 'image/gif',
            png: 'image/png',
            jpg: 'image/jpeg',
            jpeg: 'image/jpeg',
            svg: 'image/svg+xml',
        };

    // resolve the file path
    if (path.isAbsolute(file) || file.startsWith('/')) {
        file = path.join(cwd, 'public', file);
    } else {
        file = path.resolve('public/', path.relative('dev/', path.resolve(path.dirname(this.options.file), file)));
    }

    const ext = path.extname(file).substr(1).toLowerCase();

    if (typeof mimes[ext] === 'undefined') {
        throw 'Unsupported file extension "' + ext + '"';
    }

    let content = fs.readFileSync(file);

    if (ext === 'svg') {
        // convert buffer to string and replace variables
        content = processTemplate(content.toString(), variables);

        // convert string back to buffer
        content = Buffer.from(content);
    }

    // stat file for embed
    embedHandler('add', 'embed', path.relative(path.join(cwd, 'public'), file), path.relative(path.join(cwd, 'dev'), this.options.file));

    if (asData) {
        return 'data:' + mimes[ext] + ';base64,' + content.toString('base64');
    }

    return content.toString();
}

// additional sass functions
config.sass.options.functions = config.sass.options.functions || {};

config.sass.options.functions['image-embed($file, $variables: null)'] = function (file, variables) {
    const types = require('node-sass').types,
        vars = {};

    /**
     * Convert sass variable to string
     *
     * @param {*} value
     * @returns {string}
     */
    function sassToString(value) {
        if (value instanceof types.Null) {
            return '';
        }

        if (value instanceof types.Boolean) {
            return value.getValue();
        }

        if (value instanceof types.Number) {
            if (value.getUnit() !== '') {
                return value.getValue() + value.getUnit();
            }

            return value.getValue();
        }

        if (value instanceof types.String) {
            return value.getValue();
        }

        if (value instanceof types.List) {
            const list = [];

            for (let i = 0; i < value.getLength(); i++) {
                list.push(sassToString(value.getValue(i)));
            }

            // separator is comma
            if (value.getSeparator()) {
                return list.join(', ');
            }

            // separator is space
            return list.join(' ');
        }

        if (value instanceof types.Color) {
            if (value.getA() === 0.0) {
                return 'transparent';
            }

            if (value.getA() !== 1.0) {
                return `rgba(${value.getR()},${value.getG()},${value.getB()},${value.getA()})`;
            }

            return '#' + ('000000' + ((value.getR() << 16) + (value.getG() << 8) + value.getB()).toString(16)).slice(-6);
        }
    }

    file = file.getValue();

    if (variables instanceof types.Map) {
        // convert sass map to key-value pairs
        for (let i = 0; i < variables.getLength(); i++) {
            const key = sassToString(variables.getKey(i));

            vars[key] = sassToString(variables.getValue(i));
        }
    }

    const content = getImage.call(this, file, vars, true);

    return types.String('url(' + content + ')');
};

// propagate javascript configuration
config.javascript.files.forEach(function (settings, index) {
    const globalSettings = deepAssign({}, config.javascript);
    delete globalSettings.files;

    config.javascript.files[index] = deepAssign({}, globalSettings, settings);
});

// propagate sass configuration
config.sass.files.forEach(function (settings, index) {
    const globalSettings = deepAssign({}, config.sass);
    delete globalSettings.files;

    config.sass.files[index] = deepAssign({}, globalSettings, settings);
});

/**
 * On "all" event for watchers.
 *
 * @param {string} event
 * @param {string} path
 */
function watchEvent(event, path) {
    const log = require('fancy-log'),
        colors = require('ansi-colors');

    log('Watcher', colors.blue(path), '(' + colors.magenta(event) + ')');
}

/**
 * Format and display error messages.
 *
 * @param {Error} error
 * @param {String} type Either 'sass' or 'javascript'
 * @param {Number} index File index
 */
function errorHandler(error, type, index) {
    const path = require('path'),
        log = require('fancy-log'),
        colors = require('ansi-colors'),
        message = (error.formatted || error.message).trim().replace(process.cwd() + path.sep, '');

    // count errors
    errors[type][index].error++;

    // empty line
    console.log();

    message.split('\n').forEach((line) => {
        log(colors.red('ERROR:') + ' ' + line);
    });

    // empty line
    console.log();

    // end stream to continue processing
    if (this.emit) {
        this.emit('end');
    }
}

/**
 * Displays a success or error popup.
 *
 * @param {String} type Either 'sass' or 'javascript'
 */
function popup(type) {
    let message = '',
        errorCount = 0,
        title = config.name;

    const notify = require('gulp-notify');

    if (type === 'sass') {
        title += ' (SASS)';
    } else if (type === 'javascript') {
        title += ' (JS)';
    }

    config[type].files.forEach((settings, index) => {
        // skip if file has not been concatenated
        if (typeof processed[type][index] !== 'undefined' && !processed[type][index]) {
            return;
        }

        const info = errors[type][index],
            timer = process.hrtime(info.timer);

        if (message !== '') {
            message += '\n';
        }

        message += settings.name + ' (' + Number((timer[0] + timer[1] * 1e-9).toFixed(2)) + 's';

        if (info.error) {
            message += ', ' + info.error + ' errors';
        }

        message += ')';

        errorCount += info.error;
    });

    if (message) {
        notify.onError({
            title,
            message,
        })(errorCount ? new Error() : { path: '' });
    }
}

/**
 * glob-modify
 *
 * Prepends a directory and changes extensions of a glob array
 *
 * @param {string|string[]} glob
 * @param {string} [prefix]
 * @param {string} [ext]
 * @param {boolean} [negate]
 * @returns {string[]}
 */
function globify(glob, prefix, ext, negate) {
    if (typeof glob !== 'object') {
        glob = [glob];
    }

    return glob.map((file) => {
        if (negate) {
            if (file.substr(0, 1) === '!') {
                file = file.substr(1);
            } else {
                file = '!' + file;
            }
        }

        if (prefix) {
            file = file.replace(/^(!)?/, '$1' + prefix);
        }

        if (ext) {
            file = file.replace(/\.[^.]+$/, ext);
        }

        return file;
    });
}

/**
 * Handles relation between images and sass files using embed()
 *
 * @param {string} state
 * @param {string} [type] 'image' or 'embed'
 * @param {File|string} [file]
 * @param {string} [embededFrom]
 * @returns {boolean}
 */
function embedHandler(state, type, file, embededFrom) {
    const fs = require('fs'),
        path = require('path'),
        embedFile = config.cache + 'embed.json';

    try {
        if (state === 'load') {
            if (fs.existsSync(embedFile)) {
                embed = require('./' + embedFile);

                return true;
            }

            return false;
        } else if (state === 'save') {
            if (!fs.existsSync(config.cache)) {
                fs.mkdirSync(config.cache);
            }

            fs.writeFileSync(embedFile, JSON.stringify(embed));

            return true;
        } else if (state === 'reset') {
            for (const asset in embed) {
                if (!Object.prototype.hasOwnProperty.call(embed, asset)) {
                    continue;
                }

                if (type === 'embed') {
                    for (const embedFile in embed[asset].files) {
                        if (!Object.prototype.hasOwnProperty.call(embed[asset].files, embedFile)) {
                            continue;
                        }

                        // reset single embed file
                        if (embededFrom) {
                            if (embedFile === embededFrom) {
                                embed[asset].files[embedFile].stat = 0;
                            }

                            continue;
                        }

                        embed[asset].files[embedFile].stat = 0;
                    }

                    continue;
                }

                embed[asset].stat = 0;
            }
        } else if (state === 'add') {
            // add single embed file
            if (type === 'embed' && !file && embededFrom) {
                for (const asset in embed) {
                    if (!Object.prototype.hasOwnProperty.call(embed, asset)) {
                        continue;
                    }

                    for (const embedFile in embed[asset].files) {
                        if (!Object.prototype.hasOwnProperty.call(embed[asset].files, embedFile)) {
                            continue;
                        }

                        if (embedFile === embededFrom) {
                            embed[asset].files[embedFile].stat++;
                        }
                    }
                }

                return true;
            }

            if (!file) {
                return false;
            }

            if (file.relative) {
                file = file.relative;
            }

            // skip if not a file
            if (!fs.lstatSync('dev/' + file).isFile()) {
                return false;
            }

            if (typeof embed[file] === 'undefined') {
                embed[file] = {
                    stat: 0,
                    files: {},
                };
            }

            embed[file].stat++;

            if (!embededFrom) {
                return true;
            }

            if (typeof embed[file].files[embededFrom] === 'undefined') {
                embed[file].files[embededFrom] = {
                    stat: 0,
                };
            }

            embed[file].files[embededFrom].stat++;
        } else if (state === 'clean') {
            for (const asset in embed) {
                if (!Object.prototype.hasOwnProperty.call(embed, asset)) {
                    continue;
                }

                if (type === 'embed') {
                    for (const embedFile in embed[asset].files) {
                        if (!Object.prototype.hasOwnProperty.call(embed[asset].files, embedFile)) {
                            continue;
                        }

                        if (!embed[asset].files[embedFile].stat) {
                            delete embed[asset].files[embedFile];
                        }
                    }

                    continue;
                }

                // image/svg was modified or deleted
                if (embed[asset].stat !== 1) {
                    for (const embedFile in embed[asset].files) {
                        if (!Object.prototype.hasOwnProperty.call(embed[asset].files, embedFile)) {
                            continue;
                        }

                        fs.utimesSync(path.join('dev', embedFile), Date.now() / 1000, Date.now() / 1000);
                    }
                }

                if (!embed[asset].stat) {
                    delete embed[asset];
                }
            }
        }
    } catch (e) {
        return false;
    }
}

/**
 *
 * @param {File} file
 * @param {string} contents
 * @param {string} type
 * @returns {string}
 */
function vueHandler(file, contents, type) {
    const path = require('path'),
        vue = require('vue-template-compiler');

    contents = contents.replace(/<embed\s+file\s*=\s*("[^"]*"|'[^']*')(?:\s+variables\s*=\s*("[^"]*"|'[^']*'))?(?:\s+data\s*=\s*("[^"]*"|'[^']*'))?\s*\/>/g, function (_, image, variables, data) {
        // noinspection JSUnusedLocalSymbols
        const context = { options: { file: file.relative } };

        return eval('getImage.call(context, ' + image + ', ' + (variables ? variables.slice(1, -1) : '{}') + ', ' + (data ? data : 'false') + ')');
    });

    const component = vue.parseComponent(contents, {
        pad: 'line',
        deindent: type === 'sass:process' || type.endsWith(':lint'),
    });

    // save component for later processing
    file.component = component;

    if (type === 'javascript:process' || type === 'javascript:lint') {
        const stripUnreleated = new RegExp(
            [
                // multi-line comment
                /\/\*[^*]*\*+(?:[^*\/][^*]*\*+)*\//.source,
                // single-line comment
                /\/(\/)[^\n]*$/.source,
                // strings
                /"(?:[^"\\]*|\\[\S\s])*"|'(?:[^'\\]*|\\[\S\s])*'|`(?:[^`\\]*|\\[\S\s])*`/.source,
                // regexp
                /\/(?=[^*\/])[^[/\\]*(?:(?:\[(?:\\.|[^\]\\]*)*\]|\\.)[^[/\\]*)*?\/[gim]*/.source,
            ].join('|'),
            'gm'
        );

        let script = '',
            scriptLogic = '';

        if (component.script) {
            script = component.script.content;

            scriptLogic = script.replace(stripUnreleated, '');
        }

        if (type === 'javascript:process') {
            const vueName = path.basename(file.relative, '.vue');

            // resolve shortcuts
            script = script.replace(/new\s+Vue\s*\(\s*\)/g, 'new Vue({})');
            script = script.replace(/Vue\s*\.\s*component\s*\(\s*('[^']+'|"[^"]+")\s*\)/g, 'Vue.component($1, {})');

            if (!scriptLogic.match(/new\s+Vue\s*\(/) && !scriptLogic.match(/Vue\s*\.\s*component\s*\(/)) {
                script += "/* eslint-disable */;Vue.component('" + vueName + "', {});/* eslint-enable */";

                scriptLogic = script.replace(stripUnreleated, '');
            }

            if (component.template) {
                const template = component.template.content;

                const compiledResult = vue.compile(template, { whitespace: 'condense' });

                let result = 'render:function(){' + compiledResult.render + '},';

                if (compiledResult.staticRenderFns) {
                    result += 'staticRenderFns:[';

                    compiledResult.staticRenderFns.forEach((staticRenderFn) => {
                        result += 'function(){' + staticRenderFn + '},';
                    });

                    result += '],';
                }

                script = script.replace(/(Vue(?:\s*\.\s*component)?\s*\([^{]*{)/g, '$1/* eslint-disable */' + result + '/* eslint-enable */');
            }

            if (scriptLogic.match(/new\s+VueRouter\s*\(/)) {
                script = script.replace(/(component\s*:\s*)('[^']+'|"[^"]+")/g, '$1/* eslint-disable */Vue.options.components[$2]/* eslint-enable */');
            }

            if (scriptLogic.match(/new\s+Vue\s*\(/)) {
                const variable = scriptLogic.match(/(\S+)\s*=\s*new\s+Vue\s*\(/);

                let variableName;

                if (variable) {
                    variableName = variable[1];
                } else {
                    script = script.replace(/(new\s+Vue\s*\(\s*{)/, '/* eslint-disable */const __instance__=/* eslint-enable */$1');

                    variableName = '__instance__';
                }

                if (!scriptLogic.match(/__el__/)) {
                    script = script.replace(/(new\s+Vue\s*\(\s*{)/, '$1/* eslint-disable */el:__el__,/* eslint-enable */');
                }

                if (!scriptLogic.match(/__data__/)) {
                    const depth = 20,
                        pattern = '{' + '(?:[^{}]*|{'.repeat(depth) + '(?:[^{}]*)' + '})*'.repeat(depth);

                    script = script.replace(
                        new RegExp('((?:data\\s*:\\s*|data\\s*\\(\\s*\\)\\s*{\\s*return\\s*|data\\s*:\\s*\\(\\s*\\)\\s*=>\\s*{\\s*return\\s*|data\\s*:\\s*function[^(]*\\(\\s*\\)\\s*{\\s*return\\s*)' + pattern + ')'),
                        '$1    /* eslint-disable */...__data__/* eslint-enable */'
                    );

                    scriptLogic = script.replace(stripUnreleated, '');

                    if (!scriptLogic.match(/__data__/)) {
                        script = script.replace(/(new\s+Vue\s*\(\s*{)/, '$1/* eslint-disable */data:function(){return __data__;},/* eslint-enable */');
                    }
                }

                script = "/* eslint-disable */S.vue.cache['" + vueName + "'] = async function(__el__, __data__) {/* eslint-enable */" + script.replace(/^\s*\/\/\s*\n/, '\n') + '/* eslint-disable */;return ' + variableName + ';};/* eslint-enable */\n';
            } else {
                script = '/* eslint-disable */(function(){if(typeof Vue!=="function"){return;}/* eslint-enable */' + script.replace(/^\s*\/\/\s*\n/, '\n') + '/* eslint-disable */})();/* eslint-enable */\n';
            }
        }

        return script;
    }

    if (type === 'sass:process' || type === 'sass:lint') {
        let style = '';

        if (component.styles.length > 1) {
            throw 'Detected multiple <style> tags in a Vue template!';
        }

        if (component.styles.length) {
            style = component.styles[0].content;
        }

        return style;
    }
}

/**
 * Install npm packages
 */
gulp.task('npm', function () {
    const gulpif = require('gulp-if'),
        newer = require('gulp-newer'),
        install = require('gulp-install');

    return gulp
        .src('package.json', { dot: true })

        .pipe(newer(config.cache))

        .pipe(
            gulpif(
                process.env.npm_lifecycle_event !== 'postinstall',
                install({
                    ignoreScripts: true,
                })
            )
        )

        .pipe(gulp.dest(config.cache));
});

/**
 * Install composer packages
 */
gulp.task('composer', function () {
    const newer = require('gulp-newer'),
        install = require('gulp-install');

    return gulp
        .src('composer.json', { dot: true, allowEmpty: true })

        .pipe(newer(config.cache))

        .pipe(install())

        .pipe(gulp.dest(config.cache));
});

/**
 * Process/optimize images and copy over to public.
 */
gulp.task('image:process', function () {
    const gulpif = require('gulp-if'),
        newer = require('gulp-newer'),
        through2 = require('through2'),
        clean = require('gulp-dest-clean'),
        imagemin = require('gulp-imagemin');

    embedHandler('reset', 'image');

    return (
        gulp
            .src(config.folders.images, { cwd: 'dev/', base: 'dev/', dot: true })

            // clean public
            .pipe(clean('public/', globify(config.folders.images, '', '', true)))

            // stat asset for embed
            .pipe(
                gulpif((file) => {
                    embedHandler('add', 'image', file);
                }, through2.obj())
            )

            // test cache
            .pipe(newer('public/'))

            // stat asset for embed
            .pipe(
                gulpif((file) => {
                    embedHandler('add', 'image', file);
                }, through2.obj())
            )

            // optimize
            .pipe(imagemin([imagemin.gifsicle(config.images.gifsicle), imagemin.mozjpeg(config.images.mozjpeg), imagemin.optipng(config.images.optipng), imagemin.svgo(config.images.svgo)], { verbose: true }))

            // copy to public
            .pipe(gulp.dest('public/'))

            .on('end', () => {
                embedHandler('clean', 'image');

                embedHandler('save');
            })
    );
});

/**
 * Watcher for images.
 */
gulp.task('image:watcher', function (cb) {
    gulp.watch(config.folders.images, { usePolling: true, cwd: 'dev/' }, gulp.series('image:process')).on('all', watchEvent);

    cb();
});

gulp.task('image:watch', gulp.series('image:process', 'image:watcher'));

/**
 * Shortcut for images.
 */
gulp.task('image', gulp.series('image:process'));

/**
 * Sync special folders and files over to public.
 */
gulp.task('sync:process', function (cb) {
    if (!config.sync.files || !config.sync.files.length) {
        return cb();
    }

    const size = require('gulp-size'),
        newer = require('gulp-newer'),
        clean = require('gulp-dest-clean');

    return (
        gulp
            .src(config.sync.files, { cwd: 'dev/', base: 'dev/' }, { dot: true })

            .pipe(clean('public/', globify(config.sync.files, '', '', true)))

            .pipe(newer('public/'))

            // display filesize
            .pipe(size({ title: 'SYNC:', showFiles: true, showTotal: false }))

            .pipe(gulp.dest('public/'))
    );
});

/**
 * Watcher for sync special folders and files.
 */
gulp.task('sync:watcher', function (cb) {
    gulp.watch(config.sync.files, { usePolling: true, cwd: 'dev/' }, gulp.series('sync:process')).on('all', watchEvent);

    cb();
});

gulp.task('sync:watch', gulp.series('sync:process', 'sync:watcher'));

/**
 * Shortcut for sync special folders and files.
 */
gulp.task('sync', gulp.series('sync:process'));

/**
 * Process each SASS file and output it into the cache directory.
 */
gulp.task('sass:process', function () {
    const fs = require('fs'),
        path = require('path'),
        merge = require('merge2'),
        sass = require('gulp-sass'),
        size = require('gulp-size'),
        gulpif = require('gulp-if'),
        cssnano = require('cssnano'),
        newer = require('gulp-newer'),
        through2 = require('through2'),
        header = require('gulp-header'),
        filter = require('gulp-filter'),
        modify = require('gulp-modify'),
        postcss = require('gulp-postcss'),
        plumber = require('gulp-plumber'),
        clean = require('gulp-dest-clean'),
        stylelint = require('gulp-stylelint'),
        autoprefixer = require('autoprefixer'),
        sourcemaps = require('gulp-sourcemaps'),
        revertpath = require('gulp-revert-path'),
        filterUnderscore = filter(['**/*', '!**/_*.scss'], { dot: true });

    let rebuild = false;

    embedHandler('reset', 'embed');

    const tasks = config.sass.files.map((settings, index) => {
        const cache = config.cache + 'sass/' + index + '/';

        // reset errors
        errors.sass[index] = {
            error: 0,
            timer: process.hrtime(),
        };

        // reset processed state
        processed.sass[index] = false;

        return (
            gulp
                .src(settings.files, { dot: true, base: './' })

                // error handling
                .on('error', function (error) {
                    errorHandler.call(this, error, 'sass', index);
                })
                .pipe(
                    plumber({
                        errorHandler: function (error) {
                            errorHandler.call(this, error, 'sass', index);
                        },
                    })
                )

                .pipe(clean(cache, ['!**', '**/*.scss', '**/*.map']))

                // if underscore file, check against _cache.scss
                .pipe(gulpif('**/_*.scss', newer({ dest: cache })))

                // if underscore file, copy over to _cache.scss
                .pipe(gulpif('**/_*.scss', gulp.dest(cache)))

                // if underscore file, rebuild everything
                .pipe(
                    gulpif((file) => {
                        if (file.relative.match(/_[^/\\]+$/)) {
                            rebuild = true;
                        }
                    }, through2.obj())
                )

                // stat file for embed
                .pipe(
                    gulpif((file) => {
                        file = path.relative(path.join(process.cwd(), 'dev'), file.path);

                        embedHandler('add', 'embed', null, file);
                    }, through2.obj())
                )

                // if we are not rebuilding, check against cache.css
                .pipe(
                    gulpif(() => {
                        return !rebuild;
                    }, newer({ dest: cache }))
                )

                // unstat file for embed
                .pipe(
                    gulpif((file) => {
                        file = path.relative(path.join(process.cwd(), 'dev'), file.path);

                        embedHandler('reset', 'embed', null, file);
                    }, through2.obj())
                )

                // filter special "_*.scss" files
                .pipe(filterUnderscore)

                .pipe(
                    gulpif(
                        '**/*.vue',
                        modify({
                            fileModifier(file, contents) {
                                return vueHandler(file, contents, 'sass:process');
                            },
                        })
                    )
                )

                // optional linter
                .pipe(gulpif(settings.linter, stylelint(settings.linter)))

                // common imports
                .pipe(gulpif(settings.imports && settings.imports.length ? ['**/*.scss', '**/*.vue'] : false, header('@import "' + (settings.imports || []).join('"; @import "') + '";')))

                // optional sourcemaps
                .pipe(gulpif(settings.sourcemaps, sourcemaps.init({ loadMaps: true })))

                // sass
                .pipe(sass(settings.options))

                // optional cssnano and autoprefixer
                .pipe(
                    gulpif(
                        settings.cssnano || settings.autoprefixer,
                        postcss(
                            [settings.autoprefixer ? autoprefixer(settings.autoprefixer) : null, settings.cssnano ? cssnano(settings.cssnano) : null].filter((plugin) => {
                                return plugin !== null;
                            })
                        )
                    )
                )

                // revert extension, as sass changes it to .css
                .pipe(revertpath())

                // restore original sourcesContent after transformation
                .pipe(
                    gulpif(
                        function (file) {
                            return file.sourceMap;
                        },
                        modify({
                            fileModifier(file, contents) {
                                const originalContent = fs.readFileSync(file.relative);

                                if (!file.sourceMap.sourcesContent) {
                                    file.sourceMap.sources = [path.basename(file.relative)];
                                    file.sourceMap.sourcesContent = [];
                                }

                                file.sourceMap.sourcesContent[0] = originalContent.toString();

                                return contents;
                            },
                        })
                    )
                )

                // mark file as processed for concatination
                .pipe(
                    gulpif(() => {
                        processed.sass[index] = true;
                    }, through2.obj())
                )

                // display filesize
                .pipe(size({ title: 'CSS ' + settings.name + ':', showFiles: true, showTotal: false }))

                // optional sourcemaps
                .pipe(
                    gulpif(
                        settings.sourcemaps,
                        sourcemaps.write('./', {
                            sourceRoot: null,
                            addComment: false,
                            mapSources(sourcePath, file) {
                                if (path.basename(sourcePath) === path.basename(file.relative)) {
                                    return path.basename(sourcePath);
                                }

                                return path.relative(path.dirname(file.relative), sourcePath).replace(/\\/g, '/');
                            },
                        })
                    )
                )

                .pipe(gulp.dest(cache))
        );
    });

    return merge(tasks).on('end', () => {
        embedHandler('clean', 'embed');

        embedHandler('save');
    });
});

/**
 * Concatenate all cached SASS files into a single file.
 */
gulp.task('sass:concat', function () {
    const path = require('path'),
        merge = require('merge2'),
        size = require('gulp-size'),
        gulpif = require('gulp-if'),
        argv = require('yargs').argv,
        cssnano = require('cssnano'),
        through2 = require('through2'),
        concat = require('gulp-concat'),
        filter = require('gulp-filter'),
        plumber = require('gulp-plumber'),
        postcss = require('gulp-postcss'),
        sourcemaps = require('gulp-sourcemaps'),
        filterUnderscore = filter(['**/*', '!**/_*.scss'], { dot: true }),
        isProduction = argv.production;

    const tasks = config.sass.files
        .map((settings, index) => {
            // skip concatination if there is nothing to do
            if (!isProduction && typeof processed.sass[index] !== 'undefined' && !processed.sass[index]) {
                return;
            }

            // reset errors if not already done by process
            if (typeof errors.sass[index] === 'undefined') {
                errors.sass[index] = {
                    error: 0,
                    timer: process.hrtime(),
                };
            }

            const cache = config.cache + 'sass/' + index + '/';

            return (
                gulp
                    .src(globify(settings.files, cache), { dot: true, base: './', allowEmpty: true })

                    // error handling
                    .on('error', function (error) {
                        errorHandler.call(this, error, 'sass', index);
                    })
                    .pipe(
                        plumber({
                            errorHandler: function (error) {
                                errorHandler.call(this, error, 'sass', index);
                            },
                        })
                    )

                    // filter special "_*.scss" files
                    .pipe(filterUnderscore)

                    // optional sourcemaps
                    .pipe(gulpif(!isProduction && settings.sourcemaps, sourcemaps.init({ loadMaps: true })))

                    .pipe(concat(settings.name, { newLine: '' }))

                    // display total compressed size
                    .pipe(size({ title: 'CSS:', showFiles: true, gzip: true }))

                    // optional cssnano
                    .pipe(gulpif(isProduction && settings.cssnanoProduction, postcss([cssnano(settings.cssnanoProduction)])))

                    // display total compressed size
                    .pipe(gulpif(isProduction && settings.cssnanoProduction, size({ title: 'CSS:', showFiles: true, gzip: true })))

                    .pipe(gulp.dest('public/' + config.folders.css))

                    // optional sourcemaps
                    .pipe(
                        gulpif(
                            !isProduction && settings.sourcemaps,
                            sourcemaps.write('./', {
                                sourceRoot: '/',
                                mapSources(sourcePath) {
                                    return path.relative(cache + 'dev/', sourcePath).replace(/\\/g, '/');
                                },
                            })
                        )
                    )

                    .pipe(gulpif(!isProduction, gulp.dest('dev/' + config.folders.css)))

                    .pipe(browsersync ? browsersync.stream({ match: '**/*.css' }) : through2.obj())
            );
        })
        .filter((n) => {
            return n;
        });

    return merge(tasks).on('end', function () {
        popup('sass');
    });
});

/**
 * Linter for SASS.
 */
gulp.task('sass:lint', function () {
    const fs = require('fs'),
        path = require('path'),
        merge = require('merge2'),
        crypto = require('crypto'),
        gulpif = require('gulp-if'),
        argv = require('yargs').argv,
        through2 = require('through2'),
        modify = require('gulp-modify'),
        plumber = require('gulp-plumber'),
        stylelint = require('gulp-stylelint'),
        isFix = argv.fix;

    const tasks = config.sass.files.map((settings, index) => {
        // reset errors
        errors.sass[index] = {
            error: 0,
            timer: process.hrtime(),
        };

        return (
            gulp
                .src(settings.files, { dot: true, base: './' })

                // error handling
                .on('error', function (error) {
                    errorHandler.call(this, error, 'sass', index);
                })
                .pipe(
                    plumber({
                        errorHandler: function (error) {
                            errorHandler.call(this, error, 'sass', index);
                        },
                    })
                )

                // extract style
                .pipe(
                    gulpif(
                        '**/*.vue',
                        modify({
                            fileModifier(file, contents) {
                                return vueHandler(file, contents, 'sass:lint');
                            },
                        })
                    )
                )

                // keep a hash of the original file
                .pipe(
                    gulpif(function (file) {
                        file.hash = crypto.createHash('sha1').update(file.contents).digest('hex');
                    }, through2.obj())
                )

                // lint
                .pipe(
                    stylelint({
                        ...(settings.linter || {}),
                        fix: isFix,
                    })
                )

                // if stylelint fixed the file, save it
                .pipe(
                    gulpif(function (file) {
                        if (!isFix) {
                            return false;
                        }

                        // fix only the <style> part
                        if (path.extname(file.path) === '.vue' && file.component && file.component.styles && file.component.styles.length) {
                            const style = file.component.styles[0],
                                oldContent = fs.readFileSync(file.relative).toString(),
                                newContent =
                                    oldContent.slice(0, style.start) +
                                    file.contents
                                        .toString()
                                        .replace(/(\r?\n)?(\r?\n)*/, '$1')
                                        .replace(/^(?!\s*$)/gm, '    ') +
                                    oldContent.slice(style.end);

                            file.contents = Buffer.from(newContent);
                        }

                        // ignore .css files
                        else if (path.extname(file.path) !== '.scss') {
                            return false;
                        }

                        const hash = crypto.createHash('sha1').update(file.contents).digest('hex');

                        return file.hash !== hash;
                    }, gulp.dest('./'))
                )
        );
    });

    return merge(tasks);
});

/**
 * Watcher for SASS.
 */
gulp.task('sass:watcher', function (cb) {
    let glob = [];

    config.sass.files.forEach(function (settings) {
        glob = glob.concat(settings.files);
    });

    gulp.watch(glob, { usePolling: true }, gulp.series('sass:process', 'sass:concat')).on('all', watchEvent);

    cb();
});

gulp.task('sass:watch', gulp.series('sass:process', 'sass:concat', 'image:watcher', 'sass:watcher'));

/**
 * Shortcut for SASS.
 */
gulp.task('sass', gulp.series('sass:process', 'sass:concat'));

/**
 * Process each javascript file and output it into the cache directory.
 */
gulp.task('javascript:process', function () {
    const fs = require('fs'),
        ejs = require('ejs'),
        path = require('path'),
        merge = require('merge2'),
        size = require('gulp-size'),
        gulpif = require('gulp-if'),
        babel = require('gulp-babel'),
        newer = require('gulp-newer'),
        through2 = require('through2'),
        eslint = require('gulp-eslint'),
        filter = require('gulp-filter'),
        modify = require('gulp-modify'),
        uglify = require('gulp-uglify'),
        multipipe = require('multipipe'),
        plumber = require('gulp-plumber'),
        clean = require('gulp-dest-clean'),
        sourcemaps = require('gulp-sourcemaps'),
        revertpath = require('gulp-revert-path');

    embedHandler('reset', 'embed');

    const tasks = config.javascript.files.map((settings, index) => {
        const cache = config.cache + 'javascript/' + index + '/',
            filterMin = filter(['**/*', '!**/*.min.*', '!**/*-min.*'], { dot: true, restore: true });

        // reset errors
        errors.javascript[index] = {
            error: 0,
            timer: process.hrtime(),
        };

        // reset processed state
        processed.javascript[index] = false;

        return (
            gulp
                .src(settings.files, { dot: true, base: './' })

                // error handling
                .on('error', function (error) {
                    errorHandler.call(this, error, 'javascript', index);
                })
                .pipe(
                    plumber({
                        errorHandler: function (error) {
                            errorHandler.call(this, error, 'javascript', index);
                        },
                    })
                )

                .pipe(clean(cache, ['!**', '**/*.map']))

                // stat file for embed
                .pipe(
                    gulpif((file) => {
                        file = path.relative(path.join(process.cwd(), 'dev'), file.path);

                        embedHandler('add', 'embed', null, file);
                    }, through2.obj())
                )

                .pipe(newer(cache))

                // unstat file for embed
                .pipe(
                    gulpif((file) => {
                        file = path.relative(path.join(process.cwd(), 'dev'), file.path);

                        embedHandler('reset', 'embed', null, file);
                    }, through2.obj())
                )

                // optional sourcemaps
                .pipe(gulpif(settings.sourcemaps, sourcemaps.init({ loadMaps: true })))

                // filter already minified files
                .pipe(filterMin)

                .pipe(
                    gulpif(
                        '**/*.ejs',
                        modify({
                            fileModifier(file, contents) {
                                let result = contents.trim();

                                const filename = path.basename(file.relative, '.ejs'),
                                    template = ejs.compile(result, {
                                        cache: false,
                                        client: true,
                                        strict: false,
                                        escape: 'escapeFn',
                                        localsName: 'data',
                                        rmWhitespace: false,
                                        compileDebug: false,
                                        outputFunctionName: 'echo',
                                    });

                                result = template
                                    .toString()
                                    .replace(/\\r\\n/g, '\\n')
                                    .replace(/(\\n|\s)\s+/g, '$1')
                                    .replace(/([^\n]*)(\n|$)/g, function (_, line) {
                                        const linebreaks = (line.match(/\\n/g) || []).length;

                                        return line + '\n'.repeat(linebreaks);
                                    })
                                    .replace(/^function[^]*escapeFn;/, "S.ejs.cache['" + filename + "'] = function (data) {")
                                    .replace(/escape(Fn)?\s*\(/g, 'this.escape(')
                                    .replace(/render\s*\(/g, 'this.render(')
                                    .replace(/embed\s*\((.*?\))/g, function (_, match) {
                                        // noinspection JSUnusedLocalSymbols
                                        const context = { options: { file: file.relative } };

                                        return "'" + eval('getImage.call(context, ' + match).replace(/'/g, "\\'") + "'";
                                    });

                                return result;
                            },
                        })
                    )
                )

                .pipe(
                    gulpif(
                        '**/*.vue',
                        modify({
                            fileModifier(file, contents) {
                                return vueHandler(file, contents, 'javascript:process');
                            },
                        })
                    )
                )

                // optional linter
                .pipe(
                    gulpif(function (file) {
                        if (!settings.linter) {
                            return false;
                        }

                        return path.extname(file.path) === '.js' || path.extname(file.path) === '.vue';
                    }, multipipe(eslint(settings.linter), eslint.format(), eslint.failAfterError()))
                )

                // optional babel
                .pipe(gulpif(settings.babel, babel(settings.babel)))

                // revert extension, as babel changes it to .js
                .pipe(revertpath())

                // restore original sourcesContent after transformation
                .pipe(
                    gulpif(
                        function (file) {
                            if (!file.sourceMap) {
                                return false;
                            }

                            return path.extname(file.path) === '.ejs' || path.extname(file.path) === '.vue';
                        },
                        modify({
                            fileModifier(file, contents) {
                                const originalContent = fs.readFileSync(file.relative);

                                file.sourceMap.sourcesContent = [originalContent.toString()];

                                return contents;
                            },
                        })
                    )
                )

                // optional uglify
                .pipe(gulpif(settings.uglify, uglify(settings.uglify)))

                // restore filtered files
                .pipe(filterMin.restore)

                // custom modifications of the file content
                .pipe(
                    modify({
                        fileModifier(file, contents) {
                            let result = contents.trim();

                            // strip empty file processed by babel
                            if (result === '"use strict";') {
                                return '';
                            }

                            // strip possible sourceMappingURLs from already minified scripts
                            result = result.replace(/^\/\/[@#] source(Mapping)?URL=.*$/gm, '');

                            // last line is a single-line comment
                            if (result.match(/\/\/[^\n]+$/)) {
                                return result + '\n';
                            }

                            return result;
                        },
                    })
                )

                // mark file as processed for concatination
                .pipe(
                    gulpif(() => {
                        processed.javascript[index] = true;
                    }, through2.obj())
                )

                // display file size
                .pipe(size({ title: 'JS ' + settings.name + ':', showFiles: true, showTotal: false }))

                // optional sourcemaps
                .pipe(
                    gulpif(
                        settings.sourcemaps,
                        sourcemaps.write('./', {
                            sourceRoot: null,
                            addComment: false,
                            mapSources(sourcePath, file) {
                                if (path.basename(sourcePath) === path.basename(file.relative)) {
                                    return path.basename(sourcePath);
                                }

                                return path.relative(path.dirname(file.relative), sourcePath).replace(/\\/g, '/');
                            },
                        })
                    )
                )

                .pipe(gulp.dest(cache))
        );
    });

    return merge(tasks).on('end', () => {
        embedHandler('clean', 'embed');

        embedHandler('save');
    });
});

/**
 * Concatenate all cached javascript files into a single file.
 */
gulp.task('javascript:concat', function () {
    const path = require('path'),
        merge = require('merge2'),
        size = require('gulp-size'),
        gulpif = require('gulp-if'),
        argv = require('yargs').argv,
        through2 = require('through2'),
        concat = require('gulp-concat'),
        uglify = require('gulp-uglify'),
        plumber = require('gulp-plumber'),
        sourcemaps = require('gulp-sourcemaps'),
        isProduction = argv.production;

    const tasks = config.javascript.files
        .map((settings, index) => {
            // skip concatination if there is nothing to do
            if (!isProduction && typeof processed.javascript[index] !== 'undefined' && !processed.javascript[index]) {
                return;
            }

            // reset errors if not already done by process
            if (typeof errors.javascript[index] === 'undefined') {
                errors.javascript[index] = {
                    error: 0,
                    timer: process.hrtime(),
                };
            }

            const cache = config.cache + 'javascript/' + index + '/';

            return (
                gulp
                    .src(globify(settings.files, cache), { dot: true, base: './', allowEmpty: true })

                    // error handling
                    .on('error', function (error) {
                        errorHandler.call(this, error, 'javascript', index);
                    })
                    .pipe(
                        plumber({
                            errorHandler: function (error) {
                                errorHandler.call(this, error, 'javascript', index);
                            },
                        })
                    )

                    // optional sourcemaps
                    .pipe(gulpif(settings.sourcemaps, sourcemaps.init({ loadMaps: true })))

                    .pipe(concat(settings.name, { newLine: '' }))

                    // display total compressed size
                    .pipe(size({ title: 'JS:', showFiles: true, gzip: true }))

                    // optional uglify
                    .pipe(gulpif(isProduction && settings.uglifyProduction, uglify(settings.uglifyProduction)))

                    // display total compressed size
                    .pipe(gulpif(isProduction && settings.uglifyProduction, size({ title: 'JS:', showFiles: true, gzip: true })))

                    // output to public without sourcemaps
                    .pipe(gulp.dest('public/' + config.folders.javascript))

                    // optional sourcemaps
                    .pipe(
                        gulpif(
                            !isProduction && settings.sourcemaps,
                            sourcemaps.write('./', {
                                sourceRoot: '/',
                                mapSources(sourcePath) {
                                    return path.relative(cache + 'dev/', sourcePath).replace(/\\/g, '/');
                                },
                            })
                        )
                    )

                    // output to dev with optional sourcemaps
                    .pipe(gulpif(!isProduction, gulp.dest('dev/' + config.folders.javascript)))

                    .pipe(browsersync ? browsersync.stream({ match: '**/*.js' }) : through2.obj())
            );
        })
        .filter((n) => {
            return n;
        });

    return merge(tasks).on('end', function () {
        popup('javascript');
    });
});

/**
 * Linter for javascript.
 */
gulp.task('javascript:lint', function () {
    const fs = require('fs'),
        path = require('path'),
        merge = require('merge2'),
        gulpif = require('gulp-if'),
        argv = require('yargs').argv,
        eslint = require('gulp-eslint'),
        modify = require('gulp-modify'),
        multipipe = require('multipipe'),
        plumber = require('gulp-plumber'),
        isFix = argv.fix;

    const tasks = config.javascript.files.map((settings, index) => {
        // reset errors
        errors.javascript[index] = {
            error: 0,
            timer: process.hrtime(),
        };

        return (
            gulp
                .src(settings.files, { dot: true, base: './' })

                // error handling
                .on('error', function (error) {
                    errorHandler.call(this, error, 'javascript', index);
                })
                .pipe(
                    plumber({
                        errorHandler: function (error) {
                            errorHandler.call(this, error, 'javascript', index);
                        },
                    })
                )

                // extract script
                .pipe(
                    gulpif(
                        '**/*.vue',
                        modify({
                            fileModifier(file, contents) {
                                return vueHandler(file, contents, 'javascript:lint');
                            },
                        })
                    )
                )

                // linter
                .pipe(
                    gulpif(
                        ['**/*.js', '**/*.vue'],
                        multipipe(
                            eslint({
                                ...(settings.linter || {}),
                                fix: isFix,
                            }),
                            eslint.format(),
                            eslint.failAfterError()
                        )
                    )
                )

                // if eslint fixed the file, save it
                .pipe(
                    gulpif(function (file) {
                        if (!file.eslint || !file.eslint.fixed) {
                            return false;
                        }

                        // fix only the <script> part
                        if (path.extname(file.path) === '.vue' && file.component && file.component.script) {
                            const script = file.component.script,
                                oldContent = fs.readFileSync(file.relative).toString(),
                                newContent =
                                    oldContent.slice(0, script.start) +
                                    file.contents
                                        .toString()
                                        .replace(/(\r?\n)?(\/\/\r?\n)*/, '$1')
                                        .replace(/^(?!\s*$)/gm, '    ') +
                                    oldContent.slice(script.end);

                            file.contents = Buffer.from(newContent);
                        } else if (path.extname(file.path) !== '.js') {
                            return false;
                        }

                        return true;
                    }, gulp.dest('./'))
                )
        );
    });

    return merge(tasks);
});

/**
 * Watcher for javascript.
 */
gulp.task('javascript:watcher', function (cb) {
    let glob = [];

    config.javascript.files.forEach(function (javascript) {
        glob = glob.concat(javascript.files);
    });

    gulp.watch(glob, { usePolling: true }, gulp.series('javascript:process', 'javascript:concat')).on('all', watchEvent);

    cb();
});

gulp.task('javascript:watch', gulp.series('javascript:process', 'javascript:concat', 'javascript:watcher'));

/**
 * Shortcut for javascript.
 */
gulp.task('javascript', gulp.series('javascript:process', 'javascript:concat'));

/**
 * Clean cache and public assets.
 */
gulp.task('clean', function (cb) {
    const del = require('del');

    let paths = [config.cache];

    // assets
    paths = paths.concat(globify([config.folders.css, config.folders.sass, config.folders.javascript], 'public/'));

    // images and svgs
    paths = paths.concat(globify(config.folders.images, 'public/'));

    // special folders and files
    paths = paths.concat(globify(config.sync.files, 'public/'));

    del.sync(paths);

    cb();
});

/**
 * Start browsersync
 *
 * Arguments:
 * --tunnel            Opens a random public xyz.localtunnel.me tunnel
 * --tunnel=name       Opens a specific public name.localtunnel.me tunnel
 * --online            Detects external ip addresses (slow, automatically enabled for tunnel)
 * --subdomain=name    Use subdomain in proxy mode like http://subdomain.project.localhost.ew-72.de/
 */
gulp.task('browsersync', function () {
    const path = require('path'),
        argv = require('yargs').argv,
        settings = config.browsersync;

    browsersync = require('browser-sync').create();

    // default options
    if (typeof settings.watchOptions === 'undefined') {
        settings.watchOptions = {};
    }
    if (typeof settings.watchOptions.cwd === 'undefined') {
        settings.watchOptions.cwd = 'dev/';
    }
    if (typeof settings.watchOptions.usePolling === 'undefined') {
        settings.watchOptions.usePolling = true;
    }

    // replace variables
    if (typeof settings.proxy === 'string') {
        let subdomain = '';

        // setup subdomain
        if (argv.subdomain) {
            subdomain = argv.subdomain + '.';
        }

        settings.proxy = settings.proxy.replace('{{basename}}', subdomain + path.basename(process.cwd()));
    }

    // use localtunnel.me
    if (argv.tunnel) {
        settings.online = true;
        settings.open = 'tunnel';
        settings.tunnel = argv.tunnel;
        settings.socket = {
            domain: `//'+location.hostname+(location.hostname==='localhost'?':${settings.socket.port}':'')+'`,
        };
    }

    // detect external ip addresses (slow)
    else if (argv.online) {
        settings.online = true;
    }

    // force https proxy if enabled
    if (settings.https && settings.proxy) {
        settings.proxy = settings.proxy.replace(/^(https?:\/\/|)/, 'https://');
    }

    return browsersync.init(settings);
});

gulp.task('lint', gulp.series('sass:lint', 'javascript:lint'));

gulp.task('restarter', (done) => {
    let child = null;

    const spawn = require('child_process').spawn;

    function restart(done) {
        if (child) {
            child.kill();
        }

        if (!process.argv[2].endsWith(':real')) {
            process.argv[2] += ':real';
        }

        // noinspection JSCheckFunctionSignatures
        child = spawn(process.argv[0], process.argv.slice(1), { stdio: 'inherit' });

        done();
    }

    gulp.watch(['.eslintrc.js', '.stylelintrc.js', 'config.js', 'gulpfile.js', 'package.json'], { usePolling: true, dot: true }, restart).on('all', watchEvent);

    restart(done);
});

// sass includes image
gulp.task('watch:real', gulp.series(gulp.parallel('npm', 'composer'), 'image:watch', gulp.parallel('sass:watch', 'javascript:watch', 'sync:watch')));

gulp.task('watch', gulp.series('restarter'));

gulp.task('serve:real', gulp.series('watch:real', 'browsersync'));

gulp.task('serve', gulp.series('restarter'));

gulp.task('default', gulp.series(gulp.parallel('npm', 'composer'), 'image', gulp.parallel('sass', 'javascript', 'sync')));
