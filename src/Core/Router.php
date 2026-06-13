<?php

namespace App\Core;

use App\Attributes\Route;
use App\Exception\InternalServerErrorException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use ReflectionException as ReflectionExceptionAlias;

class Router {
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
    $controllerFiles = glob(APP_ROOT . '/src/Controller/*.php');
    $controllers = array_map(
      fn($cont) => basename($cont, '.php'),
      $controllerFiles
    );

    foreach ($controllers as $controller) {
      $className = "App\\Controller\\{$controller}";
      $reflectionController = new \ReflectionClass($className);
      foreach ($reflectionController->getMethods() as $method) {
        $attributes = $method->getAttributes(Route::class);
        foreach ($attributes as $attribute) {
          $route = $attribute->newInstance();

          $dependenciesNeeded = [];
          foreach ($method->getParameters() as $param) {
            $type = $param->getType();
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
              // Store the exact class name required (e.g. "App\Core\Request")
              $dependenciesNeeded[] = $type->getName();
            }
          }

          $this->routes[$route->method][$route->path] = [
            $className,
            $method->getName(),
            $dependenciesNeeded // Array of class strings stored in memory
          ];
        }
      }
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

    $controllerInstance = new $class();

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
    $allowedOrigins = [
      'http://hart.test'
    ];

    // Validate origin
    return in_array(($this->request->headers['Origin'] ?? NULL), $allowedOrigins);
  }

}