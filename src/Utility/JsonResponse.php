<?php

namespace App\Utility;

use JetBrains\PhpStorm\NoReturn;

final class JsonResponse {
  #[NoReturn]
  public function __construct(
    array $data,
    int $status = 200,
    array $headers = []
  ) {
    http_response_code($status);
    header('Content-Type: application/json');
    foreach ($headers as $header => $value) {
      if ($header === 'Content-Type') {
        continue;
      }

      header("{$header}: {$value}");
    }
    echo json_encode($data);
    exit;
  }
}