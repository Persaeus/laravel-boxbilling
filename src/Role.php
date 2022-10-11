<?php

namespace Nihilsen\BoxBilling;

use Nihilsen\BoxBilling\API\Admin;
use Nihilsen\BoxBilling\API\API;
use Nihilsen\BoxBilling\API\Client;
use Nihilsen\BoxBilling\API\Guest;

enum Role: string
{
    case Guest = 'guest';
    case Client = 'client';
    case Admin = 'admin';

    public function api(): API
    {
        return match ($this) {
            self::Admin => new Admin(),
            self::Client => new Client(),
            self::Guest => new Guest(),
        };
    }
}
