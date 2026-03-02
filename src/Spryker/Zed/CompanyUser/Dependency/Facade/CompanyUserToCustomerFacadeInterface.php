<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Dependency\Facade;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserToCustomerFacadeInterface
{
    public function registerCustomer(CustomerTransfer $customerTransfer): CustomerResponseTransfer;

    public function anonymizeCustomer(CustomerTransfer $customerTransfer): void;

    public function updateCustomer(CustomerTransfer $customerTransfer): CustomerResponseTransfer;
}
