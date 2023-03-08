<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('formation_fsc', 'Configuration/TypoScript', 'Templates und Funktionen für diese Seite (SOFTTODO GmbH)');
    }
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

// backend stylesheets
$GLOBALS['TBE_STYLES']['skins']['formation_fsc'] = [
    'name' => 'formation_fsc',
    'stylesheetDirectories' => [
        'css' => 'EXT:formation_fsc/Resources/Public/Css/Backend/',
    ],
];
