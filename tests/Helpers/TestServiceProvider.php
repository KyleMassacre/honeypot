<?php

namespace Larapress\Honeypot\Tests\Helpers;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom('tests/Helpers/routes.php');
    }

    public function register()
    {
        $viewPath = $this->getPath('tests/Helpers');
        $this->loadViewsFrom($viewPath, 'honeypot-test');
    }

    protected function getPath(string $path = '')
    {
        // We get the child class
        $rc = new \ReflectionClass(static::class);

        return dirname($rc->getFileName()) . '/../../' . $path;
    }
}
