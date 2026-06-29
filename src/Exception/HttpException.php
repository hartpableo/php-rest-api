<?php

namespace App\Exception;

abstract class HttpException extends \Exception {
  public function __construct(
    string        $message,
    protected int $statusCode = 500,
    ?\Throwable   $previous = NULL
  ) {
    parent::__construct($message, 0, $previous);
  }

  public function getStatusCode(): int {
    return $this->statusCode;
  }
}