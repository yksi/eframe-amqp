<?php

namespace EFrame\Amqp;

use Illuminate\Support\ServiceProvider;

/**
 * Class LumenServiceProvider
 * @package EFrame\Amqp
 */
class LumenServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('EFrame\Amqp\Publisher', function ($app) {
            return new Publisher($app->config);
        });

        $this->app->bind('EFrame\Amqp\Consumer', function ($app) {
            return new Consumer($app->config);
        });

        $this->app->bind('Amqp', 'EFrame\Amqp\Amqp');

        if (!class_exists('Amqp')) {
            class_alias('EFrame\Amqp\Facades\Amqp', 'Amqp');
        }
    }
}
