<?php

namespace App\Responder;

use App\Utility\Template;

final class DashboardResponder {
  public function __invoke(
    string $csrfToken,
    array  $apiKeys = []
  ): Template {
    return new Template('dashboard', [
      'csrfToken' => $csrfToken,
      'apiKeys' => $apiKeys,
    ]);
  }
}