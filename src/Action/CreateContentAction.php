<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Domain\Content\ContentService;
use App\Exception\BusinessRuleException;
use App\Responder\CreateContentResponder;
use App\Utility\JsonResponse;

#[Route(path: '/api/content/create', method: 'POST')]
final readonly class CreateContentAction {
  public function __construct(
    private ContentService $service,
    private CreateContentResponder $responder,
  ) {
  }

  public function __invoke(
    Request $request
  ): JsonResponse {
    $newEntity = $this->service->insert(
      $request->input('label'),
      $request->userId,
      $request->input('contentTypeId'),
    );

    return ($this->responder)($newEntity);
  }
}