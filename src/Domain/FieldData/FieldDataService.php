<?php

namespace App\Domain\FieldData;

use App\Domain\Content\ContentService;
use App\Domain\Field\FieldService;
use App\Exception\BusinessRuleException;

final readonly class FieldDataService {
  public function __construct(
    private FieldDataRepository $fieldDataRepository,
    private FieldService        $fieldService,
    private ContentService      $contentService,
  ) {
  }

  /**
   * @throws BusinessRuleException
   */
  public function insert(
    int                   $userId,
    int                   $fieldId,
    int                   $contentTypeId,
    int                   $contentId,
    string|int|float|bool $value
  ): FieldDataEntity {
    $errors = [];

    if (!$this->fieldService->findByFieldUserContentType(
      $fieldId,
      $userId,
      $contentTypeId
    )) {
      $errors['field'] = 'Field cannot be found.';
    }

    if (!$this->contentService->checkIfExists(
      $contentId,
      $userId,
      $contentTypeId
    )) {
      $errors['content'] = 'Content cannot be found.';
    }

    if (!empty($errors)) {
      throw new BusinessRuleException($errors);
    }

    return $this->fieldDataRepository->insert(
      new FieldDataEntity(
        id: NULL,
        fieldId: $fieldId,
        userId: $userId,
        contentTypeId: $contentTypeId,
        contentId: $contentId,
        value: $value,
      )
    );
  }
}