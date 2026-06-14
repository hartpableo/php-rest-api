<?php

namespace App\Domain\ContentType;

use App\Domain\RepositoryBase;

final class ContentTypeRepository extends RepositoryBase {
  protected string $table = 'content_type';

  public function checkIfExists(
    int    $userId,
    string $label,
    string $slug
  ): bool {
    $stmt = $this->db->prepare("
      SELECT 1 FROM {$this->table} 
        WHERE user_id = :user_id 
          AND (label = :label OR slug = :slug)
        LIMIT 1
    ");
    $stmt->execute([
      ':user_id' => $userId,
      ':label' => $label,
      ':slug' => $slug,
    ]);

    return $stmt->fetch() !== FALSE;
  }

  public function insert(ContentTypeEntity $entity): ContentTypeEntity {
    $stmt = $this->db->prepare("
      INSERT INTO {$this->table} (user_id, label, slug, created_at)
        VALUES (:user_id, :label, :slug, :created_at)
    ");

    try {
      $stmt->execute([
        ':user_id' => $entity->userId,
        ':label' => $entity->label,
        ':slug' => $entity->slug,
        ':created_at' => $entity->createdAt->format('Y-m-d H:i:s'),
      ]);
    } catch (\PDOException $e) {
      if ($e->getCode() === '23000') {
        throw new \InvalidArgumentException($e->getMessage());
      }
      throw $e;
    }

    return new ContentTypeEntity(
      id: (int)$this->db->lastInsertId(),
      userId: $entity->userId,
      label: $entity->label,
      slug: $entity->slug,
      createdAt: $entity->createdAt,
    );
  }
}