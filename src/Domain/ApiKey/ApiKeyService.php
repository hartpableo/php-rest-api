<?php

namespace App\Domain\ApiKey;

use App\Core\Session;
use App\Domain\User\UserService;
use App\Exception\BusinessRuleException;
use DateTimeImmutable;
use DateTimeZone;
use Random\RandomException;

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
      'api_token' => $key,
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

  /**
   * @throws \DateMalformedStringException
   * @throws BusinessRuleException
   */
  public function insert(
    string $host,
  ) {
    if (!empty($this->apiKeyRepository->findBy([
      'site_host' => $host,
    ]))) {
      throw new BusinessRuleException([
        'host' => 'That host already exists.'
      ]);
    }

    try {
      $token = bin2hex(random_bytes(32));
      while ($this->apiKeyRepository->checkIfExists([
        'api_token' => $token,
      ])) {
        $token = bin2hex(random_bytes(32));
      }

      return $this->apiKeyRepository->insert(
        new ApiKeyEntity(
          id: NULL,
          userId: Session::getCurrentUser()['id'],
          apiToken: $token,
          siteHost: $host,
          createdAt: new DateTimeImmutable('now', new DateTimeZone('UTC')),
        )
      );
    } catch (RandomException $e) {
      // TODO: Improve exception
    }
  }
}