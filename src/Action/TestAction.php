<?php

namespace App\Action;

use App\Attributes\Route;
use App\Responder\TestResponder;

#[Route(path: '/api/test')]
final readonly class TestAction {
  public function __construct(
    private TestResponder $responder
  ) {}

  public function __invoke() {
    ($this->responder)();
  }
}