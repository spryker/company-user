<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserEntityManagerInterface
{
    public function saveCompanyUser(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer;

    public function deleteCompanyUserById(int $idCompanyUser): void;

    public function updateCompanyUserStatus(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer;
}
