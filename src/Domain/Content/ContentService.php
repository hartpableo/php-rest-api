<?php

namespace App\Domain\Content;

use App\Exception\BusinessRuleException;
use App\Utility\Slugify;
use DateTimeZone;

final readonly class ContentService {
  public function __construct(
    private ContentRepository $repository,
  ) {
  }

  /**
   * @throws \DateMalformedStringException
   * @throws BusinessRuleException
   */
  public function insert(
    string $label,
    int    $userId,
    int    $contentTypeId
  ): ContentEntity {
    $errors = [];

    if (empty($label)) {
      $errors['label'] = 'Label cannot be empty';
    } elseif (strlen($label) < 3) {
      $errors['label'] = 'Label must be at least 3 characters';
    }

    if (empty($contentTypeId)) {
      $errors['contentTypeId'] = 'Content type cannot be empty';
    }

    if (empty($userId)) {
      $errors['userId'] = 'User cannot be empty';
    }

    $slug = Slugify::slugify($label);

    if ($this->repository->checkIfExists([
      'slug' => $slug,
      'user_id' => $slug,
      'content_type_id' => $contentTypeId,
    ])) {
      $errors['slug'] = 'Slug already exists';
    }

    // TODO: Validate existence of other entities involved (content type, user)

    if (!empty($errors)) {
      throw new BusinessRuleException($errors);
    }

    $timeNow = new \DateTimeImmutable('now', new DateTimeZone('UTC'));
    return $this->repository->insert(
      new ContentEntity(
        id: NULL,
        label: $label,
        slug: $slug,
        userId: $userId,
        contentTypeId: $contentTypeId,
        createdAt: $timeNow,
        updatedAt: $timeNow,
      ),
    );
  }
}