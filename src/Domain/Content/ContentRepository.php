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

  public function update(
    array $args,
    array $conditions,
  ): ?ContentEntity {
    if (empty($args) || empty($conditions)) {
      return NULL;
    }

    [$setClausesArray, $setBindings] = $this->buildSetClauses($args);
    $setClauses = implode(', ', $setClausesArray);

    [$whereClausesArray, $whereBindings] = $this->buildWhereClauses($conditions);
    $whereClauses = implode(' AND ', $whereClausesArray);

    $stmt = $this->db->prepare("
      UPDATE {$this->table}
      SET {$setClauses}
      WHERE {$whereClauses}
    ");

    $success = $stmt->execute(array_merge($setBindings, $whereBindings));
    if (!$success) {
      return NULL;
    }

    $row = $this->findBy($conditions);
    if (!$row) {
      return NULL;
    }

    return new ContentEntity(
      id: (int)$row['id'],
      label: $row['label'],
      slug: $row['slug'],
      userId: (int)$row['user_id'],
      contentTypeId: (int)$row['content_type_id'],
      createdAt: \DateTimeImmutable::createFromFormat("Y-m-d H:i:s", $row['created_at']),
      updatedAt: \DateTimeImmutable::createFromFormat("Y-m-d H:i:s", $row['updated_at']),
    );
  }
}