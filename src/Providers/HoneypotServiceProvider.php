<?php

namespace Larapress\Honeypot\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class HoneypotServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->registerConfig();
    }

    public function boot(): void
    {
        $this->publishers();
        $this->macroRequest();
    }
    private function registerConfig(): void
    {
        $configPath = $this->getPath('config');

        $this->mergeConfigFrom(
            "{$configPath}/config.php",
            'honeypot'
        );
    }

    public function publishers()
    {
        $configPath = $this->getPath('config');

        $this->publishes([
            "{$configPath}/config.php" => config_path('honeypot.php'),
        ], 'config');
    }

    protected function getPath(string $path = '')
    {
        // We get the child class
        $rc = new \ReflectionClass(static::class);

        return dirname($rc->getFileName()) . '/../../' . $path;
    }

    public function macroRequest()
    {
        Request::macro('honeypot', function() {
            $this->honeypot = $this->only(config('honeypot.fields'));
            return $this->honeypot;
        });
    }
}
