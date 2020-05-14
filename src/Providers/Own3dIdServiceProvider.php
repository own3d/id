<?php

namespace Own3d\Id\Providers;

use Illuminate\Support\ServiceProvider;
use Own3d\Id\Own3dId;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Own3dIdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/../config/own3d-id.php' => config_path('own3d-id.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../config/own3d-id.php', 'own3d-id'
        );
        $this->app->singleton(Own3dId::class, function () {
            return new Own3dId();
        });
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [Own3dId::class];
    }
}