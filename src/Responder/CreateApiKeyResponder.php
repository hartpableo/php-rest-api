<?php

namespace App\Responder;

final class CreateApiKeyResponder {
  public function __invoke(): Redirect {
    return new Redirect('/dashboard');
  }
}