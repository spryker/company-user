<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Expander;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    protected $repository;

    public function __construct(CompanyUserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function expandCustomerWithIsActiveCompanyUserExists(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $idCustomer = $customerTransfer->getIdCustomerOrFail();

        if ($this->repository->findCompanyUserByCustomerId($idCustomer) === null) {
            return $customerTransfer;
        }

        return $customerTransfer->setIsActiveCompanyUserExists(
            $this->repository->isActiveCompanyUser($idCustomer),
        );
    }
}
