<?php

namespace App\Domain\User;

use App\Exception\BusinessRuleException;
use DateTimeZone;

final readonly class UserService {
  public function __construct(
    private UserRepository $repository,
  ) {
  }

  public function checkIfExists(int $userId): bool {
    return $this->repository->checkIfExists([
      'id' => $userId,
    ]);
  }

  /**
   * @throws \DateMalformedStringException
   * @throws BusinessRuleException
   */
  public function insert(
    string       $email,
    string       $password,
    bool         $verified = FALSE,
  ): UserEntity {
    $errors = [];

    if (empty($email)) {
      $errors['email'][] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'][] = 'Invalid email format';
    }

    if (empty($password)) {
      $errors['password'][] = 'Password is required';
    } elseif (strlen($password) < 7) {
      $errors['password'][] = 'Password must be at least 7 characters';
    }

    if ($this->repository->checkIfExists([
      'email' => $email,
    ])) {
      $errors['email'][] = sprintf('User with email %s is already registered', $email);
    }

    if (!empty($errors)) {
      throw new BusinessRuleException($errors);
    }

    return $this->repository->insert(
      new UserEntity(
        id: NULL,
        email: $email,
        password: $password,
        verified: $verified,
        createdAt: new \DateTimeImmutable('now', new DateTimeZone('UTC')),
      )
    );
  }
}