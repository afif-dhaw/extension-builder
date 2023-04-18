<?php
return [
    \GeorgRinger\News\Domain\Model\News::class => [
        'subclasses' => [
            0 => \Filter\Filter\Domain\Model\News::class,
        ]
    ],
    \Filter\Filter\Domain\Model\News::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 0,
    ],


    \Evoweb\SfRegister\Domain\Model\FrontendUser::class => [
        'subclasses' => [
            0 => \Filter\Filter\Domain\Model\FrontendUser::class,
        ]
    ],
    \Filter\Filter\Domain\Model\FrontendUser::class => [
        'tableName' => 'fe_users',
        'recordType' => 0,
    ]
];
