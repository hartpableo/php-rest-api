<?php

namespace App\Enum;

enum FieldTypeEnum: string {
  case Text = 'text';
  case LongText = 'longtext';
  case Number = 'number';
  case Date = 'date';
  case Time = 'time';
  case DateTime = 'datetime';
  case Email = 'email';
  case EntityReference = 'entity_reference';
}
