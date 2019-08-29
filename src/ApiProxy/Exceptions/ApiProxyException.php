<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotLaravelApiAuthSystem\ApiProxy\Exceptions;

use Throwable;

class ApiProxyException extends \LogicException
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * ApiProxyException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     * @param array          $_errors
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null, array $_errors = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $_errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
