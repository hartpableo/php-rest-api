<?php

namespace App\Exception;

class InternalServerErrorException extends HttpException {
  public function __construct(
    string      $message = "Internal Server Error.",
    ?\Throwable $previous = NULL
  ) {
    parent::__construct($message, 500, $previous);
  }
}