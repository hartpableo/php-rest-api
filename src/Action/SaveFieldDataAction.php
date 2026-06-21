<?php

namespace App\Action;

use App\Core\Request;
use App\Domain\FieldData\FieldDataService;
use App\Exception\BusinessRuleException;
use App\Responder\SaveFieldDataResponder;
use App\Utility\JsonResponse;

final readonly class SaveFieldDataAction {
  public function __construct(
    private FieldDataService       $service,
    private SaveFieldDataResponder $responder,
  ) {
  }

  public function __invoke(
    Request $request,
  ) {
    try {
      $newEntity = $this->service->insert(
        $request->input('fieldId'),
        $request->input('userId'),
        $request->input('contentTypeId'),
        $request->input('contentId'),
        $request->input('value'),
      );
    } catch (BusinessRuleException $e) {
      return new JsonResponse([
        'ok' => FALSE,
        'errors' => $e->getErrors()
      ], 400);
    }

    ($this->responder)($newEntity);
  }
}