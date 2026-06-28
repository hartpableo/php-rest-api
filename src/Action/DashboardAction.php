<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\CsrfToken;
use App\Core\Request;
use App\Domain\ApiKey\ApiKeyService;
use App\Exception\UnauthorizedException;
use App\Responder\DashboardResponder;

#[Route(path: '/dashboard', method: ['GET', 'POST'])]
final readonly class DashboardAction {
  public function __construct(
    private ApiKeyService      $service,
    private DashboardResponder $responder,
    private CsrfToken          $csrfToken,
  ) {
  }

  public function __invoke(
    Request $request,
  ) {
    // Handle POST
    if ($request->method === 'POST') {
      if (!$this->csrfToken->validate()) {
        throw new UnauthorizedException();
      }

      echo 'hey';
    }

    // TODO: Refactor Auth to cross-check the api token with the user id
    $apiKeys = $this->service->getKeysByUserId($request->userId);

    // Handle GET
    ($this->responder)($this->csrfToken->get(), $apiKeys);
  }
}