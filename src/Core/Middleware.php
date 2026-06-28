<?php

namespace App\Core;

use App\Utility\Redirect;

class Middleware {
  public const string IsLoggedIn = 'isLoggedIn';
  public const string IsAnonymous = 'isAnonymous';

  public function isLoggedIn(string $target = '/login'): true|Redirect {
    // TODO: Set flash message
    if (!isset($_SESSION['user'])) {
      return new Redirect($target);
    }

    return TRUE;
  }

  public function isAnonymous(): bool|Redirect {
    if (isset($_SESSION['user'])) {
      return new Redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    return TRUE;
  }
}