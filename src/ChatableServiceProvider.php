<?php

namespace Mojtaba\Chatable;

use Illuminate\Support\ServiceProvider;

class ChatableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->definePublishableConfigs();
        $this->defineMigrations();
    }

    public function register()
    {
        $this->defineConfig();
    }

    public function defineMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../database/migrations");
    }

    public function defineConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/chatable.php', 'chatable'
        );
    }

    public function definePublishableConfigs()
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . "/../database/migrations" => database_path('migrations')
            ], 'chatable-migrations');

            $this->publishes([
                __DIR__ . "/../config/chatable.php" => config_path('chatable.php')
            ], 'chatable-config');
        }
    }
}
