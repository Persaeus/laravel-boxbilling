<?php

namespace Nihilsen\BoxBilling\Tests;

use Illuminate\Support\Facades\Http;
use Nihilsen\BoxBilling\BoxBillingServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            BoxBillingServiceProvider::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function defineEnvironment($app)
    {
        /** @var \Illuminate\Config\Repository */
        $config = $app['config'];

        $config->set('boxbilling', [
            'url' => 'https://boxbilling.invalid/api',
            'token' => 'token',
        ]);

        Http::preventStrayRequests();
    }
}
