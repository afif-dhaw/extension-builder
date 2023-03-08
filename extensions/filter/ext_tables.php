<?php
defined('TYPO3_MODE') || die();

(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_filter_domain_model_enterprise', 'EXT:filter/Resources/Private/Language/locallang_csh_tx_filter_domain_model_enterprise.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_filter_domain_model_enterprise');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_filter_domain_model_categoryenterprise', 'EXT:filter/Resources/Private/Language/locallang_csh_tx_filter_domain_model_categoryenterprise.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_filter_domain_model_categoryenterprise');
})();
