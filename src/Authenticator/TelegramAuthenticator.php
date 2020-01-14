<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\Authenticator;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\AuthTelegrambotUserEntity;
use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\AuthTelegrambotUserRepository;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\Authenticator\Interfaces\AuthenticatorInterface;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\Authenticator\Utils\HasUserTrait;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AuthUserEntity;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\AuthUserRepository;
use Longman\TelegramBot\Entities\User;

class TelegramAuthenticator implements AuthenticatorInterface
{
    use HasUserTrait;

    /**
     * @var User
     */
    protected $telegramUser;

    /**
     * @var AuthUserRepository
     */
    protected $userRepository;

    /**
     * @var AuthTelegrambotUserRepository
     */
    protected $telegrambotUserRepository;


    /**
     * TelegramAuthenticator constructor.
     *
     * @param User                          $telegramUser
     * @param AuthUserRepository            $userRepository
     * @param AuthTelegrambotUserRepository $telegrambotUserRepository
     */
    public function __construct(
        User $telegramUser,
        AuthUserRepository $userRepository,
        AuthTelegrambotUserRepository $telegrambotUserRepository
    ) {
        $this->telegramUser = $telegramUser;
        $this->userRepository = $userRepository;
        $this->telegrambotUserRepository = $telegrambotUserRepository;
    }

    /**
     * Create an authenticated user from telegram id.
     *
     * @return AuthUserEntity|null The instance of authenticated user or null if not-auth users are allowed.
     */
    public function authenticateUser(): ?AuthUserEntity
    {
        $telegrambotUser = $this->createTelegrambotUserEntity();
        if ($telegrambotUser === null) {
            return null;
        }

        $this->userEntity = $this->userRepository->getByApiToken($telegrambotUser->getApiToken());
        return $this->userEntity;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return AuthTelegrambotUserEntity|null
     */
    private function createTelegrambotUserEntity(): ?AuthTelegrambotUserEntity
    {
        return $this->telegrambotUserRepository->getByTelegramId($this->telegramUser->getId());
    }
}
