<?php

namespace App\Controller;

use App\Attributes\Route;
use App\Utility\JsonResponse;

class EntityController {
  #[Route('/api/entities/create', 'POST')]
  public function create(): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Entity created',
      'data' => ''
    ]);
  }
}