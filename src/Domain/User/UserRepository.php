<?php

namespace App\Domain\User;

use App\Domain\RepositoryBase;
use App\Enum\UserRoleEnum;

final class UserRepository extends RepositoryBase {
  protected string $table = 'user';

  public function insert(UserEntity $entity): UserEntity {
    $stmt = $this->db->prepare("
      INSERT INTO {$this->table} (name, email, password, role, created_at)
        VALUES (:name, :email, :password, :role, :created_at)
    ");

    try {
      $stmt->execute([
        ':name' => $entity->name,
        ':email' => $entity->email,
        ':password' => password_hash($entity->password, PASSWORD_DEFAULT),
        ':role' => $entity->role->value,
        ':created_at' => new \DateTimeImmutable()->format('Y-m-d H:i:s'),
      ]);
    } catch (\PDOException $e) {
      if ($e->getCode() === '23000') {
        throw new \InvalidArgumentException($e->getMessage());
      }
      throw $e;
    }

    return new UserEntity(
      id: (int)$this->db->lastInsertId(),
      name: $entity->name,
      email: $entity->email,
      password: $entity->password,
      role: $entity->role,
      verified: $entity->verified,
      deactivated: $entity->deactivated,
      createdAt: $entity->createdAt,
    );
  }
}