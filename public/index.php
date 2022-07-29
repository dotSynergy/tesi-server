<?php

require_once __DIR__.'/../bootstrap.php';
 
use Core\Request;

$request = new Request($_SERVER, $_POST, $_GET, $_FILES);
 
try {
    $controller = $request->getController();

    $method = $request->getMethod($controller);
 
    $controller = new $controller;
    echo $controller->$method($request->getUrlParts());
} catch (Exception $e) {
    echo sprintf(
        '<h3>%s</h3><h4>%s</h4><h5>%s:%s</h5>',
        $e->getCode(),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );
}