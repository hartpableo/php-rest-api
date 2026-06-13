<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Container;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Utility\JsonResponse;

// Constants
define('APP_ROOT', realpath(__DIR__));

// Container
$container = new Container();
$container->set(Request::class, new Request());
$container->set(Database::class, new Database());

// Request
$request = new Request();
$requestMethod = $request->method;
$requestUri = $request->uri;
$origin = $request->headers['Origin'] ?? NULL;

// Router
$router = new Router($container, $request);
try {
  $router->register();
} catch (ReflectionException $e) {
  return new JsonResponse([
    'ok' => FALSE,
    'message' => 'API Error: ' . $e->getMessage()
  ], 404);
}

// Resolve route
try {
  $router->resolve(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD'],
  );
} catch (UnauthorizedException|NotFoundException $e) {
  $e->displayError();
}