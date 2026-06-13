<?php

namespace App\Exception;

class NotFoundException extends \Exception {
  public function displayError() {
    http_response_code(404);
    echo $this->getMessage();
    exit;
  }
}