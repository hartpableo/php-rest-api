<?php

namespace App\Action;


use App\Attributes\Route;
use App\Core\Request;
use App\Domain\User\UserService;
use App\Exception\BusinessRuleException;
use App\Responder\CreateUserResponder;
use App\Utility\JsonResponse;

#[Route(path: '/api/users/create', method: 'POST')]
final readonly class CreateUserAction {
  public function __construct(
    private UserService         $service,
    private CreateUserResponder $responder
  ) {
  }

  public function __invoke(
    Request $request,
  ): JsonResponse {
    $newEntity = $this->service->insert(
      $request->input('email'),
      $request->input('password'),
      $request->input('verified', FALSE),
    );

    return ($this->responder)($newEntity);
  }
}