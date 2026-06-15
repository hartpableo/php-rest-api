<?php

namespace App\Responder;

use App\Domain\Field\FieldEntity;
use App\Utility\JsonResponse;

class CreateFieldResponder {
  public function __invoke(FieldEntity $entity): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Field has been created',
      'entity' => $entity
    ]);
  }
}