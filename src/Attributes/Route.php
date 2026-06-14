<?php

namespace App\Attributes;

use Attribute;

#[\Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Route {
  public function __construct(
    public string $path,
    // TODO: Support array of multiple request methods
    public string $method = 'GET',
  ) {
  }
}