<?php

namespace App\Core;

use App\Attributes\Route;
use App\Exception\InternalServerErrorException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use ReflectionException as ReflectionExceptionAlias;

final class Router {
  private array $routes = [];

  public function __construct(
    private readonly Container $container,
    private readonly Request   $request,
    private readonly ApiAuth   $apiAuth,
  ) {
  }

  /**
   * @throws ReflectionExceptionAlias
   */
  public function register(): void {
    $classes = require_once APP_ROOT . '/config/actions.php';

    foreach ($classes as $controller) {
      $reflectionController = new \ReflectionClass($controller);

      // Process Class-level routes (mapping to __invoke)
      $classAttributes = $reflectionController->getAttributes(Route::class);
      foreach ($classAttributes as $attribute) {
        $route = $attribute->newInstance();
        $methodName = '__invoke';

        if ($reflectionController->hasMethod($methodName)) {
          $method = $reflectionController->getMethod($methodName);
          $dependenciesNeeded = [];
          foreach ($method->getParameters() as $param) {
            $type = $param->getType();
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
              $dependenciesNeeded[] = $type->getName();
            }
          }

          $middlewares = $route->middlewares;

          // Handle case where route attribute has multiple methods (array)
          $routeMethod = $route->method;
          if (is_array($routeMethod)) {
            foreach ($routeMethod as $method) {
              $this->routes[$method][$route->path] = [
                $controller,
                $methodName,
                $dependenciesNeeded,
                $middlewares
              ];
            }
          }
          else {
            $this->routes[$routeMethod][$route->path] = [
              $controller,
              $methodName,
              $dependenciesNeeded,
              $middlewares
            ];
          }
        }
      }

      // Process Method-level routes
//      foreach ($reflectionController->getMethods() as $method) {
//        if ($method->getName() === '__invoke' && !empty($classAttributes)) {
//          continue;
//        }
//        $attributes = $method->getAttributes(Route::class);
//        foreach ($attributes as $attribute) {
//          $route = $attribute->newInstance();
//
//          $dependenciesNeeded = [];
//          foreach ($method->getParameters() as $param) {
//            $type = $param->getType();
//            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
//              $dependenciesNeeded[] = $type->getName();
//            }
//          }
//
//          $this->routes[$route->method][$route->path] = [
//            $className,
//            $method->getName(),
//            $dependenciesNeeded
//          ];
//        }
//      }
    }
  }

  /**
   * @throws UnauthorizedException
   * @throws InternalServerErrorException
   * @throws NotFoundException
   */
  public function resolve(
    string $path,
    string $method,
  ) {
    // Protect API endpoints
    if (
      str_starts_with($path, '/api/')
      && !$this->apiAuth->isValidAPI($this->request)
    ) {
      throw new UnauthorizedException("Not authorized");
    }

    $routePath = parse_url($path, PHP_URL_PATH);
    $action = $this->routes[$method][$routePath] ?? NULL;

    if (!$action) {
      throw new NotFoundException("Not found");
    }

    [$class, $method, $dependenciesNeeded, $middlewares] = $action;

    // Execute middlewares
    foreach ($middlewares as $key => $mwArgs) {
      if (is_callable([new Middleware(), $key])) {
        call_user_func_array([new Middleware(), $key], explode('|', $mwArgs));
      }
    }

    // Resolve dependencies out of the container at runtime
    $argsToPass = [];
    foreach ($dependenciesNeeded as $neededClass) {
      $resolvedInstance = $this->container->get($neededClass);

      if ($resolvedInstance === NULL) {
        throw new InternalServerErrorException("Cannot resolve dependency: {$neededClass}");
      }

      $argsToPass[] = $resolvedInstance;
    }

    // Resolve controller instance from the container (enabling constructor autowiring)
    $controllerInstance = $this->container->get($class);

    if ($controllerInstance === NULL) {
      throw new InternalServerErrorException("Cannot resolve controller: {$class}");
    }

    // Pass dependencies natively
    return $controllerInstance->$method(...$argsToPass);
  }

}