<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Session;
use App\Responder\UserLogoutResponder;

#[Route(path: '/logout', method: 'GET')]
final readonly class UserLogoutAction {
  public function __construct(
    private UserLogoutResponder $responder,
  ) {}

  public function __invoke(): void {
    Session::logout();

    // TODO: Add flash message

    ($this->responder)();
  }
}