<?php

namespace App\Controller;

use App\Attributes\Route;
use App\Helper\JsonResponse;

class EntityController {
  #[Route('/api/entities/create', 'POST')]
  public function create(): JsonResponse {


    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Entity created'
    ]);
  }
}