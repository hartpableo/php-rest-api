<?php

namespace App\Responder;

use App\Domain\User\UserEntity;
use App\Utility\JsonResponse;

final class CreateUserResponder {
  public function __invoke(UserEntity $entity): JsonResponse {
    return new JsonResponse([
      'ok' => TRUE,
      'message' => 'User has been created.',
      'entity' => $entity
    ]);
  }
}