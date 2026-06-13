<?php

namespace App\Exception;

class UnauthorizedException extends \Exception {
  public function displayError() {
    http_response_code(401);
    echo $this->getMessage();
    exit;
  }
}