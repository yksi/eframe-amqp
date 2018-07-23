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
        $this->app->bind(Publisher::class, function ($app) {
            return new Publisher($app->config);
        });

        $this->app->bind(Consumer::class, function ($app) {
            return new Consumer($app->config);
        });

        $this->app->bind('Amqp', Amqp::class);
    }
}
