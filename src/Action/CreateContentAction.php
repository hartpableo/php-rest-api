<?php

namespace App\Action;

use App\Core\Request;
use App\Domain\Content\ContentService;
use App\Exception\BusinessRuleException;
use App\Responder\CreateContentResponder;
use App\Utility\JsonResponse;

final readonly class CreateContentAction {
  public function __construct(
    private ContentService $service,
    private CreateContentResponder $responder,
  ) {
  }

  public function __invoke(
    Request $request
  ) {
    try {
      $newEntity = $this->service->insert(
        $request->input('label'),
        $request->input('userId'),
        $request->input('contentTypeId'),
      );
    } catch (BusinessRuleException|\DateMalformedStringException $e) {
      return new JsonResponse([
        'ok' => FALSE,
        'errors' => $e->getMessage(),
      ], 400);
    }

    ($this->responder)($newEntity);
  }
}