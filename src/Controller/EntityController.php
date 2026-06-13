<?php

namespace App\Controller;

use App\Attributes\Route;
use App\Core\Request;
use App\Utility\JsonResponse;

class EntityController {
  #[Route('/api/entities/create', 'POST')]
  public function create(
    Request $request,
  ): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Entity created',
      'data' => $request
    ]);
  }
}