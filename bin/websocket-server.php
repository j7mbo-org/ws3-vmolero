<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use Workshop\EventHandler;

$loop = Factory::create();

$webSock = new Server($loop);
$webSock->listen(1338, '0.0.0.0');

new IoServer(
    new HttpServer(
        new WsServer(
            new WampServer(
                new EventHandler($loop) /** Our event handler containing onSubscribe(), onOpen() etc **/
            )
        )
    ),
    $webSock
);

$loop->run();