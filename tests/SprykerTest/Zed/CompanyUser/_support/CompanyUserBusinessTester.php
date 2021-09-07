<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyUserBusinessTester extends Actor
{
    use _generated\CompanyUserBusinessTesterActions;

    /**
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @param array $seedData
     * @param array $companySeedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUserTransfer(
        array $seedData = [
            CompanyUserTransfer::IS_ACTIVE => true,
        ],
        array $companySeedData = [
            CompanyTransfer::IS_ACTIVE => true,
            CompanyTransfer::STATUS => self::STATUS_APPROVED,
        ]
    ): CompanyUserTransfer {
        if (!isset($seedData[CompanyUserTransfer::CUSTOMER])) {
            $customerTransfer = $this->haveCustomer();
            $seedData[CompanyUserTransfer::CUSTOMER] = $customerTransfer;
        }

        if (!isset($seedData[CompanyUserTransfer::FK_COMPANY])) {
            $companyTransfer = $this->haveCompany($companySeedData);
            $seedData[CompanyUserTransfer::FK_COMPANY] = $companyTransfer->getIdCompany();
        }

        return $this->haveCompanyUser($seedData);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getLocator()->customer()->facade();
    }
}
