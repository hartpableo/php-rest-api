<?php

namespace App\Responder;

use App\Utility\Template;

final class UserRegisterResponder {
  public function __invoke(
    string $csrfToken,
    array  $keys = []
  ): Template {
    return new Template('register', [
      'csrfToken' => $csrfToken
    ]);
  }
}