<?php

namespace App\Responder;

use App\Utility\JsonResponse;

class TestResponder {
  public function __invoke() {
    new JsonResponse([
      'ok' => TRUE,
      'message' => 'Hello from ' . __CLASS__
    ]);
  }
}