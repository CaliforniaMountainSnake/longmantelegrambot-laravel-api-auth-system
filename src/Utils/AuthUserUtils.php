<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\Utils;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\AuthTelegrambotUserEntity;
use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\AuthTelegrambotUserRepository;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\AuthUserAccountTypeAccessUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\AuthUserRoleAccessUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AuthUserEntity;
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

    /**
     * @var AuthUserEntity|null
     */
    private $user;

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

        $this->user = $this->createUserEntity($this->createTelegrambotUserEntity());
        $this->userRole = new $userRoleClass($this->getRoleOfUserString($this->getUserEntity()));
        $this->userAccountType = new $userAccountTypeClass ($this->getAccountTypeOfUserString($this->getUserEntity()));
    }

    /**
     * @return AuthUserEntity|null
     */
    protected function getUserEntity(): ?AuthUserEntity
    {
        return $this->user;
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

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return AuthTelegrambotUserEntity|null
     */
    private function createTelegrambotUserEntity(): ?AuthTelegrambotUserEntity
    {
        return $this->getTelegrambotUserRepository()->getByTelegramId($this->getTelegramUser()->getId());
    }

    /**
     * @param AuthTelegrambotUserEntity|null $_user
     *
     * @return AuthUserEntity|null
     */
    private function createUserEntity(?AuthTelegrambotUserEntity $_user): ?AuthUserEntity
    {
        if ($_user === null) {
            return null;
        }

        return $this->getUserRepository()->getByApiToken($_user->getApiToken());
    }
}
