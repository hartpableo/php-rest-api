<?php

namespace App\Domain\ApiKey;

use App\Domain\RepositoryBase;

final class ApiKeyRepository extends RepositoryBase {
  protected string $table = 'api_key';

  public function insert(ApiKeyEntity $entity): ApiKeyEntity {
    $stmt = $this->db->prepare("
      INSERT INTO {$this->table} (user_id, site_host, api_token, created_at)
        VALUES (:user_id, :site_host, :api_token, :created_at)
    ");
    $stmt->execute([
      ':user_id' => $entity->userId,
      ':site_host' => $entity->siteHost,
      ':api_token' => $entity->apiToken,
      ':created_at' => $entity->createdAt->format("Y-m-d H:i:s"),
    ]);

    return new ApiKeyEntity(
      id: (int)$this->db->lastInsertId(),
      userId: $entity->userId,
      apiToken: $entity->apiToken,
      siteHost: $entity->siteHost,
      createdAt: $entity->createdAt,
    );
  }
}