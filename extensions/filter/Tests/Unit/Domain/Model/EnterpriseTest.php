<?php

declare(strict_types=1);

namespace Filter\Filter\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Afif <afifdhaw@gmail.com>
 */
class EnterpriseTest extends UnitTestCase
{
    /**
     * @var \Filter\Filter\Domain\Model\Enterprise|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Filter\Filter\Domain\Model\Enterprise::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName(): void
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('name'));
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress(): void
    {
        $this->subject->setAddress('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('address'));
    }

    /**
     * @test
     */
    public function getPhoneReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPhone()
        );
    }

    /**
     * @test
     */
    public function setPhoneForStringSetsPhone(): void
    {
        $this->subject->setPhone('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('phone'));
    }

    /**
     * @test
     */
    public function getWebsiteReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getWebsite()
        );
    }

    /**
     * @test
     */
    public function setWebsiteForStringSetsWebsite(): void
    {
        $this->subject->setWebsite('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('website'));
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail(): void
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('email'));
    }

    /**
     * @test
     */
    public function getCodePostalReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCodePostal()
        );
    }

    /**
     * @test
     */
    public function setCodePostalForStringSetsCodePostal(): void
    {
        $this->subject->setCodePostal('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('codePostal'));
    }

    /**
     * @test
     */
    public function getVilleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getVille()
        );
    }

    /**
     * @test
     */
    public function setVilleForStringSetsVille(): void
    {
        $this->subject->setVille('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('ville'));
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForCategoryEnterprise(): void
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForObjectStorageContainingCategoryEnterpriseSetsCategory(): void
    {
        $category = new \Filter\Filter\Domain\Model\CategoryEnterprise();
        $objectStorageHoldingExactlyOneCategory = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneCategory->attach($category);
        $this->subject->setCategory($objectStorageHoldingExactlyOneCategory);

        self::assertEquals($objectStorageHoldingExactlyOneCategory, $this->subject->_get('category'));
    }

    /**
     * @test
     */
    public function addCategoryToObjectStorageHoldingCategory(): void
    {
        $category = new \Filter\Filter\Domain\Model\CategoryEnterprise();
        $categoryObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $categoryObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($category));
        $this->subject->_set('category', $categoryObjectStorageMock);

        $this->subject->addCategory($category);
    }

    /**
     * @test
     */
    public function removeCategoryFromObjectStorageHoldingCategory(): void
    {
        $category = new \Filter\Filter\Domain\Model\CategoryEnterprise();
        $categoryObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $categoryObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($category));
        $this->subject->_set('category', $categoryObjectStorageMock);

        $this->subject->removeCategory($category);
    }
}
