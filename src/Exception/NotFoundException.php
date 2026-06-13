<?php

namespace App\Exception;

class NotFoundException extends \Exception {
  public function displayError($mode = 1) {
    http_response_code(404);

    switch ($mode) {
      case 2:
        header('Content-Type: application/json');
        echo json_encode([
          'ok' => FALSE,
          'message' => $this->getMessage(),
        ]);
        break;

      default:
        echo $this->getMessage();
        break;
    }

    exit;
  }
}