<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyUserConfig extends AbstractBundleConfig
{
    public const string SORT_FIELD_COMPANY_NAME = 'companyName';

    public const string SORT_FIELD_CUSTOMER_EMAIL = 'customerEmail';

    public const string SORT_FIELD_CUSTOMER_FIRST_NAME = 'customerFirstName';

    public const string SORT_FIELD_CUSTOMER_LAST_NAME = 'customerLastName';

    /**
     * Specification:
     * - Returns the sortable fields of the company user collection, mapping each publicly accepted
     *   field name to the database column that `CompanyUserCriteriaFilterTransfer.filter.orderBy`
     *   orders by.
     * - Acts as the allow list for sorting: Propel interpolates ORDER BY identifiers straight into
     *   SQL, so a caller-supplied sort field MUST be resolved through this map and never passed
     *   through.
     *
     * @api
     *
     * @module Company
     * @module Customer
     *
     * @return array<string, string>
     */
    public function getCompanyUserCollectionSortableFieldMap(): array
    {
        return [
            static::SORT_FIELD_COMPANY_NAME => SpyCompanyTableMap::COL_NAME,
            static::SORT_FIELD_CUSTOMER_EMAIL => SpyCustomerTableMap::COL_EMAIL,
            static::SORT_FIELD_CUSTOMER_FIRST_NAME => SpyCustomerTableMap::COL_FIRST_NAME,
            static::SORT_FIELD_CUSTOMER_LAST_NAME => SpyCustomerTableMap::COL_LAST_NAME,
        ];
    }
}
