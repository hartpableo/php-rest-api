<?php

namespace App\Responder;

use App\Domain\Content\ContentEntity;
use App\Utility\JsonResponse;

class CreateContentResponder {
  public function __invoke(ContentEntity $entity): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => sprintf('Content %s has been created.', $entity->label),
      'entity' => $entity,
    ]);
  }
}