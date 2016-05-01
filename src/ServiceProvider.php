<?php
namespace Minhbang\ILib;

use Illuminate\Routing\Router;
use Minhbang\Kit\Extensions\BaseServiceProvider;
use MenuManager;

/**
 * Class ServiceProvider
 *
 * @package Minhbang\ILib
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'ilib');
        $this->loadViewsFrom(__DIR__ . '/../views', 'ilib');
        $this->publishes(
            [
                __DIR__ . '/../views'           => base_path('resources/views/vendor/ilib'),
                __DIR__ . '/../lang'            => base_path('resources/lang/vendor/ilib'),
                __DIR__ . '/../config/ilib.php' => config_path('ilib.php'),
            ]
        );
        $this->publishes(
            [
                __DIR__ . '/../database/migrations/2015_12_27_000000_create_readers_table.php'      =>
                    database_path('migrations/2015_12_27_000000_create_readers_table.php'),
                __DIR__ . '/../database/migrations/2015_12_27_100000_create_ebook_reader_table.php' =>
                    database_path('migrations/2015_12_27_100000_create_ebook_reader_table.php'),
                __DIR__ . '/../database/migrations/2015_12_27_200000_create_read_ebook_table.php'   =>
                    database_path('migrations/2015_12_27_200000_create_read_ebook_table.php'),
            ],
            'db'
        );

        $this->mapWebRoutes($router, __DIR__ . '/routes.php', config('ilib.add_route'));

        // pattern filters
        $router->pattern('reader', '[0-9]+');
        // model bindings
        $router->model('reader', 'Minhbang\ILib\Reader');
        MenuManager::registerMenus(config('ilib.menu'));
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ilib.php', 'ilib');
    }
}
