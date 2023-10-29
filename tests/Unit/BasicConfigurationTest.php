<?php

namespace Larapress\Honeypot\Tests\Unit;

use Larapress\Honeypot\Facades\Honeypot;
use Larapress\Honeypot\Providers\HoneypotServiceProvider;
use Larapress\Honeypot\Tests\Helpers\TestRequest;
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

    public function testThatConfigContainsArray()
    {
        Honeypot::updateFields('test1');
        $this->assertIsArray(Honeypot::fields(), "Fields are not an array");
    }

    public function testThatConfigFieldsContainValues(): void
    {
        $this->assertGreaterThanOrEqual(1, Honeypot::fields());
    }

    public function testSetRedirect()
    {
        $to = '/someRandomEndpoint';
        Honeypot::setRedirectTo($to);
        $this->assertEquals($to, Honeypot::redirectTo());
    }

    public function testCanSetRequest()
    {
        $newRequest = new TestRequest();
        Honeypot::setRequest($newRequest);
        $this->assertInstanceOf(\Illuminate\Http\Request::class, Honeypot::request());
    }
}
