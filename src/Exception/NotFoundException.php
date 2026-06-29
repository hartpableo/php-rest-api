<?php

namespace App\Exception;

class NotFoundException extends HttpException {
  public function __construct(
    string      $message = "Resource Not Found.",
    ?\Throwable $previous = NULL
  ) {
    parent::__construct($message, 404, $previous);
  }
}