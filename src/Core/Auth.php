<?php

namespace App\Core;

use App\Domain\ApiKey\ApiKeyService;

final readonly class Auth {
  public function __construct(
    private ApiKeyService $apiKeyService,
  ) {}

  public function isValidAPI(Request $request): bool {
    if (
      !isset($request->headers['Authorization'])
      || !is_string($request->headers['Authorization'])
      || !str_starts_with($request->headers['Authorization'], 'Bearer ')
    ) {
      return FALSE;
    }

    $token = substr($request->headers['Authorization'], 7);
    return $this->apiKeyService->checkApiUser(
      $token,
      $request->input('userId')
    );
  }
}