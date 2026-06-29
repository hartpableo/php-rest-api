<?php

namespace App\Action;

use App\Attributes\Route;
use App\Responder\TestResponder;
use App\Utility\JsonResponse;

#[Route(path: '/api/test')]
final readonly class TestAction {
  public function __construct(
    private TestResponder $responder
  ) {}

  public function __invoke(): JsonResponse {
    return ($this->responder)();
  }
}