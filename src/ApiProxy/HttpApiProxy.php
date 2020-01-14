<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy;

use CaliforniaMountainSnake\JsonResponse\JsonResponse;
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
     *
     * @param string $_app_url
     */
    public function __construct(string $_app_url)
    {
        $this->appUrl = $_app_url;
    }

    /**
     * Execute query to the target api route.
     *
     * @param AvailableRoute $_rote    Route.
     * @param array          $_params  Query parameters [optional].
     * @param array|null     $_headers Query headers [optional].
     *
     * @return HttpResponse
     * @throws ApiProxyException
     */
    public function query(AvailableRoute $_rote, array $_params = [], array $_headers = null): HttpResponse
    {
        $response = $this->httpQuery($_rote->getMethod(), $this->appUrl . '/' . $_rote->getRoute(), $_params);
        $httpCode = $response->getCode();
        $responseArray = $response->jsonDecode();
        $apiErrors = ($responseArray === null ? [] : $responseArray[JsonResponse::ERRORS] ?? []);

        if ($httpCode !== JsonResponse::HTTP_OK) {
            throw new ApiProxyException('Api returns ' . $httpCode . ' http code!'
                . ' Response: "' . $response->getContent() . '"', $httpCode, null, $apiErrors);
        }

        return $response;
    }
}
