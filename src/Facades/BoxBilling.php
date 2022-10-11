<?php

namespace Nihilsen\BoxBilling\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Nihilsen\BoxBilling\API\Admin admin()
 * @method static \Nihilsen\BoxBilling\API\Client client(int|null $id = null)
 * @method static \Nihilsen\BoxBilling\API\Guest guest()
 * @method static string token()
 * @method static string url()
 *
 * @see \Nihilsen\BoxBilling\Accessor
 */
class BoxBilling extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Nihilsen\BoxBilling\Accessor::class;
    }
}
