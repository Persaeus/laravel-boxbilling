<?php

namespace Nihilsen\BoxBilling\API;

use Illuminate\Http\Client\PendingRequest;
use Nihilsen\BoxBilling\Exceptions\APIErrorException;
use Nihilsen\BoxBilling\Facades\BoxBilling;

/**
 * @method mixed support_ticket_get_list(string|null $status = null, string|null $date_from = null, string|null $date_to = null, int|null $page = null, int|null $per_page = null) Get client tickets list. $date_from and $date_to can be any string parsable by strtotime().
 */
class Client extends AuthenticatedAPI
{
    protected bool $loggedIn = false;

    protected ?int $id = null;

    protected string $username = 'client';

    /**
     * {@inheritDoc}
     */
    protected function cookieKey(): string
    {
        $key = parent::cookieKey();

        if (is_null($this->id)) {
            return $key;
        }

        return $key.':'.$this->id;
    }

    /**
     * Log in as the client via the admin api.
     */
    protected function logIn()
    {
        $adminAPI = BoxBilling::admin();

        $adminAPI->client_login(id: $this->id);

        $this->cookies(set: $adminAPI->cookies());

        $this->loggedIn = true;
    }

    /**
     * {@inheritDoc}
     */
    public function query(string $method, ?array $parameters)
    {
        try {
            return parent::query($method, $parameters);
        } catch (APIErrorException $th) {
            // If authentication error...
            if (
                $this->id &&
                ! $this->loggedIn &&
                $th->getCode() == '204'
            ) {
                $this->logIn();

                return parent::query($method, $parameters);
            }

            throw $th;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function request(): PendingRequest
    {
        if ($this->id && is_null($this->cookies())) {
            $this->logIn();
        }

        return parent::request();
    }

    /**
     * Set the client $id.
     *
     * @param  int|null  $id
     * @return static
     */
    public function withId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }
}
