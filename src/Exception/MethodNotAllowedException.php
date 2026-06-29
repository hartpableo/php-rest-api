<?php

namespace App\Exception;

class MethodNotAllowedException extends HttpException {
  public function __construct(
    string      $message = "Method Not Allowed.",
    ?\Throwable $previous = NULL
  ) {
    parent::__construct($message, 405, $previous);
  }
}