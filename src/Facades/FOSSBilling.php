<?php

namespace Nihilsen\FOSSBilling\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Nihilsen\FOSSBilling\API\Admin admin()
 * @method static \Nihilsen\FOSSBilling\API\Client client()
 * @method static \Nihilsen\FOSSBilling\API\Guest guest()
 * @method static string token()
 * @method static string url()
 *
 * @see \Nihilsen\FOSSBilling\Accessor
 */
class FOSSBilling extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Nihilsen\FOSSBilling\Accessor::class;
    }
}
