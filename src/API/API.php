<?php

namespace Nihilsen\FOSSBilling\API;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Nihilsen\FOSSBilling\Exceptions\APIErrorException;
use Nihilsen\FOSSBilling\Facades\FOSSBilling;
use Nihilsen\FOSSBilling\Role;

abstract class API
{
    /**
     * The Role corresponding to this subset of the API endpoints.
     *
     * @var \Nihilsen\FOSSBilling\Role
     */
    public readonly Role $role;

    /**
     * Proxy calls to the API.
     *
     * @param  string  $name
     * @param  array  $arguments
     */
    public function __call($name, $arguments)
    {
        return $this->query($name, $arguments);
    }

    /**
     * Query the API with given $method and $parameters.
     *
     * @param  string  $method
     * @param  array|null  $parameters
     */
    protected function query(string $method, ?array $parameters)
    {
        $endpoint = str($method)
            ->replaceFirst('_', '/')
            ->prepend(class_basename($this), '/')
            ->lower();

        $response = $this->request()->post($endpoint, $parameters ?? []);

        if ($error = $response->json('error')) {
            throw new APIErrorException($error);
        }

        return $response->json('result');
    }

    /**
     * Get the base request.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function request(): PendingRequest
    {
        return Http::acceptJson()->baseUrl(FOSSBilling::url());
    }
}
