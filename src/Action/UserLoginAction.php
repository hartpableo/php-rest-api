<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\CsrfToken;
use App\Core\Request;
use App\Domain\User\UserService;
use App\Exception\BusinessRuleException;
use App\Exception\UnauthorizedException;
use App\Responder\UserLoginResponder;
use App\Utility\Redirect;

#[Route(path: '/login', method: ['GET', 'POST'])]
final readonly class UserLoginAction {
  public function __construct(
    private UserService $service,
    private UserLoginResponder $responder,
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
        $user = $this->service->authenticate(
          $request->input('email'),
          $request->input('password')
        );

        // TODO: Flash message

        return new Redirect('/dashboard');
      } catch (BusinessRuleException|\DateMalformedStringException $e) {
        // TODO: Handle errors
      }
    }

    // Handle GET
    ($this->responder)($this->csrfToken->get());
  }
}