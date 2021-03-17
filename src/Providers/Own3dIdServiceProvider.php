<?php

namespace Own3d\Id\Providers;

use Illuminate\Support\ServiceProvider;
use Own3d\Id\Console;
use Own3d\Id\Contracts;
use Own3d\Id\Own3dId;
use Own3d\Id\Repository\AppTokenRepository;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Own3dIdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Own3dId::shouldRunMigrations()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
        }
        $this->publishes([
            dirname(__DIR__) . '/../config/own3d-id.php' => config_path('own3d-id.php'),
        ], 'config');

        $this->registerCommands();
    }

    /**
     * Register the application services.
     *
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

        $this->app->singleton(Contracts\AppTokenRepository::class, AppTokenRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [Own3dId::class];
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\IdTwitchAccessToken::class,
            ]);
        }
    }
}
