<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserRepositoryInterface
{
    public function findCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer;

    public function findActiveCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer;

    public function getCompanyUserCollection(CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): CompanyUserCollectionTransfer;

    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;

    public function findInitialCompanyUserByCompanyId(int $idCompany): ?CompanyUserTransfer;

    public function countActiveCompanyUsersByIdCustomer(int $idCustomer): int;

    /**
     * @param array<int> $companyUserIds
     *
     * @return array<string>
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array;

    public function findCompanyUserByIdCompanyUser(CompanyUserTransfer $companyUserTransfer): ?CompanyUserTransfer;

    public function getActiveCompanyUsersByCustomerReference(
        string $customerReference
    ): CompanyUserCollectionTransfer;

    public function findCompanyUserById(int $idCompanyUser): ?CompanyUserTransfer;

    /**
     * @param array<int> $companyUserIds
     *
     * @return array<\Generated\Shared\Transfer\CompanyUserTransfer>
     */
    public function findActiveCompanyUsersByIds(array $companyUserIds): array;

    /**
     * @param array<int> $companyIds
     *
     * @return array<int>
     */
    public function findActiveCompanyUserIdsByCompanyIds(array $companyIds): array;

    public function findActiveCompanyUserByUuid(string $uuidCompanyUser): ?CompanyUserTransfer;

    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer;

    public function isActiveCompanyUser(int $idCustomer): bool;
}
