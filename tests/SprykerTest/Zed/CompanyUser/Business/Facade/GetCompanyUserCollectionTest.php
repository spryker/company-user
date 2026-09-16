<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\CompanyUserCriteriaFilterBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use SprykerTest\Zed\CompanyUser\CompanyUserBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUser
 * @group Business
 * @group Facade
 * @group GetCompanyUserCollectionTest
 * Add your own group annotations below this line
 */
class GetCompanyUserCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUser\CompanyUserBusinessTester
     */
    protected CompanyUserBusinessTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureCompanyUserTableIsEmpty();
    }

    public function testShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterBuilder([
            CompanyUserCriteriaFilterTransfer::ID_COMPANY => $companyUserTransfer->getFkCompany(),
        ]))->build();

        // Act
        $companyUserCollectionTransfer = $this->tester
            ->getFacade()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyUserCollectionTransfer->getCompanyUsers());
    }

    public function testKeepsAnOrderByThatNamesAColumnOfTheQueriedTables(): void
    {
        // Arrange
        $this->tester->createCompanyUserTransfer();
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setFilter((new FilterTransfer())->setOrderBy(SpyCompanyUserTableMap::COL_ID_COMPANY_USER));

        // Act
        $companyUserCollectionTransfer = $this->tester
            ->getFacade()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(
            1,
            $companyUserCollectionTransfer->getCompanyUsers(),
            'A caller ordering by a real column of the query keeps working.',
        );
    }

    public function testDropsAnOrderByThatIsNotAColumnOfTheQueriedTables(): void
    {
        // Arrange
        $this->tester->createCompanyUserTransfer();
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setFilter((new FilterTransfer())->setOrderBy('(SELECT 1); DROP TABLE spy_company_user; --'));

        // Act
        $companyUserCollectionTransfer = $this->tester
            ->getFacade()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(
            1,
            $companyUserCollectionTransfer->getCompanyUsers(),
            'An order-by the repository cannot resolve to a column is dropped instead of reaching Propel.',
        );
    }

    public function testIgnoresASortFieldOutsideTheSortableFieldMap(): void
    {
        // Arrange
        $this->tester->createCompanyUserTransfer();
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->addSort((new SortTransfer())->setField('spy_company_user.id_company_user; --'));

        // Act
        $companyUserCollectionTransfer = $this->tester
            ->getFacade()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(
            1,
            $companyUserCollectionTransfer->getCompanyUsers(),
            'A sort field absent from the sortable field map resolves to no column and is skipped.',
        );
    }

    public function testIgnoresAnonymizedCustomersByDefault(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::ANONYMIZED_AT => new DateTime(),
        ]))->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        $customerTransfer = (new CustomerBuilder())->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        // Act
        $companyUserTransfers = $this->tester
            ->getFacade()
            ->getCompanyUserCollection(new CompanyUserCriteriaFilterTransfer())
            ->getCompanyUsers();

        // Assert
        $this->assertCount(1, $companyUserTransfers);
        $this->assertNull($companyUserTransfers->getIterator()->current()->getCustomer()->getAnonymizedAt());
    }

    public function testIncludesAnonymizedCustomersWhenRequested(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::ANONYMIZED_AT => new DateTime(),
        ]))->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        $customerTransfer = (new CustomerBuilder())->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())->setIncludeAnonymizedCustomers(true);

        // Act
        $companyUserCollectionTransfer = $this->tester
            ->getFacade()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(2, $companyUserCollectionTransfer->getCompanyUsers());
    }
}
