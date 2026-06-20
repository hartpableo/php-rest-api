<?php

namespace App\Domain\Field;

use App\Domain\ContentType\ContentTypeRepository;
use App\Domain\User\UserRepository;
use App\Enum\FieldTypeEnum;
use App\Exception\BusinessRuleException;
use App\Exception\InternalServerErrorException;
use App\Utility\Slugify;

final readonly class FieldService {
  public function __construct(
    private FieldRepository       $fieldRepository,
    private UserRepository        $userRepository,
    private ContentTypeRepository $contentTypeRepository,
  ) {
  }

  public function findAll(
    int $userId,
    array $args,
    ?int $offset = NULL,
    ?int $limit = NULL,
  ): array {
    // TODO: Validate user

    $result = $this->fieldRepository->findAll(
      array_merge($args, ['user_id' => $userId]),
      $offset,
      $limit
    );
    return array_map(
      fn($i) => new FieldEntity(
        id: (int)$i['id'],
        userId: $i['user_id'],
        type: FieldTypeEnum::tryFrom($i['type']),
        contentTypeId: (int)$i['content_type_id'],
        label: $i['label'],
        slug: $i['slug'],
      ),
      $result
    );
  }

  /**
   * @throws BusinessRuleException
   * @throws InternalServerErrorException
   */
  public function insert(
    int           $userId,
    int           $contentTypeId,
    string        $label,
    FieldTypeEnum $type,
  ): FieldEntity {
    $errors = [];

    if (empty($userId)) {
      $errors['userId'] = 'User ID is required';
    } elseif (!$this->userRepository->checkIfExists([
      'id' => $userId,
    ])) {
      $errors['userId'] = 'User not found';
    }

    if (empty($contentTypeId)) {
      $errors['contentTypeId'] = 'Content Type ID is required';
    } elseif (!$this->contentTypeRepository->checkIfExists([
      'id' => $contentTypeId,
    ])) {
      $errors['contentTypeId'] = 'Content Type not found';
    }

    if (empty($label)) {
      $errors['label'] = 'Label is required';
    } elseif ($this->fieldRepository->checkIfExists([
      'label' => $label,
      'user_id' => $userId,
      'content_type_id' => $contentTypeId,
    ])) {
      $errors['label'] = 'Field already exists';
    }

    if (FieldTypeEnum::tryFrom($type->value) === NULL) {
      $errors['type'] = 'Field type not found';
    }

    if (!empty($errors)) {
      throw new BusinessRuleException($errors);
    }

    return $this->fieldRepository->insert(
      new FieldEntity(
        id: NULL,
        userId: $userId,
        type: $type,
        contentTypeId: $contentTypeId,
        label: $label,
        slug: Slugify::slugify($label),
      )
    );
  }
}