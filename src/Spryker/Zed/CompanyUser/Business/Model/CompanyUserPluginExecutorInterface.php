<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserPluginExecutorInterface
{
    public function executePreSavePlugins(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer;

    public function executePostSavePlugins(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer;

    public function executePostUpdatePlugins(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer;

    public function executePostCreatePlugins(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer;

    public function executeHydrationPlugins(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer;

    public function executePreDeletePlugins(CompanyUserTransfer $companyUserTransfer): void;
}
