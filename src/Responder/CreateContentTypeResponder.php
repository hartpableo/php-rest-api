<?php

namespace App\Responder;

use App\Domain\ContentType\ContentTypeEntity;
use App\Utility\JsonResponse;

final class CreateContentTypeResponder {
  public function __invoke(ContentTypeEntity $entity): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'Entity has been created.',
      'entity' => $entity
    ]);
  }
}