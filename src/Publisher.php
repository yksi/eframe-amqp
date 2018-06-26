<?php

namespace EFrame\Amqp;

/**
 * Class Publisher
 * @package EFrame\Amqp
 */
class Publisher extends Request
{
    /**
     * @param string  $routing
     * @param Message $message
     * @throws Exception\Configuration
     */
    public function publish($routing, $message)
    {
        $this->getChannel()->basic_publish($message, $this->getProperty('exchange'), $routing);
    }
}
