<?php

defined('TYPO3_MODE') || exit('Access denied.');

call_user_func(
    function () {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    }
);
\FluidTYPO3\Flux\Core::registerProviderExtensionKey('SOFTTODO.Formation', 'Page');
\FluidTYPO3\Flux\Core::registerProviderExtensionKey('SOFTTODO.Formation', 'Content');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import "EXT:formation/Configuration/TsConfig/*"');

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['formation_minimal'] = 'EXT:formation/Configuration/RTE/ProjectMinimal.yaml';
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['formation_default'] = 'EXT:formation/Configuration/RTE/ProjectDefault.yaml';
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['formation_headline'] = 'EXT:formation/Configuration/RTE/ProjectHeadline.yaml';

