<?php

namespace App\Domain\User;

final readonly class UserEntity {
  public function __construct(
    public ?int $id,
    public string $email,
    public string $password,
    public bool $verified,
    public \DateTimeImmutable $createdAt,
  ) {}
}