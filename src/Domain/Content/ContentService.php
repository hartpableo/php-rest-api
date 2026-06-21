<?php

namespace App\Domain\Content;

use App\Domain\ContentType\ContentTypeService;
use App\Domain\User\UserService;
use App\Exception\BusinessRuleException;
use App\Utility\Slugify;
use DateTimeImmutable;
use DateTimeZone;

final readonly class ContentService {
  public function __construct(
    private ContentRepository  $repository,
    private UserService        $userService,
    private ContentTypeService $contentTypeService,
  ) {
  }

  public function checkIfExists(
    int $contentId,
    int $userId,
    int $contentTypeId,
  ): bool {
    return $this->repository->checkIfExists([
      'content_id' => $contentId,
      'user_id' => $userId,
      'content_type_id' => $contentTypeId,
    ]);
  }

  public function findAll(
    int   $userId,
    array $args,
    ?int  $offset = NULL,
    ?int  $limit = NULL,
  ): array {
    $result = $this->repository->findAll(
      array_merge($args, ['user_id' => $userId]),
      $offset,
      $limit
    );

    return array_merge([
      'results' => array_map(
        fn($i) => new ContentEntity(
          id: (int)$i['id'],
          label: $i['label'],
          slug: $i['slug'],
          userId: (int)$i['user_id'],
          contentTypeId: (int)$i['content_type_id'],
          createdAt: new \DateTimeImmutable($i['created_at']),
          updatedAt: new DateTimeImmutable($i['updated_at']),
        ),
        $result['data']
      ),
      'hasNextPage' => $result['hasNextPage'],
    ]);
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
    }
    elseif (strlen($label) < 3) {
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

    if (!$this->userService->checkIfExists($userId)) {
      $errors['user'] = 'User does not exist';
    }

    if (!$this->contentTypeService->checkIfExists($userId, $contentTypeId)) {
      $errors['contentType'] = 'Content type does not exist';
    }

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