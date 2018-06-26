<?php

namespace EFrame\Amqp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Amqp
 * @package EFrame\Amqp\Facades
 * @see EFrame\Amqp\Amqp
 */
class Amqp extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Amqp';
    }
}
