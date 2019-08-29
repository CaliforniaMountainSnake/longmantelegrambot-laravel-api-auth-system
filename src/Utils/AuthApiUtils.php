<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\Utils;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\ApiProxyInterface;
use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\AvailableRoute;
use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\Exceptions\ApiProxyException;
use CaliforniaMountainSnake\SimpleLaravelAuthSystem\Middleware\AuthMiddleware;
use CaliforniaMountainSnake\UtilTraits\Curl\HttpResponse;

/**
 * Утилиты для выполнения запросов к роутам API и получения ответов.
 */
trait AuthApiUtils
{
    use AuthUserUtils;


    /**
     * @return ApiProxyInterface
     */
    abstract protected function getApi(): ApiProxyInterface;


    /**
     * @param AvailableRoute $_rote
     * @param array          $_params
     *
     * @return HttpResponse
     * @throws ApiProxyException
     */
    protected function callApiNotAuth(AvailableRoute $_rote, array $_params = []): HttpResponse
    {
        return $this->getApi()->query($_rote, $_params);
    }

    /**
     * @param AvailableRoute $_rote
     * @param array          $_params
     *
     * @return HttpResponse
     * @throws ApiProxyException
     */
    protected function callApiAuth(AvailableRoute $_rote, array $_params = []): HttpResponse
    {
        $token = ($this->getUserEntity() === null ? null : $this->getUserEntity()->getApiToken());

        $_params[AuthMiddleware::API_TOKEN_REQUEST_PARAM] = $token;
        return $this->getApi()->query($_rote, $_params);
    }
}
