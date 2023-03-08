<?php

declare(strict_types=1);

namespace Filter\Filter\Controller;
use \Filter\Filter\Domain\Repository\EnterpriseRepository;
use \Filter\Filter\Domain\Repository\CategoryEnterpriseRepository;
/**
 * This file is part of the "filter" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 Afif <afifdhaw@gmail.com>, Softtodo
 */

/**
 * EnterpriseController
 */
class EnterpriseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    protected $enterpriseRepository = null;
    protected $categoryRepository = null;

    public function __construct(EnterpriseRepository $enterpriseRepository , CategoryEnterpriseRepository $categoryRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * action list
     *
     * @return string|object|null|void
     */
    public function listAction()
    {
        $enterprises = $this->enterpriseRepository->findAll();
        $category = $this->categoryRepository->findAll();
        $this->view->assign('enterprises', $enterprises);
        $this->view->assign('category', $category);
    }

    /**
     * action show
     *
     * @param \Filter\Filter\Domain\Model\Enterprise $enterprise
     * @return string|object|null|void
     */
    public function showAction(\Filter\Filter\Domain\Model\Enterprise $enterprise)
    {
        $this->view->assign('enterprise', $enterprise);
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
     * @param \Filter\Filter\Domain\Model\Enterprise $newEnterprise
     * @return string|object|null|void
     */
    public function createAction(\Filter\Filter\Domain\Model\Enterprise $newEnterprise)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->enterpriseRepository->add($newEnterprise);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Filter\Filter\Domain\Model\Enterprise $enterprise
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("enterprise")
     * @return string|object|null|void
     */
    public function editAction(\Filter\Filter\Domain\Model\Enterprise $enterprise)
    {
        $this->view->assign('enterprise', $enterprise);
    }

    /**
     * action update
     *
     * @param \Filter\Filter\Domain\Model\Enterprise $enterprise
     * @return string|object|null|void
     */
    public function updateAction(\Filter\Filter\Domain\Model\Enterprise $enterprise)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->enterpriseRepository->update($enterprise);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Filter\Filter\Domain\Model\Enterprise $enterprise
     * @return string|object|null|void
     */
    public function deleteAction(\Filter\Filter\Domain\Model\Enterprise $enterprise)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->enterpriseRepository->remove($enterprise);
        $this->redirect('list');
    }

    /**
     * action filter
     *
     * @return string|object|null|void
     */
    public function filterAction()
    {
       $query = $this->request->getArgument('query');
       $categories = $this->request->getArgument('categories');
    //    $this->enterpriseRepository->update($enterprise);
       debug($categories);
       debug($query);
    //    die;
    }
}