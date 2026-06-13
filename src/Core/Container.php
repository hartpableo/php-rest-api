<?php

namespace App\Core;

final class Container {
  private array $instances = [];

  // Register a shared instance
  public function set(string $id, object $instance): void {
    $this->instances[$id] = $instance;
  }

  // Get a shared instance
  public function get(string $id): ?object {
    return $this->instances[$id] ?? NULL;
  }
}