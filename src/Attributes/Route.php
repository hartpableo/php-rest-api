<?php

namespace App\Attributes;

use Attribute;

#[\Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Route {
  public function __construct(
    public string       $path,
    public array|string $method = 'GET',
  ) {
  }
}