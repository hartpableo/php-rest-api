<?php

namespace App\Tests\Core;

use App\Core\Container;
use PHPUnit\Framework\TestCase;
use Exception;

// Test fixtures declared within the namespace for unit testing
class DeepDependency {}

class NestedDependency {
  public function __construct(public DeepDependency $deep) {}
}

class TestController {
  public function __construct(public NestedDependency $nested) {}
}

class ClassWithBuiltinNoDefault {
  public function __construct(string $name) {}
}

class ClassWithBuiltinWithDefault {
  public string $name;
  public function __construct(string $name = 'default_value') {
    $this->name = $name;
  }
}

final class ContainerTest extends TestCase {
  /**
   * TC-CON-001: Verify recursive autowiring successfully instantiates a controller
   * and resolves its nested constructor dependencies from the container.
   */
  public function testRecursiveAutowiringResolvesNestedDependencies(): void {
    $container = new Container();
    $controller = $container->get(TestController::class);

    $this->assertInstanceOf(TestController::class, $controller);
    $this->assertInstanceOf(NestedDependency::class, $controller->nested);
    $this->assertInstanceOf(DeepDependency::class, $controller->nested->deep);
  }

  /**
   * TC-CON-002: Verify autowiring throws a clear Exception if a constructor dependency
   * is a built-in type (e.g. string) with no default value.
   */
  public function testAutowiringThrowsExceptionForBuiltinTypeWithoutDefault(): void {
    $container = new Container();

    $this->expectException(Exception::class);
    // Container wraps the underlying exception in "Error resolving class {className}: {message}"
    $expectedMessage = "Cannot resolve built-in parameter name in class App\Tests\Core\ClassWithBuiltinNoDefault";
    $this->expectExceptionMessage($expectedMessage);

    $container->get(ClassWithBuiltinNoDefault::class);
  }

  /**
   * Verify autowiring resolves a built-in type successfully if a default value is available.
   */
  public function testAutowiringResolvesBuiltinTypeWithDefaultValue(): void {
    $container = new Container();
    $instance = $container->get(ClassWithBuiltinWithDefault::class);

    $this->assertInstanceOf(ClassWithBuiltinWithDefault::class, $instance);
    $this->assertSame('default_value', $instance->name);
  }

  /**
   * Verify that Container returns the same shared instance on subsequent retrievals (Singleton behavior).
   */
  public function testContainerReturnsSharedInstances(): void {
    $container = new Container();
    $instance1 = $container->get(DeepDependency::class);
    $instance2 = $container->get(DeepDependency::class);

    $this->assertSame($instance1, $instance2);
  }

  /**
   * Verify that Container returns null if the requested class ID does not exist.
   */
  public function testContainerReturnsNullForNonExistentClass(): void {
    $container = new Container();
    $result = $container->get('App\Core\NonExistentClass');

    $this->assertNull($result);
  }
}
