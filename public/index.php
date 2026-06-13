<?php

use App\Core\Router;
use App\Utility\JsonResponse;

require_once __DIR__ . '/../bootstrap.php';

// TODO: Store the allowed origins somewhere more dynamic soon
$allowedOrigins = [
  'http://hart.test'
];

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? NULL;

// Validate origin
if (!in_array($origin, $allowedOrigins)) {
  return new JsonResponse([
    'ok' => FALSE,
    'message' => 'Forbidden'
  ], 403);
}

// Handle preflight request
if ($requestMethod === 'OPTIONS') {
  http_response_code(204);
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH');
  header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');
  header('Access-Control-Max-Age: 7200');
  exit;
}

// Router
$router = new Router();
try {
  $router->register();
} catch (ReflectionException $e) {
  return new JsonResponse([
    'ok' => FALSE,
    'message' => 'API Error: ' . $e->getMessage()
  ], 404);
}

$router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
