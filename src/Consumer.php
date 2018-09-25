<?php

namespace EFrame\Amqp;

use Closure;
use Illuminate\Config\Repository;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPTimeoutException;

/**
 * Class Consumer
 * @package EFrame\Amqp
 */
class Consumer extends Request
{
    /**
     * @var int
     */
    protected $messageCount = 0;

    /**
     * @param string  $queue
     * @param Closure $closure
     * @return bool
     * @throws \Exception
     */
    public function consume($queue, Closure $closure)
    {
        $this->messageCount = $this->getQueueMessageCount();

        if (!$this->getProperty('persistent') && $this->messageCount == 0) {
            throw new Exception\Stop();
        }

        $object = $this;

        $this->getChannel()->basic_consume(
            $queue,
            $this->getProperty('consumer_tag'),
            $this->getProperty('consumer_no_local'),
            $this->getProperty('consumer_no_ack'),
            $this->getProperty('consumer_exclusive'),
            $this->getProperty('consumer_nowait'),
            function ($message) use ($closure, $object) {
                $closure($message, $object);
            }
        );

        while (count($this->getChannel()->callbacks)) {
            $this->getChannel()->wait(
                null,
                !$this->getProperty('blocking'),
                $this->getProperty('timeout') ? $this->getProperty('timeout') : 0
            );
        }

        return true;
    }

    /**
     * Acknowledges a message
     *
     * @param AMQPMessage $message
     */
    public function acknowledge(AMQPMessage $message)
    {
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        if ($message->body === 'quit') {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }
    }

    /**
     * Rejects a message and requeues it if wanted (default: false)
     *
     * @param AMQPMessage $message
     * @param bool    $requeue
     */
    public function reject(AMQPMessage $message, $requeue = false)
    {
        $message->delivery_info['channel']->basic_reject($message->delivery_info['delivery_tag'], $requeue);
    }

    /**
     * Stops consumer when no message is left
     *
     * @throws Exception\Stop
     */
    public function stopWhenProcessed()
    {
        if (--$this->messageCount <= 0) {
            throw new Exception\Stop();
        }
    }
}
