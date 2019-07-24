<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem;

abstract class AuthTelegrambotUserRepository
{
    /**
     * @param string $_telegram_id
     * @return AuthTelegrambotUserEntity|null
     */
    abstract public function getByTelegramId(string $_telegram_id): ?AuthTelegrambotUserEntity;
}
