<?php

namespace App\Controller;

use App\Attributes\Route;
use App\Helper\JsonResponse;

class EntityController {
  #[Route('/', 'GET')]
  public function create(): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Entity created'
    ]);
  }
}