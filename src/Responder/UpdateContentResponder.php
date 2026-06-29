<?php

namespace App\Responder;

use App\Domain\Content\ContentEntity;
use App\Utility\JsonResponse;

final class UpdateContentResponder {
  public function __invoke(ContentEntity $entity): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Content has been created.',
      'entity' => $entity,
    ]);
  }
}