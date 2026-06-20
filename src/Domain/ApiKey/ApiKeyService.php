<?php

namespace App\Domain\ApiKey;

final readonly class ApiKeyService {
  public function __construct(
    private ApiKeyRepository $repository,
  ) {}

  public function checkApiUser(
    string $key,
    int $userId
  ): bool {
    return $this->repository->checkIfExists([
      'user_id' => $userId,
      'key' => $key,
    ]);
  }
}