<?php
defined('TYPO3_MODE') || die();
call_user_func(function () {
    /**
     * Temporary variables
     */
    $extensionKey = 'formation_fsc';

    /**
     * Default TypoScript for formation_fsc
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        'formation_fsc'
    );
});
