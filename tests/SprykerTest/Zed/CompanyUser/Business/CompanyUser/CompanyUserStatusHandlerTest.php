<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Business\CompanyUser;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUser\CompanyUserStatusHandler;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUser
 * @group Business
 * @group CompanyUser
 * @group CompanyUserStatusHandlerTest
 * Add your own group annotations below this line
 */
class CompanyUserStatusHandlerTest extends Unit
{
    protected const int ID_COMPANY_USER = 7;

    public function testDisableExecutesPostUpdatePluginsOnStatusFlip(): void
    {
        // Arrange
        $companyUserPluginExecutorMock = $this->createCompanyUserPluginExecutorMock();
        $companyUserPluginExecutorMock->expects($this->once())->method('executePostUpdatePlugins')->willReturnArgument(0);
        $companyUserStatusHandler = $this->createCompanyUserStatusHandler($companyUserPluginExecutorMock, true);

        // Act
        $companyUserResponseTransfer = $companyUserStatusHandler->disableCompanyUser($this->createCompanyUserTransfer());

        // Assert
        $this->assertTrue($companyUserResponseTransfer->getIsSuccessful());
    }

    public function testDisableDoesNotExecutePostUpdatePluginsWhenAlreadyDisabled(): void
    {
        // Arrange
        $companyUserPluginExecutorMock = $this->createCompanyUserPluginExecutorMock();
        $companyUserPluginExecutorMock->expects($this->never())->method('executePostUpdatePlugins');
        $companyUserStatusHandler = $this->createCompanyUserStatusHandler($companyUserPluginExecutorMock, false);

        // Act
        $companyUserResponseTransfer = $companyUserStatusHandler->disableCompanyUser($this->createCompanyUserTransfer());

        // Assert
        $this->assertFalse($companyUserResponseTransfer->getIsSuccessful());
    }

    public function testEnableExecutesPostUpdatePluginsOnStatusFlip(): void
    {
        // Arrange
        $companyUserPluginExecutorMock = $this->createCompanyUserPluginExecutorMock();
        $companyUserPluginExecutorMock->expects($this->once())->method('executePostUpdatePlugins')->willReturnArgument(0);
        $companyUserStatusHandler = $this->createCompanyUserStatusHandler($companyUserPluginExecutorMock, false);

        // Act
        $companyUserResponseTransfer = $companyUserStatusHandler->enableCompanyUser($this->createCompanyUserTransfer());

        // Assert
        $this->assertTrue($companyUserResponseTransfer->getIsSuccessful());
    }

    public function testEnableDoesNotExecutePostUpdatePluginsWhenAlreadyEnabled(): void
    {
        // Arrange
        $companyUserPluginExecutorMock = $this->createCompanyUserPluginExecutorMock();
        $companyUserPluginExecutorMock->expects($this->never())->method('executePostUpdatePlugins');
        $companyUserStatusHandler = $this->createCompanyUserStatusHandler($companyUserPluginExecutorMock, true);

        // Act
        $companyUserResponseTransfer = $companyUserStatusHandler->enableCompanyUser($this->createCompanyUserTransfer());

        // Assert
        $this->assertFalse($companyUserResponseTransfer->getIsSuccessful());
    }

    /**
     * @param \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface|\PHPUnit\Framework\MockObject\MockObject $companyUserPluginExecutorMock
     */
    protected function createCompanyUserStatusHandler(
        CompanyUserPluginExecutorInterface $companyUserPluginExecutorMock,
        bool $isCompanyUserActive
    ): CompanyUserStatusHandler {
        $companyUserRepositoryMock = $this->createMock(CompanyUserRepositoryInterface::class);
        $companyUserRepositoryMock->method('findCompanyUserByIdCompanyUser')
            ->willReturn($this->createCompanyUserTransfer()->setIsActive($isCompanyUserActive));

        return new CompanyUserStatusHandler(
            $companyUserRepositoryMock,
            $this->createMock(CompanyUserEntityManagerInterface::class),
            $companyUserPluginExecutorMock,
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCompanyUserPluginExecutorMock(): CompanyUserPluginExecutorInterface
    {
        return $this->createMock(CompanyUserPluginExecutorInterface::class);
    }

    protected function createCompanyUserTransfer(): CompanyUserTransfer
    {
        return (new CompanyUserTransfer())->setIdCompanyUser(static::ID_COMPANY_USER);
    }
}
