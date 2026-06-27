<?php

namespace App\Responder;

use App\Utility\Template;

final class UserLoginResponder {
  public function __invoke(
    string $csrfToken,
  ): Template {
    return new Template('login', [
      'csrfToken' => $csrfToken
    ]);
  }
}