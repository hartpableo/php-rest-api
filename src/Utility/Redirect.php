<?php

namespace App\Utility;

final class Redirect {
  public function __construct(
    string $uri,
    int $statusCode = 302,
  ) {
    if (!in_array($statusCode, [301, 302], true)) {
      throw new \InvalidArgumentException('Invalid redirect status code');
    }

    http_response_code($statusCode);
    header('Location: ' . $uri, true, $statusCode);
    exit;
  }
}