<?php

namespace Nihilsen\BoxBilling\API;

/**
 * @method mixed client_login(int $id)
 */
class Admin extends AuthenticatedAPI
{
    protected string $username = 'admin';
}
