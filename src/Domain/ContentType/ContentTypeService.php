<?php

namespace App\Domain\ContentType;

use App\Exception\BusinessRuleException;
use App\Utility\Slugify;
use DateMalformedStringException as DateMalformedStringExceptionAlias;
use DateTimeZone;

class ContentTypeService {
  public function __construct(
    private ContentTypeRepository $repository,
  ) {
  }

  /**
   * @throws BusinessRuleException
   * @throws DateMalformedStringExceptionAlias
   */
  public function insert(
    string $label,
    int    $userId,
  ): ContentTypeEntity {
    $errors = [];

    if (empty($label)) {
      $errors['label'] = 'Label cannot be empty';
    }

    if (empty($userId)) {
      $errors['userId'] = 'User cannot be empty';
    }

    $slug = Slugify::slugify($label);

    if ($this->repository->checkIfExists($label, $userId, $slug)) {
      $errors['uniqueness'] = 'Content type already exists';
    }

    if (!empty($errors)) {
      throw new BusinessRuleException($errors);
    }

    return $this->repository->insert(
      new ContentTypeEntity(
        id: NULL,
        userId: $userId,
        label: $label,
        slug: $slug,
        createdAt: new \DateTime('now', new DateTimeZone('UTC')),
      )
    );
  }
}