<?php

namespace App\Domain\FieldData;

use App\Domain\Field\FieldService;
use App\Exception\BusinessRuleException;

final readonly class FieldDataService {
  public function __construct(
    private FieldDataRepository $fieldDataRepository,
    private FieldService $fieldService,
  ) {
  }

  /**
   * @throws BusinessRuleException
   */
  public function insert(
    int                   $userId,
    int                   $fieldId,
    string|int|float|bool $value
  ): FieldDataEntity {
    $errors = [];

    if (!$this->fieldService->findByFieldUser($fieldId, $userId)) {
      $errors['field'] = 'Field cannot be found.';
    }

    if (!empty($errors)) {
      throw new BusinessRuleException($errors);
    }

    return $this->fieldDataRepository->insert(
      new FieldDataEntity(
        id: NULL,
        fieldId: $fieldId,
        userId: $userId,
        value: $value,
      )
    );
  }
}