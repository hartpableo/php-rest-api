<?php

namespace App\Exception;

class TooManyRequestsException extends HttpException {
  public function __construct(
    string      $message = "Too Many Requests.",
    ?\Throwable $previous = NULL
  ) {
    parent::__construct($message, 429, $previous);
  }
}