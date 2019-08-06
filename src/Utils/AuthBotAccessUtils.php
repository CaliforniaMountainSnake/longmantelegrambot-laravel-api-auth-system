<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\Utils;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\AvailableRoute;
use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\Utils\Exceptions\GroupChatNotAvailableException;
use CaliforniaMountainSnake\LongmanTelegrambotUtils\Enums\TelegramChatTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotUtils\TelegramUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\ArrayUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\AuthUserAccountTypeAccessUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\AuthUserRoleAccessUtils;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\Exceptions\UserAccountTypeNotEqualsException;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AccessUtils\Exceptions\UserRoleNotEqualsException;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AuthRoleService;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AuthUserEntity;

/**
 * Утилиты для проверки доступа юзера к какой-либо команде бота.
 */
trait AuthBotAccessUtils
{
    use AuthUserRoleAccessUtils;
    use AuthUserAccountTypeAccessUtils;
    use ArrayUtils;
    use TelegramUtils;


    /**
     * @return AuthRoleService
     */
    abstract protected function getRoleService(): AuthRoleService;

    /**
     * Get command name.
     * @return string
     */
    abstract public static function getCommandName(): string;

    /**
     * Get command description.
     * @return string
     */
    abstract public static function getCommandDescription(): string;

    /**
     * @return AvailableRoute|null
     */
    abstract protected function getMainRoute(): ?AvailableRoute;

    /**
     * @return bool
     */
    abstract protected function isGroupChatAvailable(): bool;

    /**
     * Не нарушена ли политика запуска команды в групповых чатах?
     * @return bool
     */
    public function isPrivacyCorrect(): bool
    {
        return !(!$this->isGroupChatAvailable() && (string)$this->getChatType() !== TelegramChatTypeEnum::PRIVATE_CHAT);
    }

    /**
     * Проверить у юзера наличие прав доступа к основному роуту команды и выбросить исключение, если доступ запрещен.
     *
     * @param AuthUserEntity|null $_user
     * @throws GroupChatNotAvailableException
     * @throws UserAccountTypeNotEqualsException
     * @throws UserRoleNotEqualsException
     * @throws \LogicException
     */
    public function assertUserHasAccessToMainRoute(?AuthUserEntity $_user): void
    {
        // В первую очередь проверим, можно ли запускать команду в данном типе чата.
        if (!$this->isPrivacyCorrect()) {
            throw new GroupChatNotAvailableException('This command can not executes in group chats or channels!');
        }

        // Если главный роут не задан, команда по-дефолту доступна для всех ролей и всех типов аккаунтов.
        if ($this->getMainRoute() === null) {
            return;
        }

        $this->assertUserHasAccessToRoute($_user, $this->getMainRoute());
    }

    /**
     * Проверить у юзера наличие прав доступа к заданному роуту и выбросить исключение, если доступ запрещен.
     *
     * @param AuthUserEntity|null $_user
     * @param AvailableRoute $_route
     *
     * @throws UserAccountTypeNotEqualsException
     * @throws UserRoleNotEqualsException
     * @throws \LogicException
     */
    public function assertUserHasAccessToRoute(?AuthUserEntity $_user, AvailableRoute $_route): void
    {
        // Имеет ли роль данного юзера доступ к роуту.
        $this->assertUserRoleHasAccessToRoute($_user, $_route);

        // Имеет ли тип аккаунта данного юзера доступ к роуту.
        $this->assertUserAccountTypeHasAccessToRoute($_user, $_route);
    }

    /**
     * Проверить у роли наличие прав доступа к заданному роуту и выбросить исключение, если доступ запрещен.
     *
     * @param AuthUserEntity|null $_user
     * @param AvailableRoute $_route
     * @throws UserRoleNotEqualsException
     * @throws \LogicException
     */
    public function assertUserRoleHasAccessToRoute(?AuthUserEntity $_user, AvailableRoute $_route): void
    {
        if (!$this->hasUserRoleAccessToRoute($_user, $_route)) {
            throw new UserRoleNotEqualsException('A role "' . $this->getRoleOfUserString($_user)
                . '" does not have access to the route "' . $this->getMainRoute() . '".');
        }
    }

    /**
     * Проверить у типа аккаунта юзера наличие прав доступа к заданному роуту
     * и выбросить исключение, если доступ запрещен.
     *
     * @param AuthUserEntity|null $_user
     * @param AvailableRoute $_route
     * @throws UserAccountTypeNotEqualsException
     * @throws \LogicException
     */
    public function assertUserAccountTypeHasAccessToRoute(?AuthUserEntity $_user, AvailableRoute $_route): void
    {
        if (!$this->hasUserAccountTypeAccessToRoute($_user, $_route)) {
            throw new UserAccountTypeNotEqualsException('An account with type "'
                . $this->getAccountTypeOfUserString($_user) . '" does not have access to the route "'
                . $this->getMainRoute() . '".');
        }
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Может ли роль юзера запускать данный роут?
     *
     * @param AuthUserEntity|null $_user
     * @param AvailableRoute $_route
     * @return bool
     * @throws \LogicException
     */
    public function hasUserRoleAccessToRoute(?AuthUserEntity $_user, AvailableRoute $_route): bool
    {
        $routeRoles = $this->getRoleService()->getRolesByRoute($_route->getRoute());
        return $this->isUserRoleEquals($_user, ...$routeRoles);
    }

    /**
     * Может ли тип аккаунта юзера запускать данный роут?
     *
     * @param AuthUserEntity|null $_user
     * @param AvailableRoute $_route
     * @return bool
     * @throws \LogicException
     */
    public function hasUserAccountTypeAccessToRoute(?AuthUserEntity $_user, AvailableRoute $_route): bool
    {
        $routeAccountTypes = $this->getRoleService()->getAccountTypesByRoute($_route->getRoute());
        return $this->isUserAccountTypeEquals($_user, ...$routeAccountTypes);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Имеет ли данный юзер право доступа к основному роуту команды?
     *
     * @param AuthUserEntity|null $_user
     * @return bool
     * @throws \LogicException
     */
    public function hasUserAccessToMainRoute(?AuthUserEntity $_user): bool
    {
        try {
            $this->assertUserHasAccessToMainRoute($_user);
        } catch (GroupChatNotAvailableException|UserRoleNotEqualsException|UserAccountTypeNotEqualsException $e) {
            return false;
        }

        return true;
    }

    /**
     * Имеет ли данный юзер право доступа к заданному роуту?
     *
     * @param AuthUserEntity|null $_user
     * @param AvailableRoute $_route
     * @return bool
     * @throws \LogicException
     */
    public function hasUserAccessToRoute(?AuthUserEntity $_user, AvailableRoute $_route): bool
    {
        try {
            $this->assertUserHasAccessToRoute($_user, $_route);
        } catch (UserRoleNotEqualsException|UserAccountTypeNotEqualsException $e) {
            return false;
        }

        return true;
    }
}
