<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\Exceptions\ApiProxyException;
use CaliforniaMountainSnake\UtilTraits\Curl\HttpResponse;

/**
 * The interface that allows object to perform requests to API routes.
 */
interface ApiProxyInterface
{
    /**
     * Execute a query to the target api route.
     *
     * @param AvailableRoute $_rote    Route.
     * @param array          $_params  Query parameters [optional].
     * @param array|null     $_headers Query headers [optional].
     *
     * @return HttpResponse
     * @throws ApiProxyException
     */
    public function query(AvailableRoute $_rote, array $_params = [], array $_headers = null): HttpResponse;
}
