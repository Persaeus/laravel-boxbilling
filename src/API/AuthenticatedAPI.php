<?php

namespace Nihilsen\FOSSBilling\API;

use Illuminate\Http\Client\PendingRequest;
use Nihilsen\FOSSBilling\Facades\FOSSBilling;

abstract class AuthenticatedAPI extends API
{
    /**
     * The "username" for use in basic authentication.
     *
     * @var string
     */
    protected string $username;

    /**
     * {@inheritDoc}
     */
    final public function request(): PendingRequest
    {
        return parent::request()->withBasicAuth(
            $this->username,
            $this->token()
        );
    }

    protected function token(): string
    {
        return FOSSBilling::token();
    }
}
