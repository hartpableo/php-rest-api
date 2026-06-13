<?php

use App\Helper\JsonResponse;
use App\Router;

require_once __DIR__ . '/vendor/autoload.php';

define('APP_ROOT', realpath(__DIR__));

$router = new Router();
try {
  $router->register();
} catch (ReflectionException $e) {
  http_response_code(404);
  return new JsonResponse([
    'ok' => FALSE,
    'message' => 'API Error: ' . $e->getMessage()
  ]);
}

$router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);