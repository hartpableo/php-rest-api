<?php

namespace App\Action;

use App\Core\Request;
use App\Domain\FieldData\FieldDataService;
use App\Exception\BusinessRuleException;
use App\Responder\SaveFieldDataResponder;
use App\Utility\JsonResponse;

use App\Attributes\Route;

#[Route(path: '/api/fields/save-data', method: 'POST')]
final readonly class SaveFieldDataAction {
  public function __construct(
    private FieldDataService       $service,
    private SaveFieldDataResponder $responder,
  ) {
  }

  public function __invoke(
    Request $request,
  ): JsonResponse {
    $newEntity = $this->service->insert(
      $request->userId,
      (int)$request->input('fieldId'),
      (int)$request->input('contentTypeId'),
      (int)$request->input('contentId'),
      $request->input('value'),
    );

    return ($this->responder)($newEntity);
  }
}