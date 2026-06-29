<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\Request;
use App\Domain\Content\ContentService;
use App\Responder\UpdateContentResponder;

#[Route(path: '/api/contents/update', method: ['PATCH', 'PUT'])]
final readonly class UpdateContentAction {
  public function __construct(
    private ContentService         $service,
    private UpdateContentResponder $responder,
  ) {
  }

  public function __invoke(
    Request $request,
  ) {
    $updatedEntity = $this->service->update(
      $request->input('args'),
      $request->input('conditions'),
    );

    ($this->responder)($updatedEntity);
  }
}