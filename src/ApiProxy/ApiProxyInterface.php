<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\Exceptions\ApiProxyException;
use CaliforniaMountainSnake\UtilTraits\Curl\HttpResponse;

interface ApiProxyInterface
{
    /**
     * Execute query to the target api route.
     *
     * @param AvailableRoute $_rote   Route.
     * @param array          $_params Query parameters [optional].
     *
     * @return HttpResponse
     * @throws ApiProxyException
     */
    public function query(AvailableRoute $_rote, array $_params = []): HttpResponse;
}
