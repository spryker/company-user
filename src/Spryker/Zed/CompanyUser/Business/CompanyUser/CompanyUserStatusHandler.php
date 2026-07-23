<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;

class CompanyUserStatusHandler implements CompanyUserStatusHandlerInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    protected $companyUserRepository;

    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface
     */
    protected $companyUserEntityManager;

    protected CompanyUserPluginExecutorInterface $companyUserPluginExecutor;

    public function __construct(
        CompanyUserRepositoryInterface $companyUserRepository,
        CompanyUserEntityManagerInterface $companyUserEntityManager,
        CompanyUserPluginExecutorInterface $companyUserPluginExecutor
    ) {
        $this->companyUserRepository = $companyUserRepository;
        $this->companyUserEntityManager = $companyUserEntityManager;
        $this->companyUserPluginExecutor = $companyUserPluginExecutor;
    }

    public function enableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer->requireIdCompanyUser();

        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(false);

        $existingCompanyUser = $this->companyUserRepository->findCompanyUserByIdCompanyUser($companyUserTransfer);

        if (!$existingCompanyUser || $existingCompanyUser->getIsActive()) {
            return $companyUserResponseTransfer;
        }

        $existingCompanyUser->setIsActive(true);
        $this->companyUserEntityManager->updateCompanyUserStatus($existingCompanyUser);

        $companyUserResponseTransfer = $companyUserResponseTransfer
            ->setCompanyUser($existingCompanyUser)
            ->setIsSuccessful(true);

        return $this->companyUserPluginExecutor->executePostUpdatePlugins($companyUserResponseTransfer);
    }

    public function disableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer->requireIdCompanyUser();

        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(false);

        $existingCompanyUser = $this->companyUserRepository->findCompanyUserByIdCompanyUser($companyUserTransfer);

        if (!$existingCompanyUser || !$existingCompanyUser->getIsActive()) {
            return $companyUserResponseTransfer;
        }

        $existingCompanyUser->setIsActive(false);
        $this->companyUserEntityManager->updateCompanyUserStatus($existingCompanyUser);

        $companyUserResponseTransfer = $companyUserResponseTransfer
            ->setCompanyUser($existingCompanyUser)
            ->setIsSuccessful(true);

        return $this->companyUserPluginExecutor->executePostUpdatePlugins($companyUserResponseTransfer);
    }
}
