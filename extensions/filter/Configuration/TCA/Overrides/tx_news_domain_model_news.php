<?php
defined('TYPO3_MODE') or die();

$fields = array(
    'intro_text' => array(
        'exclude' => 1,
        'label' => 'Colored introductory text of the picture(optional)',
        'config' => array(
            'type' => 'text',
            'enableRichtext' => true,
            'richtextConfiguration' => 'default',
            'size' => '1000000000'
        ),
    ),
    'title_text' => array(
        'exclude' => 1,
        'label' => 'Introductory text above the picture(h1,h2,h3)',
        'config' => array(
            'type' => 'text',
            'enableRichtext' => true,
            'richtextConfiguration' => 'default',
            'size' => '1000000000'
        ),
    ),
    'bodytext' => array(
        'exclude' => 1,
        'label' => 'Text',
        'config' => array(
            'type' => 'text',
            'enableRichtext' => true,
            'richtextConfiguration' => 'default',
            'size' => '1000000000'
        ),
    )
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', 'intro_text', '', 'after:teaser');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', 'title_text', '', 'after:intro_text');
