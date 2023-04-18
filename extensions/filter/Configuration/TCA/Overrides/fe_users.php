<?php
defined('TYPO3_MODE') or die();


$temporaryColumns = array(
    'role' => array(
        'target' => '',
        'exclude' => 1,
        'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang.xlf:fe_users.role',
        'config' => array(
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:filter/Resources/Private/Language/locallang.xlf:fe_users.admin', 'admin'],
                ['LLL:EXT:filter/Resources/Private/Language/locallang.xlf:fe_users.developer', 'developper']
            ],
            'default' => '',
        )
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $temporaryColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', 'role');
