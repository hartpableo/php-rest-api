<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Domain\ApiKey\ApiKeyService;
use App\Responder\CreateApiKeyResponder;

#[Route(path: '/apikeys/create', method: 'POST')]
final readonly class CreateApiKeyAction {
  public function __construct(
    private ApiKeyService         $service,
    private CreateApiKeyResponder $responder
  ) {
  }

  public function __invoke(
    Request $request
  ): void {
    $newApiKey = $this->service->insert($request->input('host'));

    // TODO: Flash message

    ($this->responder)();
  }
}