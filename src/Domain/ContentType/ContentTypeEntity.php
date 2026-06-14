<?php

namespace App\Domain\ContentType;

final readonly class ContentTypeEntity {
  public function __construct(
    public ?int $id,
    public int $userId,
    public string $label,
    public string $slug,
    public \DateTimeImmutable $createdAt,
  ) {
  }
}