<?php

namespace Designitgmbh\MonkeyAccess;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class MonkeyAccessServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
    	$this->app->middleware([
            'load_monkey_access_rights' => 'Designitgmbh\MonkeyAccess\Http\Middleware\AccessRightsLoaderMiddleware',
        ]);

        // use this if your package has views
        // $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'monkeyAccess');
        
        // use this if your package has routes
        // $this->setupRoutes($this->app->router);
        
        // use this if your package needs a config file
        // $this->publishes([
        //         __DIR__.'/config/config.php' => config_path('monkeyAccess.php'),
        // ]);
        
        // use the vendor configuration file as fallback
        // $this->mergeConfigFrom(
        //     __DIR__.'/config/config.php', 'monkeyAccess'
        // );

        // publish our migrations
        $this->publishes([
		    __DIR__.'/migrations' => $this->app->databasePath().'/migrations',
		]);	
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Designitgmbh\MonkeyAccess\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMonkeyAccess();
        
        // use this if your package has a config file
        // config([
        //         'config/monkeyAccess.php',
        // ]);
    }

    private function registerMonkeyAccess()
    {
        $this->app->bind('monkeyAccess',function($app){
            return new MonkeyAccess($app);
        });
    }
}