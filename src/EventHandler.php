<?php

namespace Workshop;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use React\EventLoop\LoopInterface;
use React\EventLoop\Timer\Timer;
use React\ChildProcess\Process;

/**
 * @package Workshop
 *
 * @todo React\ChildProcess documentation is here: https://github.com/reactphp/child-process#quickstart-example
 */
class EventHandler implements WampServerInterface
{
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var null|Timer
     */
    private $timeTimer = null;

    /**
     * @var null|Timer
     */
    private $sqlTimer = null;

    /**
     * @var bool
     */
    private $isBroadcastingTime = false;

    /**
     * @var bool
     */
    private $isBroadcastingSql = false;

    /**
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic               $topic
     */
    function onSubscribe(ConnectionInterface $conn, $topic)
    {
        switch ($topic->getId()) {
            case 'sql':
                // @todo Use React\ChildProcess\Process
                // @todo The process you want to execute is bin/call-sql.php
            break;
            case 'time':
                // When a new person subscribes, are we already broadcasting to all subscribers of this topic?
                if ($this->isBroadcastingTime === true) {
                    return;
                }

                $this->isBroadcastingTime = true;

                // Store the timer so we can cancel it in onUnsubscribe, when nobody else is subscribing
                $this->timeTimer = $this->loop->addPeriodicTimer(1, function() use ($topic) {
                    $topic->broadcast((new \DateTime)->format('H:i:s'));
                });
            break;
            default:
            break;
        }
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic               $topic
     */
    function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        switch ($topic->getId()) {
            case 'time':
                if ($this->isBroadcastingTime && $topic->count() === 0) {
                    $this->timeTimer->cancel();
                    unset($this->timeTimer);
                    $this->isBroadcastingTime = false;
                }
            break;
            case 'sql':
                // @todo
            break;
        }
    }

    /** Don't worry about these for now **/
    public function onClose(ConnectionInterface $conn) { }
    public function onError(ConnectionInterface $conn, \Exception $e) { }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) { }
    public function onOpen(ConnectionInterface $conn) { }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) { }
}