<?php

namespace App;

use App\Attributes\Route;
use App\Helper\JsonResponse;

class Router {
  private array $routes = [];

  public function register(): void {
    $controllers = array_map(
      fn($cont) => basename($cont, '.php'),
      glob(APP_ROOT . '/src/Controller/*.php')
    );

    foreach ($controllers as $controller) {
      $reflectionController = new \ReflectionClass("App\\Controller\\{$controller}");
      foreach ($reflectionController->getMethods() as $method) {
        $attributes = $method->getAttributes(Route::class);
        foreach ($attributes as $attribute) {
          $route = $attribute->newInstance();
          $this->routes[$route->method][$route->path] = [$controller, $method->getName()];
        }
      }
    }
  }

  public function resolve(
    string $path,
    string $method,
  ) {
    $route = parse_url($path, PHP_URL_PATH);
    $action = $this->routes[$method][$route] ?? NULL;
    if (empty($action)) {
      // TODO: Update 404 handling if still necessary
      http_response_code(404);
      return new JsonResponse([
        'ok' => FALSE,
        'message' => 'Request not found'
      ]);
    }

    [$class, $method] = $action;
    $class = "App\\Controller\\{$class}";
//    error_log(print_r($action, true), 3, APP_ROOT . '/logs/router.log');

    if (!method_exists($class, $method)) {
      // TODO: Update 404 handling if still necessary
      http_response_code(404);
      return new JsonResponse([
        'ok' => FALSE,
        'message' => 'Request not found'
      ]);
    }

    return call_user_func_array([new $class, $method], []);
  }
}