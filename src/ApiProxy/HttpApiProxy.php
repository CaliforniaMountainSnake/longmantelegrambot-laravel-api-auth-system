<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy;

use CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\Exceptions\ApiProxyException;
use CaliforniaMountainSnake\UtilTraits\Curl\CurlUtils;
use CaliforniaMountainSnake\UtilTraits\Curl\HttpResponse;

class HttpApiProxy implements ApiProxyInterface
{
    use CurlUtils;

    /**
     * @var string
     */
    protected $appUrl;

    /**
     * HttpApiProxy constructor.
     * @param string $_app_url
     */
    public function __construct(string $_app_url)
    {
        $this->appUrl = $_app_url;
    }

    /**
     * Выполнить запрос к заданноу роуту API.
     *
     * @param AvailableRoute $_rote Роут.
     * @param array $_params Параметры запроса [опционально].
     *
     * @return HttpResponse
     *
     * @throws ApiProxyException
     */
    public function query(AvailableRoute $_rote, array $_params = []): HttpResponse
    {
        $response = $this->httpQuery($_rote->getMethod(), $this->appUrl . '/' . $_rote->getRoute(), $_params);
        $code     = $response->getCode();
        if ($code !== 200) {
            throw new ApiProxyException('Api returns ' . $code . ' http code! Content: "'
                . \var_export($response->jsonDecode(), true) . '"', $code);
        }

        return $response;
    }
}
