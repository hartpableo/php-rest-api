<?php

namespace App\Core;

use App\Domain\ApiKey\ApiKeyService;

final class Auth {
  private static string $authHeaderPrefix = 'Basic ';

  public function __construct(
    private readonly ApiKeyService $service,
  ) {
  }

  public function isValidAPI(Request $request): bool {
    if (
      !isset($request->headers['Authorization'])
      || !is_string($request->headers['Authorization'])
      || !str_starts_with(
        $request->headers['Authorization'],
        self::$authHeaderPrefix
      )
      || !isset($request->headers['Origin'])
    ) {
      return FALSE;
    }

    $token = base64_decode(str_replace(
      self::$authHeaderPrefix,
      '',
      $request->headers['Authorization']
    ));
    $token = explode(':', $token);

    $user = $this->service->checkApiUser($token[1], $token[0]);

    if (empty($user)) {
      return FALSE;
    }

    $apiKey = $this->service->findBy([
      'key' => $token[1],
      'user_id' => $user['id'],
    ]);

    if ($apiKey['siteHost'] !== parse_url($apiKey['siteHost'], PHP_URL_HOST)) {
      return FALSE;
    }

    $request->userId = $user['id'];
    return TRUE;
  }
}