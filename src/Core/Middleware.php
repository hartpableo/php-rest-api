<?php

namespace App\Core;

use App\Utility\Redirect;

class Middleware {
  public const string IsLoggedIn = 'isLoggedIn';

  public function isLoggedIn(string $target = '/login'): true|Redirect {
    // TODO: Set flash message
    if (!isset($_SESSION['user'])) {
      return new Redirect($target);
    }

    return TRUE;
  }
}