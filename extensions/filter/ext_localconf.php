<?php
defined('TYPO3_MODE') || die();

(static function() {
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
            \Filter\Filter\Controller\EnterpriseController::class => 'filter, filterajax'
        ],
        // non-cacheable actions
        [
            \Filter\Filter\Controller\EnterpriseController::class => 'filter, filterajax'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    enterprise {
                        iconIdentifier = filter-plugin-enterprise
                        title = LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_enterprise.name
                        description = LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_enterprise.description
                        tt_content_defValues {
                            CType = list
                            list_type = filter_enterprise
                        }
                    }
                    filter {
                        iconIdentifier = filter-plugin-filter
                        title = LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_filter.name
                        description = LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_filter.description
                        tt_content_defValues {
                            CType = list
                            list_type = filter_filter
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
})();
