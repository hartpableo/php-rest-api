<?php

namespace App\Domain\Field;

use App\Enum\FieldTypeEnum;

final readonly class FieldEntity {
  public function __construct(
    public ?int          $id,
    public int           $userId,
    public FieldTypeEnum $type,
    public int           $contentTypeId,
    public string        $label,
    public string        $slug,
  ) {
  }
}