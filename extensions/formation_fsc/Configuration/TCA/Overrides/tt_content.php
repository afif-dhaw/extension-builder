<?php

/**
 * ========================================================================================
 *              Begin of needed code for adding formation_fsc_newcontentelement
 * ========================================================================================
 */
// Adds the content element to the "Type" dropdown
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        // title
        'LLL:EXT:formation_fsc/Resources/Private/Language/locallang.xlf:formation_fsc.newcontentelement.title',
        // plugin signature: extkey_identifier
        'formation_fsc_newcontentelement',
        // icon identifier
        'content-text',
    ],
    'textmedia',
    'after'
);


// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types']['formation_fsc_newcontentelement'] = [
    'showitem' => '
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header; Internal title (not displayed),
            bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
      ',
    'columnsOverrides' => [
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
            ],
        ],
    ],
];

/**
 * ========================================================================================
 *              End of needed code for adding formation_fsc_newcontentelement
 * =========================================================================================
 */
