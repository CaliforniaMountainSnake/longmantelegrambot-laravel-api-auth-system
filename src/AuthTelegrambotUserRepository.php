<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem;

use CaliforniaMountainSnake\DatabaseEntities\BaseRepository;

abstract class AuthTelegrambotUserRepository extends BaseRepository
{
    /**
     * @param string $_telegram_id
     *
     * @return AuthTelegrambotUserEntity|null
     */
    abstract public function getByTelegramId(string $_telegram_id): ?AuthTelegrambotUserEntity;
}
