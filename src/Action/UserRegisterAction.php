<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\CsrfToken;
use App\Core\Request;
use App\Domain\User\UserService;
use App\Exception\UnauthorizedException;
use App\Responder\UserRegisterResponder;

#[Route(path: '/', method: ['GET', 'POST'])]
final readonly class UserRegisterAction {
  public function __construct(
    private UserSErvice $service,
    private UserRegisterResponder $responder,
    private CsrfToken $csrfToken,
  ) {
  }

  public function __invoke(
    Request $request,
  ) {
    // Handle POST
    if ($request->method === 'POST') {
      if (!$this->csrfToken->validate()) {
        throw new UnauthorizedException();
      }

//      $insertNewUser = $this->service->insert(
//
//      );
    }

    // Handle GET
    ($this->responder)($this->csrfToken->get());
  }
}