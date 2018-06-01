<?php

namespace SprykerTest\Zed\CompanyUser\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUser
 * @group Business
 * @group Facade
 * @group CompanyUserFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUserFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUser\CompanyUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCountOfActiveCompanyUsersByCustomerId(): void
    {
        //Arrange
        $expectedCompanyUserAmount = 1;
        $customer = $this->tester->haveCustomer();
        $activeCompany = $this->tester->haveCompany(['isActive' => true]);
        $inactiveCompany = $this->tester->haveCompany(['isActive' => false]);

        $seedDataWithActiveCompany = [
            'customer' => $customer,
            'fkCompany' => $activeCompany->getIdCompany(),
        ];
        $seedDataWithInactiveCompany = [
            'customer' => $customer,
            'fkCompany' => $inactiveCompany->getIdCompany(),
        ];
        $this->tester->haveCompanyUser($seedDataWithActiveCompany);
        $this->tester->haveCompanyUser($seedDataWithInactiveCompany);

        //Act
        $actualCompanyUserAmount = $this->tester->getFacade()->getCountOfActiveCompanyUsersByCustomerId($customer);

        //Assert
        $this->tester->assertEquals($expectedCompanyUserAmount, $actualCompanyUserAmount);
    }
}
