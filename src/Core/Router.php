<?php

namespace App\Core;

use App\Attributes\Route;
use App\Exception\InternalServerErrorException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use Exception as ExceptionAlias;
use ReflectionException as ReflectionExceptionAlias;

final class Router {
  private array $routes = [];

  public function __construct(
    private readonly Container $container,
    private readonly Request   $request
  ) {
  }

  /**
   * @throws ReflectionExceptionAlias
   */
  public function register(): void {
    // TODO: Create an array of the action classes instead of using glob() for better performance
    $classFiles = glob(APP_ROOT . '/src/Action/*.php');
    $classes = array_map(
      fn($cont) => basename($cont, '.php'),
      $classFiles
    );

    foreach ($classes as $controller) {
      $className = "App\\Action\\{$controller}";
      $reflectionController = new \ReflectionClass($className);

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
          $this->routes[$route->method][$route->path] = [
            $className,
            $methodName,
            $dependenciesNeeded
          ];
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
    if (str_starts_with($path, '/api/') && !$this->guardApis($method)) {
      throw new UnauthorizedException("Not authorized");
    }

//    error_log(print_r($this->routes, true), 3, APP_ROOT . '/logs/router.log');

    $routePath = parse_url($path, PHP_URL_PATH);
    $action = $this->routes[$method][$routePath] ?? NULL;

    if (!$action) {
      throw new NotFoundException("Not found");
    }

    [$class, $method, $dependenciesNeeded] = $action;

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

  public function guardApis(string $method) {
    // Handle preflight request
    if ($method === 'OPTIONS') {
      http_response_code(204);
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH');
      header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');
      header('Access-Control-Max-Age: 7200');
      exit;
    }

    // TODO: Store the allowed origins somewhere more dynamic/flexible soon
    // TODO: Validate origin against API key (through the Bearer Token in $request)
    $allowedOrigins = [
      'http://hart.test'
    ];

    // Validate origin
    return in_array(($this->request->headers['Origin'] ?? NULL), $allowedOrigins);
  }

}