<?php

declare(strict_types=1);

namespace Filter\Filter\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Core\Database\ConnectionPool;
/**
 * This file is part of the "filter" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 Afif <afifdhaw@gmail.com>, Softtodo
 */

/**
 * The repository for Enterprises
 */
class EnterpriseRepository extends Repository
{

    protected $connectionPool = null;

    protected $enterpriseName = 'tx_filter_domain_model_enterprise';


    // public function __construct(ConnectionPool $connectionPool)
    // {
    //     $this->connectionPool = $connectionPool;
    // }

    public function initializeObject()
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }



    public function getByQueryAndCategories(string $query = "" , array $categories = [])
    {
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($this->enterpriseName);
        $queryBuilder = $queryBuilder->select('*')->from($this->enterpriseName);
        $queryBuilder = $queryBuilder->where(

            $queryBuilder->expr()->in('category', [1])

            // $queryBuilder->expr()->like('name', $queryBuilder->createNamedParameter('%rprise%'))
        );
        // $queryBuilder->orWhere(
        //     // $queryBuilder->expr()->like('name', $queryBuilder->createNamedParameter('%rprise%'))
        //     $queryBuilder->expr()->in('category', [3,5])
        // );
    


        $queryResult =  $queryBuilder->execute();
        $results = $queryResult->fetchAll();
        debug($results);die;
    }
}
