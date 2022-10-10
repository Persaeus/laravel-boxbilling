<?php

namespace Nihilsen\FOSSBilling;

use Nihilsen\FOSSBilling\Exceptions\UnexpectedAPIRoleException;

class Accessor
{
    /**
     * Get the API matching the $method
     *
     * @param  string  $method
     */
    public function __call($method, $_)
    {
        if (is_null($role = Role::tryFrom($method))) {
            throw new UnexpectedAPIRoleException($method);
        }

        return $role->api();
    }

    /**
     * Get the authentication token for authenticated API requests.
     */
    public function token()
    {
        return config('fossbilling.token');
    }

    /**
     * Get the base url for API requests.
     */
    public function url()
    {
        return config('fossbilling.url');
    }
}
