<?php

namespace App\Responder;

use App\Utility\JsonResponse;

final class GetAllResponder {
  public function __invoke(
    array $data,
    string $message
  ): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => $message,
      'data' => $data
    ]);
  }
}