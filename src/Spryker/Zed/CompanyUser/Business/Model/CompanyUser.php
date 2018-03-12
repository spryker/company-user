<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CompanyUser implements CompanyUserInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    protected $companyUserRepository;

    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface
     */
    protected $companyUserEntityManager;

    /**
     * @var \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface
     */
    protected $companyUserPluginExecutor;

    /**
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface $companyUserRepository
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface $companyUserEntityManager
     * @param \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface $companyUserPluginExecutor
     */
    public function __construct(
        CompanyUserRepositoryInterface $companyUserRepository,
        CompanyUserEntityManagerInterface $companyUserEntityManager,
        CompanyUserToCustomerFacadeInterface $customerFacade,
        CompanyUserPluginExecutorInterface $companyUserPluginExecutor
    ) {
        $this->companyUserRepository = $companyUserRepository;
        $this->companyUserEntityManager = $companyUserEntityManager;
        $this->customerFacade = $customerFacade;
        $this->companyUserPluginExecutor = $companyUserPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(true);

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserResponseTransfer) {
            return $this->executeCreateTransaction($companyUserResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function save(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(true);

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserResponseTransfer) {
            $companyUserResponseTransfer = $this->executeSaveTransaction($companyUserResponseTransfer);

            return $companyUserResponseTransfer;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function delete(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserTransfer) {
            $companyUserTransfer = $this->companyUserRepository->getCompanyUserById(
                $companyUserTransfer->getIdCompanyUser()
            );
            $this->companyUserEntityManager->deleteCompanyUserById($companyUserTransfer->getIdCompanyUser());
            $this->customerFacade->anonymizeCustomer($companyUserTransfer->getCustomer());

            return (new CompanyUserResponseTransfer())->setIsSuccessful(true);
        });
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserRepository->findCompanyUserByCustomerId($idCustomer);

        if ($companyUserTransfer !== null) {
            return $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return null;
    }

    /**
     * @param int $idCustomer
     *
     * @return CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserRepository->findActiveCompanyUserByCustomerId($idCustomer);

        if ($companyUserTransfer !== null) {
            return $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        $collectionTransfer = $this->companyUserRepository->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        foreach ($collectionTransfer->getCompanyUsers() as &$companyUserTransfer) {
            $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return $collectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function executeCreateTransaction(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = $this->registerCustomer($companyUserResponseTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $companyUserResponseTransfer;
        }

        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $companyUserTransfer = $this->companyUserEntityManager->saveCompanyUser($companyUserTransfer);
        $companyUserTransfer = $this->companyUserPluginExecutor->executePostSavePlugins($companyUserTransfer);
        $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function executeSaveTransaction(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer->requireCompanyUser();
        $companyUserResponseTransfer->getCompanyUser()->requireCustomer();

        $companyUserResponseTransfer = $this->updateCustomer($companyUserResponseTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $companyUserResponseTransfer;
        }

        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $companyUserTransfer = $this->companyUserEntityManager->saveCompanyUser($companyUserTransfer);
        $companyUserTransfer = $this->companyUserPluginExecutor->executePostSavePlugins($companyUserTransfer);
        $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return CompanyUserResponseTransfer
     */
    protected function updateCustomer(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $customerResponseTransfer = $this->customerFacade->updateCustomer($companyUserTransfer->getCustomer());

        if ($customerResponseTransfer->getIsSuccess()) {
            $companyUserTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());

            return $companyUserResponseTransfer;
        }

        $companyUserResponseTransfer->setIsSuccessful(false);
        $companyUserResponseTransfer = $this->addErrorsToResponse(
            $companyUserResponseTransfer,
            $customerResponseTransfer->getErrors()
        );

        return $companyUserResponseTransfer;
    }

    /**
     * @param CompanyUserResponseTransfer $companyUserResponseTransfer
     * @param CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return CompanyUserResponseTransfer
     */
    protected function registerCustomer(CompanyUserResponseTransfer $companyUserResponseTransfer)
    {
        $companyUserResponseTransfer->requireCompanyUser();
        $companyUserResponseTransfer->getCompanyUser()->requireCustomer();

        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $customerTransfer = $companyUserTransfer->getCustomer();

        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);

        if ($customerResponseTransfer->getIsSuccess()) {
            $companyUserTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());
            $companyUserTransfer->setFkCustomer(
                $customerResponseTransfer->getCustomerTransfer()
                    ->getIdCustomer()
            );

            return $companyUserResponseTransfer;
        }

        $companyUserResponseTransfer->setIsSuccessful(false);

        $companyUserResponseTransfer = $this->addErrorsToResponse(
            $companyUserResponseTransfer,
            $customerResponseTransfer->getErrors()
        );

        return $companyUserResponseTransfer;
    }

    /**
     * @param CompanyUserResponseTransfer $companyUserResponseTransfer
     * @param \ArrayObject|CustomerErrorTransfer[] $errors
     *
     * @return CompanyUserResponseTransfer
     */
    protected function addErrorsToResponse(CompanyUserResponseTransfer $companyUserResponseTransfer, \ArrayObject $errors): CompanyUserResponseTransfer
    {
        foreach ($errors as $error) {
            $companyUserResponseTransfer->addMessage(
                (new ResponseMessageTransfer())->setText(    $error->getMessage())
            );
        }

        return $companyUserResponseTransfer;
    }
}
