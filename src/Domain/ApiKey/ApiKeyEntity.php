<?php

namespace App\Domain\ApiKey;

use DateTimeImmutable;

final readonly class ApiKeyEntity {
  public function __construct(
    public ?int              $id,
    public int               $userId,
    public string            $apiToken,
    public string            $siteHost,
    public DateTimeImmutable $createdAt,
  ) {
  }
}