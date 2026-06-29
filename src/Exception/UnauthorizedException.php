<?php

namespace App\Exception;

class UnauthorizedException extends HttpException {
  public function __construct(
    string      $message = "Not Authorized.",
    ?\Throwable $previous = NULL
  ) {
    parent::__construct($message, 401, $previous);
  }
}