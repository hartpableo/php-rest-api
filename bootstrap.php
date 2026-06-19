<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Container;
use App\Core\Request;
use App\Core\Router;
use App\Exception\InternalServerErrorException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;

// Constants
define('APP_ROOT', realpath(__DIR__));

// Request
$request = new Request();

// Container
$container = new Container();
$container->set(Request::class, $request);

// Router
$router = new Router($container, $request);
try {
  $router->register();
} catch (ReflectionException $e) {
  die($e->getMessage());
}

// Resolve route
try {
  $router->resolve($request->uri, $request->method);
} catch (UnauthorizedException|NotFoundException|InternalServerErrorException $e) {
  $e->displayError();
}