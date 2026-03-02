<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Dependency\Facade;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CompanyUserToCustomerFacadeBridge implements CompanyUserToCustomerFacadeInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade
     */
    public function __construct($customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    public function registerCustomer(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        return $this->customerFacade->registerCustomer($customerTransfer);
    }

    public function anonymizeCustomer(CustomerTransfer $customerTransfer): void
    {
        $this->customerFacade->anonymizeCustomer($customerTransfer);
    }

    public function updateCustomer(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        return $this->customerFacade->updateCustomer($customerTransfer);
    }
}
