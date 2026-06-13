<?php

namespace App\Exception;

class InternalServerErrorException extends \Exception {
  public function displayError() {
    http_response_code(500);
    echo $this->getMessage();
    exit;
  }
}