<?php

namespace GeorgRinger\News\Controller;

namespace Filter\Filter\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use GeorgRinger\News\Event\NewsListActionEvent;
use GeorgRinger\News\Utility\Cache;
use GeorgRinger\News\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

class NewsTestController extends \GeorgRinger\News\Controller\NewsController
{


    public function listAction(array $overwriteDemand = null)
    {

        $this->forwardToDetailActionWhenRequested();

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, __CLASS__);

        if ((int)($this->settings['disableOverrideDemand'] ?? 1) !== 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }
        $newsRecords = $this->newsRepository->findDemanded($demand);

        // pagination
        $paginationConfiguration = $this->settings['list']['paginate'] ?? [];
        $itemsPerPage = (int)(($paginationConfiguration['itemsPerPage'] ?? '') ?: 10);
        $maximumNumberOfLinks = (int)($paginationConfiguration['maximumNumberOfLinks'] ?? 0);

        $currentPage = $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1;
        $paginator = GeneralUtility::makeInstance(QueryResultPaginator::class, $newsRecords, $currentPage, $itemsPerPage, (int)($this->settings['limit'] ?? 0), (int)($this->settings['offset'] ?? 0));
        $paginationClass = $paginationConfiguration['class'] ?? SimplePagination::class;
        if (class_exists(NumberedPagination::class) && $paginationClass === NumberedPagination::class && $maximumNumberOfLinks) {
            $pagination = GeneralUtility::makeInstance(NumberedPagination::class, $paginator, $maximumNumberOfLinks);
        } elseif (class_exists($paginationClass)) {
            $pagination = GeneralUtility::makeInstance($paginationClass, $paginator);
        } else {
            $pagination = GeneralUtility::makeInstance(SimplePagination::class, $paginator);
        }

        $assignedValues = [
            'news' => $newsRecords,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'categories' => null,
            'tags' => null,
            'settings' => $this->settings,
            'pagination' => [
                'currentPage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
            ]
        ];

        if ($demand->getCategories() !== '') {
            $categoriesList = $demand->getCategories();
            if (is_string($categoriesList)) {
                $categoriesList = GeneralUtility::trimExplode(',', $categoriesList);
            }
            if (!empty($categoriesList)) {
                $assignedValues['categories'] = $this->categoryRepository->findByIdList($categoriesList);
            }
        }

        if ($demand->getTags() !== '') {
            $tagList = $demand->getTags();
            if (!is_array($tagList)) {
                $tagList = GeneralUtility::trimExplode(',', $tagList);
            }
            if (!empty($tagList)) {
                $assignedValues['tags'] = $this->tagRepository->findByIdList($tagList);
            }
        }

        $event = $this->eventDispatcher->dispatch(new NewsListActionEvent($this, $assignedValues));
        $this->view->assignMultiple($event->getAssignedValues());

        Cache::addPageCacheTagsByDemandObject($demand);
    }
}
