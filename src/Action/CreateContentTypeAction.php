<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Domain\ContentType\ContentTypeService;
use App\Exception\BusinessRuleException;
use App\Responder\CreateContentTypeResponder;
use App\Utility\JsonResponse;

#[Route(path: '/api/entities/create', method: 'POST')]
final readonly class CreateContentTypeAction {
  public function __construct(
    private ContentTypeService         $service,
    private CreateContentTypeResponder $responder,
  ) {
  }

  public function __invoke(
    Request $request
  ) {
    try {
      $newEntity = $this->service->insert(
        $request->input('label'),
        (int)$request->input('userId'),
      );
    } catch (BusinessRuleException|\DateMalformedStringException $e) {
      return new JsonResponse($e->getErrors(), 400);
    }

    ($this->responder)($newEntity);
  }
}