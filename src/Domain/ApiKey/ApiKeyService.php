<?php

namespace App\Domain\ApiKey;

use App\Domain\User\UserService;

final readonly class ApiKeyService {
  public function __construct(
    private ApiKeyRepository $apiKeyRepository,
    private UserService      $userService,
  ) {
  }

  public function checkApiUser(
    string $key,
    string $email
  ): mixed {
    $user = $this->userService->findBy([
      'email' => $email,
    ]);

    if (empty($user)) {
      return NULL;
    }

    $exists = $this->apiKeyRepository->checkIfExists([
      'user_id' => $user['id'],
      'key' => $key,
    ]);

    if (empty($exists)) {
      return NULL;
    }

    return $user;
  }

  public function findBy(array $args) {
    return $this->apiKeyRepository->findBy($args);
  }

  public function getKeysByUserId(int $userId): array {
    return $this->apiKeyRepository->findAll([
      'user_id' => $userId,
    ]);
  }
}