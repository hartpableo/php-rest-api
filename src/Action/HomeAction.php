<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Core\Session;
use App\Utility\Redirect;

#[Route(path: '/', method: 'GET')]
final readonly class HomeAction {
  public function __invoke(
    Request $request,
  ): Redirect {
    if (Session::isUserLoggedIn()) {
      return new Redirect('/dashboard');
    }

    return new Redirect('/login');
  }
}