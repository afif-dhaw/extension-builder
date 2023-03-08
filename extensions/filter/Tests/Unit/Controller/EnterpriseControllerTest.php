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
class EnterpriseControllerTest extends UnitTestCase
{
    /**
     * @var \Filter\Filter\Controller\EnterpriseController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Filter\Filter\Controller\EnterpriseController::class))
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
    public function listActionFetchesAllEnterprisesFromRepositoryAndAssignsThemToView(): void
    {
        $allEnterprises = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\EnterpriseRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $enterpriseRepository->expects(self::once())->method('findAll')->will(self::returnValue($allEnterprises));
        $this->subject->_set('enterpriseRepository', $enterpriseRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('enterprises', $allEnterprises);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenEnterpriseToView(): void
    {
        $enterprise = new \Filter\Filter\Domain\Model\Enterprise();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('enterprise', $enterprise);

        $this->subject->showAction($enterprise);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenEnterpriseToEnterpriseRepository(): void
    {
        $enterprise = new \Filter\Filter\Domain\Model\Enterprise();

        $enterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\EnterpriseRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseRepository->expects(self::once())->method('add')->with($enterprise);
        $this->subject->_set('enterpriseRepository', $enterpriseRepository);

        $this->subject->createAction($enterprise);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenEnterpriseToView(): void
    {
        $enterprise = new \Filter\Filter\Domain\Model\Enterprise();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('enterprise', $enterprise);

        $this->subject->editAction($enterprise);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenEnterpriseInEnterpriseRepository(): void
    {
        $enterprise = new \Filter\Filter\Domain\Model\Enterprise();

        $enterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\EnterpriseRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseRepository->expects(self::once())->method('update')->with($enterprise);
        $this->subject->_set('enterpriseRepository', $enterpriseRepository);

        $this->subject->updateAction($enterprise);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenEnterpriseFromEnterpriseRepository(): void
    {
        $enterprise = new \Filter\Filter\Domain\Model\Enterprise();

        $enterpriseRepository = $this->getMockBuilder(\Filter\Filter\Domain\Repository\EnterpriseRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseRepository->expects(self::once())->method('remove')->with($enterprise);
        $this->subject->_set('enterpriseRepository', $enterpriseRepository);

        $this->subject->deleteAction($enterprise);
    }
}
