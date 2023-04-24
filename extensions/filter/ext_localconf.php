<?php
defined('TYPO3_MODE') || die();
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \Filter\Filter\Hook\EnterpriseHook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News']['filter'] = 'filter';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\GeorgRinger\News\Controller\NewsController::class] = ['className' => \Filter\Filter\Controller\NewsTestController::class,];
(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Filter',
        'Enterprise',
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'list'
        ],
        // non-cacheable actions
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'list'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Filter',
        'Filter',
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'filter'
        ],
        // non-cacheable actions
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'filter'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Filter',
        'Listajax',
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'listajax'
        ],
        // non-cacheable actions
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'listajax'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Filter',
        'Filterajax',
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'filterajax'
        ],
        // non-cacheable actions
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'filterajax'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    enterprise {
                        iconIdentifier = filter-plugin-enterprise
                        title = enterprise
                        description = enterprise
                        tt_content_defValues {
                            CType = list
                            list_type = filter_enterprise
                        }
                    }
                    filter {
                        iconIdentifier = filter-plugin-filter
                        title = filter
                        description = filter
                        tt_content_defValues {
                            CType = list
                            list_type = filter_filter
                        }
                    }
                    listajax {
                        iconIdentifier = filter-plugin-listajax
                        title = listajax
                        description = listajax
                        tt_content_defValues {
                            CType = list
                            list_type = filter_listajax
                        }
                    }
                    filterajax {
                        iconIdentifier = filter-plugin-filterajax
                        title = filterajax
                        description = filterajax
                        tt_content_defValues {
                            CType = list
                            list_type = filter_filterajax
                        }
                    }
                }
                show = *
            }
       }'
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'filter-plugin-enterprise',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:filter/Resources/Public/Icons/user_plugin_enterprise.svg']
    );
    $iconRegistry->registerIcon(
        'filter-plugin-filter',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:filter/Resources/Public/Icons/user_plugin_filter.svg']
    );
    $iconRegistry->registerIcon(
        'filter-plugin-listajax',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:filter/Resources/Public/Icons/user_plugin_listajax.svg']
    );
    $iconRegistry->registerIcon(
        'filter-plugin-filterajax',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:filter/Resources/Public/Icons/user_plugin_filterajax.svg']
    );
})();





$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['sf_register']['extender']
[\Evoweb\SfRegister\Domain\Model\FrontendUser::class]['filter'] =
    'EXT:filter/Classes/Domain/Model/FrontendUser.php';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '@import \'EXT:filter/Configuration/TypoScript/Fields.typoscript\''
);
