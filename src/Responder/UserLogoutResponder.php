<?php

namespace App\Responder;

use App\Utility\Redirect;

final class UserLogoutResponder {
  public function __invoke(): Redirect {
    return new Redirect('/login');
  }
}