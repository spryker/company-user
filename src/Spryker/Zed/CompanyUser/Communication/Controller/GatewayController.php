<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    public function createAction(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFacade()->create($companyUserTransfer);
    }

    public function updateAction(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFacade()->update($companyUserTransfer);
    }

    public function deleteAction(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFacade()->delete($companyUserTransfer);
    }

    public function getCompanyUserCollectionAction(
        CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        return $this->getFacade()->getCompanyUserCollection($criteriaFilterTransfer);
    }

    public function getCompanyUserByIdAction(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $this->getFacade()->getCompanyUserById($companyUserTransfer->getIdCompanyUser());
    }

    public function getActiveCompanyUsersByCustomerReferenceAction(
        CustomerTransfer $customerTransfer
    ): CompanyUserCollectionTransfer {
        return $this->getFacade()->getActiveCompanyUsersByCustomerReference($customerTransfer);
    }

    public function enableCompanyUserAction(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFacade()->enableCompanyUser($companyUserTransfer);
    }

    public function disableCompanyUserAction(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFacade()->disableCompanyUser($companyUserTransfer);
    }
}
