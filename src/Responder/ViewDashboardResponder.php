<?php

namespace App\Responder;

use App\Utility\Template;

class ViewDashboardResponder {
  public function __invoke(): Template {
    return new Template(
      'dashboard',
      []
    );
  }
}