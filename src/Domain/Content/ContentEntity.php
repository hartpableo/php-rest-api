<?php

namespace App\Domain\Content;

final class ContentEntity {
  public function __construct(
    public ?int               $id,
    public string             $label,
    public string             $slug,
    public int                $userId,
    public int                $contentTypeId,
    public \DateTimeImmutable $createdAt,
    public \DateTimeImmutable $updatedAt,
  ) {
  }
}