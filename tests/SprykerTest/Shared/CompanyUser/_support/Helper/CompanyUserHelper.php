<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\CompanyUser\Helper;

use Codeception\Module;
use Exception;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeBridge;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelperTrait;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class CompanyUserHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;
    use BusinessHelperTrait;
    use CustomerDataHelperTrait;

    /**
     * @param array $seed
     * @param array<\Generated\Shared\Transfer\CompanyRoleTransfer> $companyRoles
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUser(array $seed = [], array $companyRoles = []): CompanyUserTransfer
    {
        $companyUserTransfer = (new CompanyUserBuilder($seed))->build();
        $companyUserTransfer->setIdCompanyUser(null);

        $companyUserTransfer->requireCustomer();

        $companyUserResponseTransfer = $this->createCompanyUserFacade()->create($companyUserTransfer);

        $this->assertCompanyUserCreated($companyUserResponseTransfer);

        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();

        if ($companyRoles) {
            $this->assignCompanyRolesToCompanyUser($companyUserTransfer, $companyRoles);
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyUserTransfer): void {
            $this->debug(sprintf('Deleting Company User Id: %s', $companyUserTransfer->getIdCompanyUser()));
            $this->createCompanyUserFacade()->deleteCompanyUser($companyUserTransfer);
        });

        return $companyUserTransfer;
    }

    /**
     * A failed create still returns a company user, only without an ID - which every caller then
     * feeds into role assignment and order fixtures, where it surfaces as a NOT NULL violation
     * several calls later. Fail here, with the validation messages.
     *
     * @throws \Exception
     */
    protected function assertCompanyUserCreated(CompanyUserResponseTransfer $companyUserResponseTransfer): void
    {
        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();

        if ($companyUserResponseTransfer->getIsSuccessful() && $companyUserTransfer !== null && $companyUserTransfer->getIdCompanyUser() !== null) {
            return;
        }

        $messages = [];

        foreach ($companyUserResponseTransfer->getMessages() as $responseMessageTransfer) {
            $messages[] = (string)$responseMessageTransfer->getText();
        }

        throw new Exception(sprintf(
            'Failed to create a company user. Response messages: %s',
            $messages ? implode('; ', $messages) : '(none)',
        ));
    }

    public function assignCompanyRolesToCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $companyUserTransfer->requireCompanyRoleCollection();

        $this->getCompanyRoleFacade()->saveCompanyUser($companyUserTransfer);
    }

    protected function getCompanyRoleFacade(): CompanyRoleFacadeInterface
    {
        return $this->getLocator()->companyRole()->facade();
    }

    public function createCompanyUserFacade(): CompanyUserFacadeInterface
    {
        if ($this->getLocatorHelper()->isProjectNamespaceEnabled()) {
            return $this->getLocator()->companyUser()->facade();
        }

        $this->getBusinessHelper()->mockFactoryMethod(
            'getCustomerFacade',
            new CompanyUserToCustomerFacadeBridge($this->getCustomerDataHelper()->getCustomerFacade()),
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPreSavePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPostSavePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPostCreatePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserHydrationPlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPreDeletePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserSavePreCheckPlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPostUpdatePlugins',
            [],
            'CompanyUser',
        );

        /** @var \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade */
        $companyUserFacade = $this->getBusinessHelper()->getFacade('CompanyUser');
        $this->getLocatorHelper()->addToLocatorCache('companyUser-facade', $companyUserFacade);

        return $companyUserFacade;
    }
}
