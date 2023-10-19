<?php

namespace Larapress\Honeypot\Tests\Unit;

use Larapress\Honeypot\Providers\HoneypotServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class BasicConfigurationTest extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            HoneypotServiceProvider::class,
        ];
    }

    public function test_that_config_contains_array()
    {
        $this->assertIsArray(config('honeypot.fields'), "Fields are not an array");
    }

    public function test_that_config_fields_contain_values(): void
    {
        $this->assertGreaterThanOrEqual(1, config('honeypot.fields'));
    }
}
