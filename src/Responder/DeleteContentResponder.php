<?php

namespace App\Responder;

use App\Utility\JsonResponse;

final class DeleteContentResponder {
  public function __invoke(
    bool $isDeleted,
    array $data
  ): JsonResponse {
    return new JsonResponse([
      'ok' => $isDeleted,
      'message' => $isDeleted ? 'Content has been deleted' : 'Content deletion failed',
      'data' => $data
    ], $isDeleted ? 200 : 400);
  }
}