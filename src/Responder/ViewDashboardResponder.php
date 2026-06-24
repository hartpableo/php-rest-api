<?php

namespace App\Responder;

use App\Utility\Template;

class ViewDashboardResponder {
  public function __invoke(
    string $csrfToken,
    array  $keys = []
  ): Template {
    return new Template('dashboard', [
      'csrfToken' => $csrfToken
    ]);
  }
}