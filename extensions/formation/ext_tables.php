<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('formation', 'Configuration/TypoScript', 'Templates und Funktionen für diese Seite (SOFTTODO GmbH)');
    }
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

// backend stylesheets
$GLOBALS['TBE_STYLES']['skins']['formation'] = [
    'name' => 'formation',
    'stylesheetDirectories' => [
        'css' => 'EXT:formation/Resources/Public/Css/Backend/',
    ],
];