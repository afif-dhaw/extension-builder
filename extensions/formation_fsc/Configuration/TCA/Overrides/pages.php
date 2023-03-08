<?php
defined('TYPO3_MODE') || die();
call_user_func(function () {
    /**
     * Temporary variables
     */
    $extensionKey = 'formation_fsc';

    /**
     * Default PageTS for formation_fsc
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/All.tsconfig',
        'formation_fsc'
    );
});
