<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\Utils;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\Authenticator\TelegramAuthenticator;
use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\AuthTelegrambotUserRepository;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\AuthUserAccountTypeAccessUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\AuthUserRoleAccessUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\Authenticator\Utils\HasAuthenticatorTrait;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\Authenticator\Utils\HasUserTrait;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AuthUserRepository;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\Enums\AuthUserAccountTypeEnum;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\Enums\AuthUserRoleEnum;
use Longman\TelegramBot\Entities\User;

/**
 * Утилиты, обеспечивающие доступ к информаци о текущем пользователе.
 */
trait AuthUserUtils
{
    use AuthUserRoleAccessUtils;
    use AuthUserAccountTypeAccessUtils;
    use HasAuthenticatorTrait;
    use HasUserTrait;

    /**
     * @var AuthUserRoleEnum
     */
    private $userRole;

    /**
     * @var AuthUserAccountTypeEnum
     */
    private $userAccountType;


    /**
     * @return User
     */
    abstract protected function getTelegramUser(): User;

    /**
     * @return AuthUserRepository
     */
    abstract protected function getUserRepository(): AuthUserRepository;

    /**
     * @return AuthTelegrambotUserRepository
     */
    abstract protected function getTelegrambotUserRepository(): AuthTelegrambotUserRepository;

    /**
     * @return string
     */
    abstract protected function getUserRoleEnumClass(): string;

    /**
     * @return string
     */
    abstract protected function getUserAccountTypeEnumClass(): string;


    /**
     * Init user params.
     */
    protected function reInitUserParams(): void
    {
        $userRoleClass = $this->getUserRoleEnumClass();
        $userAccountTypeClass = $this->getUserAccountTypeEnumClass();

        $this->authenticator = new TelegramAuthenticator($this->getTelegramUser(), $this->getUserRepository(),
            $this->getTelegrambotUserRepository());

        // We just create a UserEntity, don't perform full authentication.
        /** @see AuthBotAccessUtils::assertUserHasAccessToMainRoute */
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->userEntity = $this->authenticator->authenticateUser();

        $this->userRole = new $userRoleClass($this->getRoleOfUserString($this->getUserEntity()));
        $this->userAccountType = new $userAccountTypeClass ($this->getAccountTypeOfUserString($this->getUserEntity()));
    }

    /**
     * @return AuthUserRoleEnum
     */
    protected function getUserRole(): AuthUserRoleEnum
    {
        return $this->userRole;
    }

    /**
     * @return AuthUserAccountTypeEnum
     */
    protected function getUserAccountType(): AuthUserAccountTypeEnum
    {
        return $this->userAccountType;
    }
}
