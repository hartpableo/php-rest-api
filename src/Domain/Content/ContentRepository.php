<?php

namespace App\Domain\Content;

use App\Domain\RepositoryBase;

final class ContentRepository extends RepositoryBase {
  protected string $table = 'content';

  public function insert(ContentEntity $entity): ContentEntity {
    $stmt = $this->db->prepare("
      INSERT INTO {$this->table} (user_id, content_type_id, label, slug, created_at, updated_at)
        VALUES (:user_id, :content_type_id, :label, :slug, :created_at, :updated_at)
    ");
    $stmt->execute([
      ':user_id' => $entity->userId,
      ':content_type_id' => $entity->contentTypeId,
      ':label' => $entity->label,
      ':slug' => $entity->slug,
      ':created_at' => $entity->createdAt->format("Y-m-d H:i:s"),
      ':updated_at' => $entity->updatedAt->format("Y-m-d H:i:s"),
    ]);

    return new ContentEntity(
      id: (int)$this->db->lastInsertId(),
      label: $entity->label,
      slug: $entity->slug,
      userId: $entity->userId,
      contentTypeId: $entity->contentTypeId,
      createdAt: $entity->createdAt,
      updatedAt: $entity->updatedAt,
    );
  }
}