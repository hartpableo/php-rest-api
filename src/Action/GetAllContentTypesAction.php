<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Domain\Content\ContentService;
use App\Domain\ContentType\ContentTypeService;
use App\Domain\Field\FieldService;
use App\Responder\GetAllResponder;

#[Route(path: '/api/content-types', method: 'GET')]
final readonly class GetAllContentTypesAction {
  public function __construct(
    private ContentTypeService $service,
    private GetAllResponder    $responder
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