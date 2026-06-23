<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Responder\ViewDashboardResponder;

#[Route(path: '/', method: ['GET', 'POST'])]
final readonly class ViewDashboardAction {
  public function __construct(
    ViewDashboardResponder $responder,
  ) {
  }

  public function __invoke(
    Request $request,
  ) {
    if ($request->method === 'POST') {
      // Handle POST
    }

    // Handle GET
  }
}