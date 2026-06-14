<?php

namespace App\Core;

use ReflectionClass;
use ReflectionNamedType;
use Exception;

final class Container {
  private array $instances = [];

  // Register a shared instance
  public function set(string $id, object $instance): void {
    $this->instances[$id] = $instance;
  }

  // Get a shared instance or resolve/instantiate it if not registered (autowiring)
  public function get(string $id): ?object {
    if (isset($this->instances[$id])) {
      return $this->instances[$id];
    }

    if (!class_exists($id)) {
      return NULL;
    }

    try {
      $instance = $this->resolve($id);
      $this->instances[$id] = $instance;
      return $instance;
    } catch (\Throwable $e) {
      throw new Exception("Error resolving class {$id}: " . $e->getMessage(), 0, $e);
    }
  }

  /**
   * Helper method to resolve class dependencies recursively
   * @throws Exception
   */
  private function resolve(string $className): object {
    $reflector = new ReflectionClass($className);

    if (!$reflector->isInstantiable()) {
      throw new Exception("Class {$className} is not instantiable");
    }

    $constructor = $reflector->getConstructor();

    if (NULL === $constructor) {
      return new $className();
    }

    $parameters = $constructor->getParameters();
    $dependencies = [];

    foreach ($parameters as $parameter) {
      $type = $parameter->getType();

      if (NULL === $type) {
        if ($parameter->isDefaultValueAvailable()) {
          $dependencies[] = $parameter->getDefaultValue();
          continue;
        }
        throw new Exception("Cannot resolve parameter {$parameter->getName()} in class {$className}: missing type hint");
      }

      if ($type instanceof \ReflectionUnionType) {
        throw new Exception("Cannot resolve parameter {$parameter->getName()} in class {$className}: Union types are not supported");
      }

      if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
        $dependencyName = $type->getName();
        $dependency = $this->get($dependencyName);
        if ($dependency === NULL) {
          throw new Exception("Cannot resolve dependency {$dependencyName} for {$className}");
        }
        $dependencies[] = $dependency;
      } else {
        if ($parameter->isDefaultValueAvailable()) {
          $dependencies[] = $parameter->getDefaultValue();
        } else {
          throw new Exception("Cannot resolve built-in parameter {$parameter->getName()} in class {$className}");
        }
      }
    }

    return $reflector->newInstanceArgs($dependencies);
  }
}