<?php

namespace App\Domain\Field;

use App\Domain\RepositoryBase;
use App\Exception\InternalServerErrorException;

class FieldRepository extends RepositoryBase {
  protected string $table = 'field';

  /**
   * @throws InternalServerErrorException
   */
  public function insert(FieldEntity $entity): FieldEntity {
    $stmt = $this->db->prepare("
      INSERT INTO {$this->table} (user_id, type, content_type_id, label, slug)
        VALUES (:user_id, :type, :content_type_id, :label, :slug)
    ");

    try {
      $stmt->execute([
        ':user_id' => $entity->userId,
        ':type' => $entity->type->value,
        ':content_type_id' => $entity->contentTypeId,
        ':label' => $entity->label,
        ':slug' => $entity->slug,
      ]);
    } catch (\PDOException $e) {
      if ($e->getCode() === '23000') {
        throw new \InvalidArgumentException($e->getMessage());
      }
      throw $e;
    }

    $id = (int)$this->db->lastInsertId();

    // Create dedicated table for this field's data
    $this->createFieldDataTable(
      $entity->slug,
      $id,
      $entity->userId,
      $entity->contentTypeId
    );

    return new FieldEntity(
      id: $id,
      userId: $entity->userId,
      type: $entity->type,
      contentTypeId: $entity->contentTypeId,
      label: $entity->label,
      slug: $entity->slug
    );
  }

  /**
   * @throws InternalServerErrorException
   */
  private function createFieldDataTable(
    string $slug,
    int    $fieldId,
    int    $userId,
    int    $contentTypeId,
  ): void {
    $result = $this->db->exec("
      CREATE TABLE IF NOT EXISTS `field_data_{$slug}` (
        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `field_id` INT NOT NULL DEFAULT '{$fieldId}',
        `user_id` INT NOT NULL DEFAULT '{$userId}',
        `content_type_id` INT NOT NULL DEFAULT '{$contentTypeId}',
        `content_id` INT NOT NULL,
        `value` VARCHAR(1024) NOT NULL,
        INDEX `idx_user_field_content_type` (`user_id`, `field_id`, `content_type_id`)
      );
    ");

    if ($result === FALSE) {
      $errorInfo = $this->db->errorInfo();
      $errorMessage = $errorInfo[2] ?? 'Unknown database error';

      throw new InternalServerErrorException(
        "Error when saving the new field data table. SQL Error: {$errorMessage}"
      );
    }
  }
}