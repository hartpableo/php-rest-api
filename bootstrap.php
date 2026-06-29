<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Container;
use App\Core\Request;
use App\Core\Router;
use App\Exception\BusinessRuleException;
use App\Exception\HttpException;
use App\Utility\JsonResponse;
use App\Utility\Template;

// Constants
define('APP_ROOT', realpath(__DIR__));

// Global exception handler
set_exception_handler(function (\Throwable $exception) {
  $status = 500;
  $errors = NULL;
  $message = "Internal Server Error";

  if ($exception instanceof HttpException) {
    $status = $exception->getStatusCode();
    $message = $exception->getMessage();
  }
  elseif ($exception instanceof BusinessRuleException) {
    $status = 422; // Unprocessable Entity
    $message = $exception->getMessage();
    $errors = $exception->getErrors(); // Retrieve detailed validation list
  }

  // If status is 500, log the error internally
  if ($status >= 500) {
    error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
  }

  // Check if it's an API request (i.e., starts with /api/)
  global $request;
  $isApi = FALSE;
  if (isset($request) && $request instanceof Request) {
    if (str_starts_with($request->uri, '/api/')) {
      $isApi = TRUE;
    }
  }
  elseif (
    isset($_SERVER['REQUEST_URI'])
    && str_starts_with($_SERVER['REQUEST_URI'], '/api/')
  ) {
    $isApi = TRUE;
  }

  if ($isApi) {
    $responsePayload = [
      'ok' => FALSE,
      'message' => $message,
    ];
    if ($errors !== NULL) {
      $responsePayload['errors'] = $errors;
    }

    $response = new JsonResponse($responsePayload, $status);
    $response->send();
  }
  else {
    new Template('status', [
      'status' => $status,
      'message' => $message,
      'errors' => $errors,
    ], $status);
    exit;
  }
});

// Request
$request = new Request();

// Container
$container = new Container();
$container->set(Container::class, $container);
$container->set(Request::class, $request);

// Router
$router = $container->get(Router::class);
$router->register();

// Resolve route
$response = $router->resolve($request->uri, $request->method);
if ($response instanceof \App\Utility\JsonResponse) {
  $response->send();
}