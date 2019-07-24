<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy;

use CaliforniaMountainSnake\UtilTraits\Curl\HttpResponse;

interface ApiProxyInterface
{
    /**
     * Выполнить запрос к заданноу роуту API.
     *
     * @param AvailableRoute $_rote Роут.
     * @param array $_params Параметры запроса [опционально].
     *
     * @return HttpResponse
     */
    public function query(AvailableRoute $_rote, array $_params = []): HttpResponse;
}
