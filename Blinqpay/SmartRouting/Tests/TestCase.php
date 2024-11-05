<?php

namespace Blinqpay\SmartRouting\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Blinqpay\SmartRouting\SmartRoutingServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SmartRoutingServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Load package migrations for testing
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
    }
}
