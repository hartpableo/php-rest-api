<?php

namespace App\Domain\User;

use App\Enum\UserRoleEnum;
use App\Exception\BusinessRuleException;
use DateTimeZone;

final readonly class UserService {
  public function __construct(
    private UserRepository $repository,
  ) {
  }

  /**
   * @throws \DateMalformedStringException
   * @throws BusinessRuleException
   */
  public function insert(
    string       $name,
    string       $email,
    string       $password,
    UserRoleEnum $role = UserRoleEnum::User,
    bool         $verified = FALSE,
  ): UserEntity {
    $errors = [];

    if (empty($name)) {
      $errors['name'][] = 'Name is required';
    }

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

    if (empty($role)) {
      $errors['role'][] = 'Role is required';
    } elseif (empty(UserRoleEnum::tryFrom($role->value))) {
      $errors['role'][] = 'Role is not valid';
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
        name: $name,
        email: $email,
        password: $password,
        role: $role,
        verified: $verified,
        deactivated: FALSE,
        createdAt: new \DateTimeImmutable('now', new DateTimeZone('UTC')),
      )
    );
  }
}