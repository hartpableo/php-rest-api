<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Domain\Field\FieldService;
use App\Responder\GetAllResponder;

#[Route(path: '/api/fields', method: 'GET')]
final readonly class GetAllFieldsAction {
  public function __construct(
    private FieldService    $service,
    private GetAllResponder $responder
  ) {
  }

  public function __invoke(
    Request $request,
  ): void {
    $data = $this->service->findAll(
      $request->userId,
      $request->query['args'] ?? [],
      $request->query['offset'] ?? NULL,
      $request->query['limit'] ?? 10
    );
    $message = !empty($data['results'] ?? NULL)
      ? 'Data fetched successfully'
      : 'Empty data result';

    ($this->responder)($data, $message);
  }
}