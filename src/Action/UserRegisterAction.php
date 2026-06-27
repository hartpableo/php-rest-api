<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\CsrfToken;
use App\Core\Request;
use App\Domain\User\UserService;
use App\Exception\BusinessRuleException;
use App\Exception\UnauthorizedException;
use App\Responder\UserRegisterResponder;
use App\Utility\Redirect;

#[Route(path: '/register', method: ['GET', 'POST'])]
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

      try {
        $this->service->insert(
          $request->input('email'),
          $request->input('password')
        );

        return new Redirect('/login');
      } catch (BusinessRuleException|\DateMalformedStringException $e) {
        // TODO: Handle errors
      }
    }

    // Handle GET
    ($this->responder)($this->csrfToken->get());
  }
}