<?php

declare(strict_types=1);

namespace Filter\Filter\Controller;


/**
 * This file is part of the "filter" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 Afif <afifdhaw@gmail.com>, Softtodo
 */

/**
 * CategoryEnterpriseController
 */
class CategoryEnterpriseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryEnterpriseRepository
     *
     * @var \Filter\Filter\Domain\Repository\CategoryEnterpriseRepository
     */
    protected $categoryEnterpriseRepository = null;

    /**
     * @param \Filter\Filter\Domain\Repository\CategoryEnterpriseRepository $categoryEnterpriseRepository
     */
    public function injectCategoryEnterpriseRepository(\Filter\Filter\Domain\Repository\CategoryEnterpriseRepository $categoryEnterpriseRepository)
    {
        $this->categoryEnterpriseRepository = $categoryEnterpriseRepository;
    }

    /**
     * action list
     *
     * @return string|object|null|void
     */
    public function listAction()
    {
        $categoryEnterprises = $this->categoryEnterpriseRepository->findAll();
        $this->view->assign('categoryEnterprises', $categoryEnterprises);
    }

    /**
     * action index
     *
     * @return string|object|null|void
     */
    public function indexAction()
    {
    }

    /**
     * action show
     *
     * @param \Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise
     * @return string|object|null|void
     */
    public function showAction(\Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise)
    {
        $this->view->assign('categoryEnterprise', $categoryEnterprise);
    }

    /**
     * action new
     *
     * @return string|object|null|void
     */
    public function newAction()
    {
    }

    /**
     * action create
     *
     * @param \Filter\Filter\Domain\Model\CategoryEnterprise $newCategoryEnterprise
     * @return string|object|null|void
     */
    public function createAction(\Filter\Filter\Domain\Model\CategoryEnterprise $newCategoryEnterprise)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->categoryEnterpriseRepository->add($newCategoryEnterprise);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("categoryEnterprise")
     * @return string|object|null|void
     */
    public function editAction(\Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise)
    {
        $this->view->assign('categoryEnterprise', $categoryEnterprise);
    }

    /**
     * action update
     *
     * @param \Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise
     * @return string|object|null|void
     */
    public function updateAction(\Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->categoryEnterpriseRepository->update($categoryEnterprise);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise
     * @return string|object|null|void
     */
    public function deleteAction(\Filter\Filter\Domain\Model\CategoryEnterprise $categoryEnterprise)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->categoryEnterpriseRepository->remove($categoryEnterprise);
        $this->redirect('list');
    }
}
