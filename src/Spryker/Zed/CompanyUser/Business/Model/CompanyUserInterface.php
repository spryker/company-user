<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserInterface
{
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    public function save(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    public function delete(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    public function findCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer;

    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;

    public function findActiveCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer;

    public function getActiveCompanyUsersByCustomerReference(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer;

    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer;

    public function createInitialCompanyUser(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer;

    public function deleteCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    public function findCompanyUserById(int $idCompanyUser): ?CompanyUserTransfer;

    /**
     * @param array<int> $companyUserIds
     *
     * @return array<\Generated\Shared\Transfer\CompanyUserTransfer>
     */
    public function findActiveCompanyUsers(array $companyUserIds): array;

    /**
     * @param array<int> $companyIds
     *
     * @return array<int>
     */
    public function findActiveCompanyUserIdsByCompanyIds(array $companyIds): array;

    public function findActiveCompanyUserByUuid(CompanyUserTransfer $companyUserTransfer): ?CompanyUserTransfer;

    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer;
}
