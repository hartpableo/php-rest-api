<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\CsrfToken;
use App\Core\Request;
use App\Domain\ApiKey\ApiKeyService;
use App\Exception\MethodNotAllowedException;
use App\Responder\ViewDashboardResponder;
use App\Utility\Template;

#[Route(path: '/', method: ['GET', 'POST'])]
final readonly class ViewDashboardAction {
  public function __construct(
    private ApiKeyService          $service,
    private ViewDashboardResponder $responder,
    private CsrfToken              $csrfToken,
  ) {
  }

  public function __invoke(
    Request $request,
  ) {
    // Handle POST
    if ($request->method === 'POST') {
      if (!$this->csrfToken->validate()) {
        throw new MethodNotAllowedException();
      }

      // TODO: Authenticate -> Replace form with "generate new api key" -> Shows keys in sidebar
//      $keys = $this->service
      echo 'hey';
    }

    // Handle GET
    ($this->responder)($this->csrfToken->get());
  }
}