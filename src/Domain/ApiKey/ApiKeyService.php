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
    int    $userId
  ): bool {
    if (!$this->userService->checkIfExists($userId)) {
      return FALSE;
    }

    return $this->apiKeyRepository->checkIfExists([
      'user_id' => $userId,
      'key' => $key,
    ]);
  }

  public function getUserIdByKey(string $key): ?int {
    $apiKey = $this->apiKeyRepository->findBy([
      'key' => $key,
    ]);
    return $apiKey ? (int)$apiKey['user_id'] : null;
  }

  public function getKeysByUserId(int $userId): array {
    return $this->apiKeyRepository->findAll([
      'user_id' => $userId,
    ]);
  }
}