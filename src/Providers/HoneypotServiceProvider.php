<?php

namespace Larapress\Honeypot\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Larapress\Honeypot\Facades\Honeypot as HoneypotFacade;
use Larapress\Honeypot\Honeypot;

class HoneypotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();
        $this->registersViews();
    }

    public function boot(): void
    {
        $this->publishers();
        $this->honeypot();
        $this->registerBladeComponent();
    }

    private function registerConfig(): void
    {
        $configPath = $this->getPath('config');

        $this->mergeConfigFrom(
            "{$configPath}/config.php",
            'honeypot'
        );
    }

    public function publishers(): void
    {
        $configPath = $this->getPath('config');

        $this->publishes([
            "{$configPath}/config.php" => config_path('honeypot.php'),
        ], 'config');

        $this->publishViews();
    }

    protected function getPath(string $path = ''): string
    {
        // We get the child class
        $rc = new \ReflectionClass(static::class);

        return dirname($rc->getFileName()) . '/../../' . $path;
    }

    public function honeypot(): void
    {
        $request = app('request');
        $config = app('config');

        $this->app->instance(HoneypotFacade::class, new Honeypot($request, $config));
    }

    public function publishViews(): void
    {
        $viewpath = $this->getPath('resources/views');
        $this->publishes([
            $viewpath => base_path("resources/views/vendor/honeypot"),
        ], "honeypot-views");
    }

    public function registersViews(): void
    {
        $viewpath = $this->getPath('resources/views');
        $this->loadViewsFrom($viewpath, 'honeypot');
    }

    public function registerBladeComponent(): void
    {
        Blade::componentNamespace('Larapress\\Honeypot\\View', 'honeypot');
    }
}
