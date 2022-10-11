<?php

namespace Nihilsen\BoxBilling\API;

use Illuminate\Http\Client\PendingRequest;
use Nihilsen\BoxBilling\Facades\BoxBilling;

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
    public function request(): PendingRequest
    {
        return parent::request()->withBasicAuth(
            $this->username,
            $this->token()
        );
    }

    protected function token(): string
    {
        return BoxBilling::token();
    }
}
