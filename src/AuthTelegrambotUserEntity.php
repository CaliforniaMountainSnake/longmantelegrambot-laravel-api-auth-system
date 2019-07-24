<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem;

use CaliforniaMountainSnake\DatabaseEntities\BaseEntity;

abstract class AuthTelegrambotUserEntity extends BaseEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $telegram_id;

    /**
     * @var string
     */
    protected $api_token;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTelegramId(): string
    {
        return $this->telegram_id;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->api_token;
    }
}
