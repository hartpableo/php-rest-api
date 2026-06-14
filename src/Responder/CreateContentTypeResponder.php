<?php

namespace App\Responder;

use App\Domain\ContentType\ContentTypeEntity;

final class CreateContentTypeResponder {
  public function __invoke(ContentTypeEntity $entity): string {
    return '<h1>Hello from the CreateEntityBaseResponder class!</h1>' . PHP_EOL . print_r($entity, TRUE);
  }
}