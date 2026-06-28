<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\CsrfToken;
use App\Core\Middleware;
use App\Core\Request;
use App\Core\Session;
use App\Domain\ApiKey\ApiKeyService;
use App\Exception\UnauthorizedException;
use App\Responder\DashboardResponder;

#[Route(
  path: '/dashboard',
  method: ['GET', 'POST'],
  middlewares: [
    Middleware::IsLoggedIn => '/login',
  ]
)]
final readonly class DashboardAction {
  public function __construct(
    private ApiKeyService      $service,
    private DashboardResponder $responder,
    private CsrfToken          $csrfToken,
  ) {
  }

  public function __invoke(
    Request $request,
  ): void {
    // Handle POST
    if ($request->method === 'POST') {
      if (!$this->csrfToken->validate()) {
        throw new UnauthorizedException();
      }

      echo '<pre>';
      print_r($request);
      echo '</pre>';
      exit;
    }

    $apiKeys = $this->service->getKeysByUserId(
      Session::getCurrentUser()['id']
    );

    // Handle GET
    ($this->responder)($this->csrfToken->get(), $apiKeys);
  }
}