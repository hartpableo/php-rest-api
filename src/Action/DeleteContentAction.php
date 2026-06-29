<?php

namespace App\Action;

use App\Core\Request;
use App\Domain\Content\ContentService;
use App\Responder\DeleteContentResponder;

final readonly class DeleteContentAction {
  public function __construct(
    private ContentService         $service,
    private DeleteContentResponder $responder
  ) {
  }

  public function __invoke(
    Request $request,
  ) {
    $conditions = $request->input('conditions');
    $isDeleted = $this->service->delete(
      $conditions,
      $request->input('limit')
    );

    ($this->responder)($isDeleted, $conditions);
  }
}