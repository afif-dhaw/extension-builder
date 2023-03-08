<?php

defined('TYPO3_MODE') || exit('Access denied.');

call_user_func(
    function () {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    }
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import "EXT:formation_fsc/Configuration/TsConfig/*"');

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['formation_fsc_minimal'] = 'EXT:formation_fsc/Configuration/RTE/ProjectMinimal.yaml';
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['formation_fsc_default'] = 'EXT:formation_fsc/Configuration/RTE/ProjectDefault.yaml';
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['formation_fsc_headline'] = 'EXT:formation_fsc/Configuration/RTE/ProjectHeadline.yaml';
