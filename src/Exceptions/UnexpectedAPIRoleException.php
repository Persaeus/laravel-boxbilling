<?php

namespace Nihilsen\FOSSBilling\Exceptions;

class UnexpectedAPIRoleException extends \UnexpectedValueException
{
    public function __construct(string $role)
    {
        return parent::__construct("Unknown API role '$role'.");
    }
}
