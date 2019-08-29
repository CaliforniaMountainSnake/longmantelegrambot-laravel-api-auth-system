<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy;

use CaliforniaMountainSnake\UtilTraits\Curl\RequestMethodEnum;

class AvailableRoute
{
    /**
     * Http-method.
     * @var RequestMethodEnum
     */
    protected $method;

    /**
     * The path to the api route relative the domain root.
     *
     * @var string
     */
    protected $route;

    /**
     * AvailableRoute constructor.
     *
     * @param RequestMethodEnum $method Http-method.
     * @param string            $route  The path to the api route relative the domain root.
     */
    public function __construct(RequestMethodEnum $method, string $route)
    {
        $this->method = $method;
        $this->route = $route;
    }

    /**
     * Get http-method.
     *
     * @return RequestMethodEnum
     */
    public function getMethod(): RequestMethodEnum
    {
        return $this->method;
    }

    /**
     * Get the path to the api route relative the domain root.
     *
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->method . ' ' . $this->route;
    }
}
