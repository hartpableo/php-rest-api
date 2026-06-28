<?php

namespace App\Responder;

use App\Utility\Redirect;

final class CreateApiKeyResponder {
  public function __invoke(): Redirect {
    return new Redirect('/dashboard');
  }
}