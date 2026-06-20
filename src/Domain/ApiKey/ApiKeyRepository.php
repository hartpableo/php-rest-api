<?php

namespace App\Domain\ApiKey;

use App\Domain\RepositoryBase;

final class ApiKeyRepository extends RepositoryBase {
  protected string $table = 'api_key';
}