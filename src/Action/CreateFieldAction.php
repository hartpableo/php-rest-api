<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Domain\Field\FieldService;
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
  ): void {
    $newEntity = $this->service->insert(
      $request->input('userId'), // TODO: Validate using bearer token/api key
      $request->input('contentTypeId'),
      $request->input('label'),
      $request->input('type')
    );

    ($this->responder)($newEntity);
  }
}