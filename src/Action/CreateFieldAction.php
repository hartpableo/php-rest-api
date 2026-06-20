<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Auth;
use App\Core\Request;
use App\Domain\Field\FieldService;
use App\Enum\FieldTypeEnum;
use App\Exception\BusinessRuleException;
use App\Exception\InternalServerErrorException;
use App\Responder\CreateFieldResponder;
use App\Utility\JsonResponse;

#[Route(path: '/api/fields/create', method: 'POST')]
final readonly class CreateFieldAction {
  public function __construct(
    private FieldService         $service,
    private CreateFieldResponder $responder
  ) {
  }

  /**
   * @throws BusinessRuleException
   * @throws InternalServerErrorException
   */
  public function __invoke(
    Request $request,
  ): JsonResponse {
    try {
      $newEntity = $this->service->insert(
        $request->input('userId'),
        $request->input('contentTypeId'),
        $request->input('label'),
        FieldTypeEnum::tryFrom($request->input('type')),
      );
    } catch (BusinessRuleException $e) {
      return new JsonResponse($e->getErrors(), 400);
    }

    return ($this->responder)($newEntity);
  }
}