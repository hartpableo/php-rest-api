<?php

namespace App\Domain\FieldData;

use AllowDynamicProperties;
use App\Domain\RepositoryBase;

final class FieldDataRepository extends RepositoryBase {
  public function __construct(string $table) {
    parent::__construct();
    $this->table = $table;
  }

  public function insert(FieldDataEntity $entity): FieldDataEntity {
    $stmt = $this->db->prepare("
      INSERT INTO {$this->table} (field_id, user_id, value)
        VALUES (:field_id, :user_id, :value)
    ");

    try {
      $stmt->execute([
        ':field_id' => $entity->fieldId,
        ':user_id' => $entity->userId,
        ':value' => $entity->value,
      ]);
    } catch (\PDOException $e) {
      if ($e->getCode() === '23000') {
        throw new \InvalidArgumentException($e->getMessage());
      }
      throw $e;
    }

    return new FieldDataEntity(
      id: (int)$this->db->lastInsertId(),
      fieldId: $entity->fieldId,
      userId: $entity->userId,
      value: $entity->value
    );
  }
}