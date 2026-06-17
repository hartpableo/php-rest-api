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
    try {
      $this->createFieldDataTable($entity->slug, $id, $entity->userId);
    } catch (InternalServerErrorException $e) {
      throw new InternalServerErrorException($e->getMessage());
    }

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
    int    $userId
  ): void {
    $stmt = $this->db->exec("
      CREATE TABLE IF NOT EXISTS `field_data_{$slug}` (
        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `field_id` INT NOT NULL DEFAULT '{$fieldId}',
        `user_id` INT NOT NULL DEFAULT '{$userId}',
        `value` VARCHAR(255) NOT NULL,
        INDEX `idx_user_field` (`user_id`, `field_id`)
      );
    ");

    if (empty($stmt)) {
      throw new InternalServerErrorException('Error when saving the new field data table.');
    }
  }
}