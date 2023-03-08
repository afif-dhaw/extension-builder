<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Filter',
    'Enterprise',
    'enterprise'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Filter',
    'Filter',
    'filter'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Filter',
    'Listajax',
    'listajax'
);
