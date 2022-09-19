<?php

//https://github.com/bloatless/php-websocket

include __DIR__.'/bootstrap.php';

use \Bloatless\WebSocket\Server as Server;
use \Bloatless\WebSocket\Application\StatusApplication as StatusApplication;
use \Bloatless\WebSocket\Logger\StdOutLogger as StdOutLogger;

// create new server instance
$server = new Server('0.0.0.0', 8000, '/tmp/phpwss.sock');

// add a PSR-3 compatible logger (optional)
$server->setLogger(new StdOutLogger());

// server settings
//$server->setMaxClients(100);
$server->setAllowedOrigin($_ENV['APP_DOMAIN']);
//$server->setMaxConnectionsPerIp(100);

// add your applications
$server->registerApplication('drone', App\DataSaver::getInstance());
$server->registerApplication('drone-wss', App\DataSaver::getInstance());


$server->run();