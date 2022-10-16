<?php

namespace Nihilsen\BoxBilling\API;

/**
 * @method mixed client_login(int $id)
 * @method \Nihilsen\BoxBilling\Collection<array> client_get_list(string|null $status = null, int|null $page = null, int|null $per_page = null)
 */
class Admin extends AuthenticatedAPI
{
    protected string $username = 'admin';
}
