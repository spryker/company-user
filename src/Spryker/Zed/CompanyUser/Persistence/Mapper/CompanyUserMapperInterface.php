<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SpyCompanyUserEntityTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;

interface CompanyUserMapperInterface
{
    public function mapCompanyUserTransferToEntityTransfer(
        CompanyUserTransfer $companyUserTransfer
    ): SpyCompanyUserEntityTransfer;

    public function mapEntityTransferToCompanyUserTransfer(
        SpyCompanyUserEntityTransfer $companyUserEntityTransfer
    ): CompanyUserTransfer;

    /**
     * @param array<\Generated\Shared\Transfer\SpyCompanyUserEntityTransfer> $collection
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollection($collection): CompanyUserCollectionTransfer;

    public function mapCompanyUserEntityToCompanyUserTransfer(
        SpyCompanyUser $companyUserEntity
    ): CompanyUserTransfer;
}
