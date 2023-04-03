<?php
defined('TYPO3_MODE') or die();

$fields = array(
    'intro_text' => array(
        'exclude' => 1,
        'label' => 'Colored introductory text of the picture(optional)',
        'config' => array(
            'type' => 'input',
            'size' => 15
        ),
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', 'intro_text', 'after:teaser');
