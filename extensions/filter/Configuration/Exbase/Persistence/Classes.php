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
    ]
];
