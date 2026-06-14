<?php

namespace App\Action;


use App\Attributes\Route;
use App\Core\Request;
use App\Domain\User\UserService;
use App\Enum\UserRoleEnum;
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
  ) {
    try {
      $newEntity = $this->service->insert(
        $request->input('name'),
        $request->input('email'),
        $request->input('password'),
        UserRoleEnum::tryFrom($request->input('role')),
        $request->input('verified', FALSE),
      );
    } catch (BusinessRuleException|\DateMalformedStringException $e) {
      return new JsonResponse([
        'ok' => FALSE,
        'errors' => $e->getErrors()
      ], 400);
    }

    ($this->responder)($newEntity);
  }
}