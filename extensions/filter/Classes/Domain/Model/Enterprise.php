<?php

declare(strict_types=1);

namespace Filter\Filter\Domain\Model;


/**
 * This file is part of the "filter" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 Afif <afifdhaw@gmail.com>, Softtodo
 */

/**
 * Enterprise
 */
class Enterprise extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * address
     *
     * @var string
     */
    protected $address = '';

    /**
     * phone
     *
     * @var string
     */
    protected $phone = '';

    /**
     * website
     *
     * @var string
     */
    protected $website = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * codePostal
     *
     * @var string
     */
    protected $codePostal = '';

    /**
     * ville
     *
     * @var string
     */
    protected $ville = '';

    /**
     * category
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Filter\Filter\Domain\Model\CategoryEnterprise>
     */
    protected $category = null;

    /**
     * __construct
     */
    public function __construct()
    {

        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->category = $this->category ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * Returns the phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone
     *
     * @param string $phone
     * @return void
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * Returns the website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Sets the website
     *
     * @param string $website
     * @return void
     */
    public function setWebsite(string $website)
    {
        $this->website = $website;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Returns the codePostal
     *
     * @return string $codePostal
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Sets the codePostal
     *
     * @param string $codePostal
     * @return void
     */
    public function setCodePostal(string $codePostal)
    {
        $this->codePostal = $codePostal;
    }

    /**
     * Returns the ville
     *
     * @return string $ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Sets the ville
     *
     * @param string $ville
     * @return void
     */
    public function setVille(string $ville)
    {
        $this->ville = $ville;
    }

    /**
     * Adds a CategoryEnterprise
     *
     * @param \Filter\Filter\Domain\Model\CategoryEnterprise $category
     * @return void
     */
    public function addCategory(\Filter\Filter\Domain\Model\CategoryEnterprise $category)
    {
        $this->category->attach($category);
    }

    /**
     * Removes a CategoryEnterprise
     *
     * @param \Filter\Filter\Domain\Model\CategoryEnterprise $categoryToRemove The CategoryEnterprise to be removed
     * @return void
     */
    public function removeCategory(\Filter\Filter\Domain\Model\CategoryEnterprise $categoryToRemove)
    {
        $this->category->detach($categoryToRemove);
    }

    /**
     * Returns the category
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Filter\Filter\Domain\Model\CategoryEnterprise> $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Filter\Filter\Domain\Model\CategoryEnterprise> $category
     * @return void
     */
    public function setCategory(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $category)
    {
        $this->category = $category;
    }
}
