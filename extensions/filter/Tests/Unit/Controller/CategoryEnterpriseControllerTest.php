<?php

declare(strict_types=1);

namespace Filter\Filter\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Afif <afifdhaw@gmail.com>
 */
class CategoryEnterpriseControllerTest extends UnitTestCase
{
    /**
     * @var \Filter\Filter\Controller\CategoryEnterpriseController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Filter\Filter\Controller\CategoryEnterpriseController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllCategoryEnterprisesFromRepositoryAndAssignsThemToView(): void
    {
        $allCategoryEnterprises = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $categoryEnterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\CategoryEnterpriseRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryEnterpriseRepository->expects(self::once())->method('findAll')->will(self::returnValue($allCategoryEnterprises));
        $this->subject->_set('categoryEnterpriseRepository', $categoryEnterpriseRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('categoryEnterprises', $allCategoryEnterprises);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenCategoryEnterpriseToView(): void
    {
        $categoryEnterprise = new \Filter\Filter\Domain\Model\CategoryEnterprise();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('categoryEnterprise', $categoryEnterprise);

        $this->subject->showAction($categoryEnterprise);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenCategoryEnterpriseToCategoryEnterpriseRepository(): void
    {
        $categoryEnterprise = new \Filter\Filter\Domain\Model\CategoryEnterprise();

        $categoryEnterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\CategoryEnterpriseRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $categoryEnterpriseRepository->expects(self::once())->method('add')->with($categoryEnterprise);
        $this->subject->_set('categoryEnterpriseRepository', $categoryEnterpriseRepository);

        $this->subject->createAction($categoryEnterprise);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenCategoryEnterpriseToView(): void
    {
        $categoryEnterprise = new \Filter\Filter\Domain\Model\CategoryEnterprise();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('categoryEnterprise', $categoryEnterprise);

        $this->subject->editAction($categoryEnterprise);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenCategoryEnterpriseInCategoryEnterpriseRepository(): void
    {
        $categoryEnterprise = new \Filter\Filter\Domain\Model\CategoryEnterprise();

        $categoryEnterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\CategoryEnterpriseRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $categoryEnterpriseRepository->expects(self::once())->method('update')->with($categoryEnterprise);
        $this->subject->_set('categoryEnterpriseRepository', $categoryEnterpriseRepository);

        $this->subject->updateAction($categoryEnterprise);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenCategoryEnterpriseFromCategoryEnterpriseRepository(): void
    {
        $categoryEnterprise = new \Filter\Filter\Domain\Model\CategoryEnterprise();

        $categoryEnterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\CategoryEnterpriseRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $categoryEnterpriseRepository->expects(self::once())->method('remove')->with($categoryEnterprise);
        $this->subject->_set('categoryEnterpriseRepository', $categoryEnterpriseRepository);

        $this->subject->deleteAction($categoryEnterprise);
    }
}
