<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'filter',
    'description' => '',
    'category' => 'plugin',
    'author' => 'Afif',
    'author_email' => 'afifdhaw@gmail.com',
    'state' => 'alpha',
    'clearCacheOnLoad' => 0,
    'uploadfolder' => FALSE,
    'version' => '1.0.0',
    'createDirs' => '',
    'clearCacheOnLoad' => TRUE,
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
            'news' => '9.4.0-9.9.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
