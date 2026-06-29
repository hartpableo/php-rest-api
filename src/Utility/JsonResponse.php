<?php

namespace App\Utility;

use JetBrains\PhpStorm\NoReturn;

final readonly class JsonResponse {
  public function __construct(
    private array $data,
    private int   $status = 200,
    private array $headers = []
  ) {
  }

  #[NoReturn]
  public function send(): void {
    if (!headers_sent()) {
      http_response_code($this->status);
      header('Content-Type: application/json');
      foreach ($this->headers as $key => $value) {
        header("{$key}: {$value}");
      }
    }
    echo json_encode($this->data);
    exit;
  }

  public function getData(): array {
    return $this->data;
  }

  public function getStatus(): int {
    return $this->status;
  }
}