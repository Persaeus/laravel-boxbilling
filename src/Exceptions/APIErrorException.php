<?php

namespace Nihilsen\FOSSBilling\Exceptions;

class APIErrorException extends \RuntimeException
{
    public function __construct(array $error)
    {
        /**
         * @var int $code
         * @var string $message
         */
        extract($error);

        return parent::__construct("Request returned error code $code: '$message'.", $code);
    }
}
