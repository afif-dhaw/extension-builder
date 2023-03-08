module.exports = {
    /**
     * Project name used only for the success/error popups
     *
     * @var {string}
     */
    name: 'Project Name',

    /**
     * JavaScript configuration
     *
     * @var {Object}
     */
    javascript: {
        /**
         * Concatenate one or more javascript files
         *
         * @var {Object[]}
         */
        files: [
            {
                /**
                 * Name of the concatenated javascript file.
                 *
                 * This should match the G_PROJECT constant for static projects.
                 *
                 * @see /dev/lib/config.php  define('G_PROJECT', ...);
                 * @var {string}
                 */
                name: 'scripts.min.js',

                /**
                 * List all files here with glob syntax in specific order to be concatenated
                 *
                 * @see https://github.com/isaacs/node-glob
                 * @var {string[]}
                 */
                files: [
                    // polyfills required by IE11
                    'node_modules/@babel/polyfill/dist/polyfill.min.js',
                    'node_modules/element-qsa-scope/index.js',
                    'node_modules/intersection-observer/intersection-observer.js',

                    // --- INCLUDE EXTERNAL LIBRARIES BELOW THIS LINE ---

                    'node_modules/jquery/dist/jquery.min.js',

                    // $ npm install select2 --save
                    // 'node_modules/select2/dist/js/select2.min.js',

                    // $ npm install jquery.scrollto --save
                    // 'node_modules/jquery.scrollto/jquery.scrollTo.min.js',

                    // $ npm install swiper --save
                    'node_modules/swiper/swiper-bundle.min.js',

                    // $ npm install vue --save
                    // 'node_modules/vue/dist/vue.runtime.min.js',

                    // --- INCLUDE EXTERNAL LIBRARIES ABOVE THIS LINE ---

                    // templates
                    'extensions/formation/Resources/Public/**/*.js',
                    'extensions/formation/Resources/Private/**/*.js',
                    '!extensions/formation/Resources/Private/**/_*.js',
                    'extensions/formation_fsc/Resources/Private/**/**/*.js',
                    '!extensions/formation_fsc/Resources/Private/**/**/_*.js',

                    // ejs
                    'extensions/formation/Resources/Private/**/*.ejs',
                    '!extensions/formation/Resources/Private/**/_*.ejs',
                    'extensions/formation_fsc/Resources/Private/**/*.ejs',
                    '!extensions/formation_fsc/Resources/Private/**/_*.ejs',

                    // shame
                    'dev/assets/js/shame.js',
                ],

                /* It is possible to define and override other options like "babel", "uglify", "linter" or "sourcemaps"
                 * for this specific file. If nothing defined, the global settings below will be used.
                 *
                 * babel: false,
                 * uglify: {
                 *      mangle: false,
                 * },
                 * sourcemaps: false,
                 **/
            },
        ],

        /**
         * Babel javascript compiler/transpiler to translate ES2015 to ES5
         *
         * @see https://babeljs.io/docs/usage/options/
         * @var {Object|boolean} Options object or false to disable
         */
        babel: {
            compact: true,
            sourceType: 'script',
            presets: ['@babel/env', '@vue/babel-preset-jsx'],
            plugins: [
                [
                    '@babel/plugin-transform-modules-commonjs',
                    {
                        allowTopLevelThis: true,
                    },
                ],
            ],
        },

        /**
         * UglifyJS3 JavasSript minifier
         *
         * @see https://github.com/terinjokes/gulp-uglify
         * @var {Object|boolean} Options object or false to disable
         */
        uglify: {
            compress: {
                drop_debugger: false,
            },
            mangle: {
                eval: true,
            },
        },

        /**
         * Overrides above settings when used with `--production` flag
         */
        uglifyProduction: {
            compress: {
                drop_console: true,
            },
            mangle: {
                eval: true,
            },
        },

        /**
         * ESLint javascript linter
         *
         * @see Configuration file .eslintrc.js
         * @see http://eslint.org/docs/developer-guide/nodejs-api#cliengine
         * @var {Object|boolean} Options object or false to disable
         */
        linter: {
            useEslintrc: false,
            configFile: '.eslintrc.js',
        },

        /**
         * Sourcemaps
         *
         * @var {boolean}
         */
        sourcemaps: true,
    },

    /**
     * SASS configuration
     *
     * @var {Object}
     */
    sass: {
        /**
         * Concatenate one or more SASS/CSS files
         *
         * @var {Object[]}
         */
        files: [
            {
                /**
                 * Name of the concatenated CSS file
                 *
                 * This should match the G_PROJECT constant for static projects.
                 *
                 * @see /dev/lib/config.php  define('G_PROJECT', ...);
                 * @var {string}
                 */
                name: 'styles.min.css',

                /**
                 * List all files here with glob syntax in specific order to be concatenated
                 *
                 * @see https://github.com/isaacs/node-glob
                 * @var {string[]}
                 */
                files: [
                    // required for the watcher
                    'dev/assets/sass/**/_*.scss',

                    // general/base stuff
                    'dev/assets/sass/_project/fonts.scss',
                    'node_modules/normalize.css/normalize.css',
                    'dev/assets/sass/_project/base.scss',
                    'dev/assets/sass/_project/globals.scss',

                    // --- INCLUDE EXTERNAL LIBRARIES BELOW THIS LINE ---

                    // $ npm install select2 --save
                    // 'node_modules/select2/dist/css/select2.min.css',

                    // $ npm install swiper --save
                    'node_modules/swiper/swiper-bundle.min.css',

                    // --- INCLUDE EXTERNAL LIBRARIES ABOVE THIS LINE ---

                    // templates
                    'extensions/formation/Resources/Private/**/*.scss',
                    '!extensions/formation/Resources/Private/**/_*.scss',
                    'extensions/formation_fsc/Resources/Private/**/**/*.scss',
                    '!extensions/formation_fsc/Resources/Private/**/**/_*.scss',

                    // shame
                    'dev/assets/sass/shame.scss',
                ],

                /* It is possible to define and override other options like "autoprefixer", "linter" or "sourcemaps"
                 * for this specific file. If nothing defined, the global settings below will be used.
                 *
                 * options: {
                 *     outputStyle: 'expanded',
                 * },
                 * linter: false,
                 * sourcemaps: false,
                 **/
            },
        ],

        /**
         * node-sass options
         *
         * @see https://github.com/sass/node-sass#options
         * @var {Object}
         */
        options: {
            outputStyle: 'compressed',

            /**
             * Additional "@import" paths
             */
            includePaths: ['dev/assets/sass/'],
        },

        /**
         * Auto "@import" these files at top of each SASS file
         *
         * YOU SHOULD NEVER NEED THIS
         *
         * @var {string[]}
         */
        imports: ['_common.scss'],

        /**
         * cssnano
         *
         * @see https://cssnano.co/guides/presets
         * @param {Object|boolean} Options object or false to disable
         */
        cssnano: {
            preset: 'default',
        },

        /**
         * Overrides above settings when used with `--production` flag
         */
        cssnanoProduction: {
            preset: 'advanced',
        },

        /**
         * Autoprefixer generates automatic vendor prefixes for new CSS features
         *
         * @see https://github.com/postcss/autoprefixer#options
         * @param {Object|boolean} Options object or false to disable
         */
        autoprefixer: {
            remove: true,
        },

        /**
         * Stylelint Linter
         *
         * @see https://github.com/olegskl/gulp-stylelint#options
         * @see https://stylelint.io/user-guide/usage/node-api#options
         * @var {Object|boolean} Options object or false to disable
         */
        linter: {
            syntax: 'scss',
            reporters: [
                {
                    formatter: 'string',
                    console: true,
                },
            ],
        },

        /**
         * Sourcemaps
         *
         * @var {boolean}
         */
        sourcemaps: true,
    },

    /**
     * Lossless image optimization/compression
     *
     * @var {Object}
     */
    images: {
        /**
         * Options for gifsicle
         *
         * @see https://github.com/imagemin/imagemin-gifsicle#options
         * @var {Object} Options object
         */
        gifsicle: {},

        /**
         * Options for mozjpeg
         *
         * @see https://github.com/imagemin/imagemin-mozjpeg#options
         * @var {Object} Options object
         */
        mozjpeg: {},

        /**
         * Options for optipng
         *
         * @see https://github.com/imagemin/imagemin-optipng#options
         * @var {Object} Options object
         */
        optipng: {},

        /**
         * Options for SVGO
         *
         * @see https://github.com/svg/svgo#what-it-can-do
         * @var {Object} Options object
         */
        svgo: {
            plugins: [{ removeTitle: true }, { removeViewBox: false }, { collapseGroups: false }],
        },
    },

    /**
     * Sync additional files and folders from "dev/" to "public/"
     *
     * All paths MUST be inside "dev/" and be relative to it!
     *
     * @var {Object}
     */
    sync: {
        /**
         * List all files here with glob syntax
         *
         * @see https://github.com/isaacs/node-glob
         * @var {string[]}
         */
        files: ['assets/fonts/**', 'assets/favicon/**'],
    },

    /**
     * BrowserSync
     *
     * @see https://www.browsersync.io/docs/options/
     * @var {Object}
     */
    browsersync: {
        /**
         * Reload page if the following files change
         *
         * @see https://github.com/isaacs/node-glob
         * @var {string[]}
         */
        // files: [
        //     '**/*.php',
        //     '**/*.html',
        // ],

        /**
         * Proxy url to local Apache server
         *
         * Variables:
         *      {{basename}}    The project directory name
         *
         * @var {string}
         */
        proxy: '{{basename}}.localhost.ew-72.de',

        online: false,
        logConnections: true,
        socket: {
            port: 3000,
        },
        ghostMode: {
            forms: true,
            clicks: true,
            scroll: true,
        },
    },

    /**
     * Configurable directory paths
     *
     * YOU SHOULD NEVER NEED THIS
     *
     * @var {Object}
     */
    folders: {
        css: 'assets/css/',
        sass: 'assets/sass/',
        javascript: 'assets/js/',
        images: ['assets/img/**', 'assets/svg/**/*', '!**/.*'],
    },

    /**
     * Gulp cache directory
     *
     * YOU SHOULD NEVER NEED THIS
     *
     * @var {string}
     */
    cache: '.gulp-cache/',
};
