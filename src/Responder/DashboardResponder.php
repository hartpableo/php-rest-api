<?php

namespace App\Responder;

use App\Utility\Template;

final class DashboardResponder {
  public function __invoke(
    string $csrfToken,
    array  $keys = []
  ): Template {
    return new Template('dashboard', [
      'csrfToken' => $csrfToken
    ]);
  }
}