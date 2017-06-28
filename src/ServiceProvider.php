<?php namespace Minhbang\ILib;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use MenuManager;
use Enum;
use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Reader\Reader;
/**
 * Class ServiceProvider
 *
 * @package Minhbang\ILib
 */
class ServiceProvider extends BaseServiceProvider {
    /**
     * @param \Illuminate\Routing\Router $router
     */
    public function boot( Router $router ) {
        $this->loadTranslationsFrom( __DIR__ . '/../lang', 'ilib' );
        $this->loadViewsFrom( __DIR__ . '/../views', 'ilib' );
        $this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
        $this->loadRoutesFrom( __DIR__ . '/routes.php' );
        $this->publishes(
            [
                __DIR__ . '/../views'           => base_path( 'resources/views/vendor/ilib' ),
                __DIR__ . '/../lang'            => base_path( 'resources/lang/vendor/ilib' ),
                __DIR__ . '/../config/ilib.php' => config_path( 'ilib.php' ),
            ]
        );
        // pattern filters
        $router->pattern( 'reader', '[0-9]+' );
        // model bindings
        $router->model( 'reader', Reader::class );
        MenuManager::registerMenus( config( 'ilib.menu' ) );
        Enum::shared( Reader::class, Ebook::class, [ 'security_id' ] );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom( __DIR__ . '/../config/ilib.php', 'ilib' );
    }
}
