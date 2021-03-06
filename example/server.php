<?php

use Clue\Redis\Server\Client;
use Clue\Redis\Server\Factory;
use Clue\Redis\Server\Server;
use Clue\Redis\Protocol\Model\ModelInterface;

require __DIR__ . '/../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$factory = new Factory($loop);

$address = '127.0.0.1:1337';

$factory->createServer($address)->then(function(Server $server) use ($address) {
    echo 'server listening on ' . $address . '!' . PHP_EOL;

    $server->on('connection', function ($client) {
        echo 'client connected' . PHP_EOL;
    });

    $server->on('error', function ($error, $client) {
        echo 'ERROR: ' . $error->getMessage() . PHP_EOL;
    });

    $server->on('request', function (ModelInterface $request, Client $client) {
        echo $client->getRequestDebug($request) . PHP_EOL;
    });

    $server->on('disconnection', function ($client) {
        echo 'client disconnected' . PHP_EOL;
    });
});

$loop->run();