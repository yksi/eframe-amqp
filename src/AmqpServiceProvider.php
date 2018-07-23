<?php

namespace EFrame\Amqp;

use Illuminate\Support\ServiceProvider;

/**
 * Class AmqpServiceProvider
 * @package EFrame\Amqp
 */
class AmqpServiceProvider extends ServiceProvider
{
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Amqp', 'EFrame\Amqp\Amqp');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('EFrame\Amqp\Publisher', function ($app) {
            return new Publisher(config());
        });
        
        $this->app->singleton('EFrame\Amqp\Consumer', function ($app) {
            return new Consumer(config());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Amqp', 
            'EFrame\Amqp\Publisher', 
            'EFrame\Amqp\Consumer'
        ];
    }
}
