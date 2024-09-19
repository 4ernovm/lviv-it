<?php

require '../vendor/autoload.php';

error_reporting(E_ERROR);

use Chernoff\LvivItTestAssignment\ServiceProvider;
use Pimple\Container;

// Init dependency container
$container = (new Container())->register(new ServiceProvider());
// Init router
$dispatcher = FastRoute\simpleDispatcher(include '../config/routes.php');

// Get route information
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], rawurldecode($uri));

function handle_request($routeInfo, $container): array
{
    [$controller, $action] = $routeInfo[1];
    $vars = $routeInfo[2];

    return $container[$controller]->{$action}(...$vars);
}

$response = match ($routeInfo[0]) {
    FastRoute\Dispatcher::NOT_FOUND => ['status' => 404],
    FastRoute\Dispatcher::METHOD_NOT_ALLOWED => ['status' => 405],
    FastRoute\Dispatcher::FOUND => handle_request($routeInfo, $container),
    default => throw new \RuntimeException('Unexpected error during route resolution', 400),
};

echo json_encode($response, JSON_PRETTY_PRINT);
