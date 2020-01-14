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
     * @param array|null     $_headers
     *
     * @return HttpResponse
     * @throws ApiProxyException
     */
    protected function callApiNotAuth(AvailableRoute $_rote, array $_params = [], array $_headers = null): HttpResponse
    {
        return $this->getApi()->query($_rote, $_params, $_headers);
    }

    /**
     * @param AvailableRoute $_rote
     * @param array          $_params
     * @param array|null     $_headers
     *
     * @return HttpResponse
     * @throws ApiProxyException
     */
    protected function callApiAuth(AvailableRoute $_rote, array $_params = [], array $_headers = null): HttpResponse
    {
        $token = ($this->getUserEntity() === null ? null : $this->getUserEntity()->getApiToken());

        $_params[AuthMiddleware::API_TOKEN_REQUEST_PARAM] = $token;
        return $this->getApi()->query($_rote, $_params, $_headers);
    }
}
