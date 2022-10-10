<?php

namespace Nihilsen\FOSSBilling;

use Nihilsen\FOSSBilling\API\Admin;
use Nihilsen\FOSSBilling\API\API;
use Nihilsen\FOSSBilling\API\Client;
use Nihilsen\FOSSBilling\API\Guest;

enum Role: string
{
    case Guest = 'guest';
    case Client = 'client';
    case Admin = 'admin';

    public function api(): API
    {
        return match ($this) {
            self::Admin  => new Admin(),
            self::Client => new Client(),
            self::Guest  => new Guest(),
        };
    }
}
