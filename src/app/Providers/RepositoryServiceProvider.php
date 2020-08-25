<?php

namespace ChuPhong\Repository\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    private const CONFIG_PATH = __DIR__ . '/../../config';

    public function boot()
    {
        $this->publishes(
            [
                self::CONFIG_PATH . '/repository.php' => $this->app->configPath('repository.php')
            ]
        );

        $this->mergeConfigFrom(self::CONFIG_PATH . '/repository.php', 'repository');
    }
}
