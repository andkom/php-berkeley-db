<?php

declare(strict_types=1);

namespace AndKom\BerkeleyDb;

/**
 * Class ServiceProvider
 * @package AndKom\BerkeleyDb
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/berkeleydb.php' => config_path('berkeleydb.php'),
        ]);
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bdb', function () {
            return new Manager(config('berkeleydb'));
        });

        $this->app->singleton('bdb.adapter', function ($app) {
            return $app['bdb']->adapter();
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['bdb', 'bdb.adapter'];
    }
}