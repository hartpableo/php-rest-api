<?php

namespace App\Domain\ContentType;

use App\Exception\BusinessRuleException;
use App\Utility\Slugify;
use DateMalformedStringException as DateMalformedStringExceptionAlias;
use DateTimeZone;

final readonly class ContentTypeService {
  public function __construct(
    private ContentTypeRepository $repository,
  ) {
  }

  public function findAll(
    int   $userId,
    array $args,
    ?int  $offset = NULL,
    ?int  $limit = NULL,
  ): array {
    // TODO: Validate user

    $result = $this->repository->findAll(
      array_merge($args, ['user_id' => $userId]),
      $offset,
      $limit
    );

    return array_merge([
      'results' => array_map(
        fn($i) => new ContentTypeEntity(
          id: (int)$i['id'],
          userId: $i['user_id'],
          label: $i['label'],
          slug: $i['slug'],
          createdAt: new \DateTimeImmutable($i['created_at']),
        ),
        $result['data']
      ),
      'hasNextPage' => $result['hasNextPage'],
    ]);
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
      $errors['label'][] = 'Label cannot be empty';
    }

    if (empty($userId)) {
      $errors['userId'][] = 'User cannot be empty';
    }

    // TODO: Validate that the user exists

    $slug = Slugify::slugify($label);

    if ($this->repository->checkIfExists([
      'label' => $label,
      'slug' => $slug,
      'user_id' => $userId,
    ])) {
      $errors['general'][] = 'Content type already exists';
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
        createdAt: new \DateTimeImmutable('now', new DateTimeZone('UTC')),
      )
    );
  }
}