<?php

namespace App\Attributes;

use Attribute;

#[\Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Route {
  public function __construct(
    public string $path,
    public array|string $method = 'GET',
    public array $middlewares = [],
  ) {
  }
}