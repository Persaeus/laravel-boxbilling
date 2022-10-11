<?php

namespace Nihilsen\BoxBilling;

use Nihilsen\BoxBilling\Exceptions\UnexpectedAPIRoleException;

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

    public function client(?int $id = null)
    {
        /** @var \Nihilsen\BoxBilling\API\Client */
        $api = Role::Client->api();

        if (is_null($id)) {
            return $api;
        }

        return $api->withId($id);
    }

    /**
     * Get the authentication token for authenticated API requests.
     */
    public function token()
    {
        return config('boxbilling.token');
    }

    /**
     * Get the base url for API requests.
     */
    public function url()
    {
        return config('boxbilling.url');
    }
}
