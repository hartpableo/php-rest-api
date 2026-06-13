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
    // Receive the request body

    // Fields: entity_name, entity_type: node, tenant_id

    // Save (EntityRepository)

    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Entity created',
      'data' => $request
    ]);
  }
}