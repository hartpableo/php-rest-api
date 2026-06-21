<?php

namespace App\Domain\FieldData;

final readonly class FieldDataEntity {
  public function __construct(
    public ?int                  $id,
    public int                   $fieldId,
    public int                   $userId,
    public string|int|bool|float $value,
  ) {
  }
}