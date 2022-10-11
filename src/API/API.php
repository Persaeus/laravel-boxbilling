<?php

namespace Nihilsen\BoxBilling\API;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Nihilsen\BoxBilling\Exceptions\APIErrorException;
use Nihilsen\BoxBilling\Facades\BoxBilling;
use Nihilsen\BoxBilling\Role;

abstract class API
{
    /**
     * The cookies for the request.
     *
     * @var \GuzzleHttp\Cookie\CookieJar|null
     */
    protected ?CookieJar $cookies;

    /**
     * The Role corresponding to this subset of the API endpoints.
     *
     * @var \Nihilsen\BoxBilling\Role
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
     * Get or set the cookies with the given $key.
     *
     * @param  string|null  $key
     * @param  \GuzzleHttp\Cookie\CookieJar|null  $set
     * @return \GuzzleHttp\Cookie\CookieJar|null
     */
    public function cookies(?string $key = null, ?CookieJar $set = null): ?CookieJar
    {
        if (is_null($key)) {
            return $this->cookies($this->cookieKey(), $set);
        }

        $cacheKey = 'boxbilling.cookies:'.$key;

        if (
            $set &&
            Cache::put($cacheKey, $set, $this->cookieTtl())
        ) {
            $this->cookies = $set;
        }

        return $this->cookies ??= cache($cacheKey);
    }

    /**
     * Get the cache keys for cookies.
     *
     * @return string
     */
    protected function cookieKey(): string
    {
        return class_basename(static::class);
    }

    /**
     * Get the cache ttl for cookies.
     *
     * @return \DateTimeInterface|\DateInterval|int
     */
    protected function cookieTtl(): \DateTimeInterface|\DateInterval|int
    {
        return now()->addMinutes(10);
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

        $this->cookies(set: $response->cookies());

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
        $request = Http::acceptJson()->baseUrl(BoxBilling::url());

        if ($cookies = $this->cookies()?->toArray()) {
            return $request->withCookies(
                domain: head($cookies)['Domain'],
                cookies: collect($cookies)
                    ->mapWithKeys(fn (array $cookie) => [$cookie['Name'] => $cookie['Value']])
                    ->all()
            );
        }

        return $request;
    }
}
