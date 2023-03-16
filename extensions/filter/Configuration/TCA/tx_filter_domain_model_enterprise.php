<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,address,phone,website,email,code_postal,ville',
        'iconfile' => 'EXT:filter/Resources/Public/Icons/tx_filter_domain_model_enterprise.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'name, address, phone, website, email, code_postal, ville, category, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime,  --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,category_perms'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_filter_domain_model_enterprise',
                'foreign_table_where' => 'AND {#tx_filter_domain_model_enterprise}.{#pid}=###CURRENT_PID### AND {#tx_filter_domain_model_enterprise}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'category_perms' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:category_perms',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0)',
                'treeConfig' => [
                    'parentField' => 'parent',
                    'appearance' => [
                        'expandAll' => false,
                        'showHeader' => false,
                        'maxLevels' => 99,
                    ],
                ],
                'size' => 20,
                'minitems' => 0,
            ]
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'phone' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'website' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.website',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ]
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,email',
                'default' => ''
            ]
        ],
        'code_postal' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.code_postal',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'ville' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.ville',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:filter/Resources/Private/Language/locallang_db.xlf:tx_filter_domain_model_enterprise.category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_filter_domain_model_categoryenterprise',
                'default' => 0,
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 1,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],

        ],
    
    ],
];
