<?php

namespace App\Domain\User;

use App\Enum\UserRoleEnum;

final readonly class UserEntity {
  public function __construct(
    public ?int $id,
    public string $name,
    public string $email,
    public string $password,
    public UserRoleEnum $role,
    public bool $verified,
    public bool $deactivated,
    public \DateTimeImmutable $createdAt,
  ) {}
}