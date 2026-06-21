<?php

namespace App\Responder;

use App\Domain\FieldData\FieldDataEntity;
use App\Utility\JsonResponse;

class SaveFieldDataResponder {
  public function __invoke(FieldDataEntity $entity): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Field has been created',
      'entity' => $entity,
    ]);
  }
}