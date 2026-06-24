<?php

namespace App\Core;

use Random\RandomException;

final class CsrfToken {
  public function get(?string $handle = NULL) {
    return (!empty($handle)
      ? $_SESSION['csrf_token'][$handle]
      : ($_SESSION['csrf_token'] ?? NULL)) ?? $this->generate();
  }

  public function generate(?string $handle = NULL): string {
    try {
      $token = bin2hex(random_bytes(32));
    } catch (RandomException $e) {
      die($e->getMessage());
    }

    if (!empty($handle)) {
      $_SESSION['csrf_token'][$handle] = $token;
    }
    else {
      $_SESSION['csrf_token'] = $token;
    }

    return $token;
  }

  public function validate(?string $handle = NULL): bool {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      error_log('Session not started');
      return FALSE;
    }

    $sessionToken = (!empty($handle)
      ? $_SESSION['csrf_token'][$handle]
      : ($_SESSION['csrf_token'] ?? NULL));
    if (!is_string($sessionToken) || $sessionToken === '') {
      return FALSE;
    }

    $requestToken = $this->getFromRequest();

    if (!is_string($requestToken) || $requestToken === '') {
      return FALSE;
    }

    $ok = hash_equals($sessionToken, $requestToken);

    if ($ok) {
      if (!empty($handle)) {
        unset($_SESSION['csrf_token'][$handle]);
      }
      else {
        unset($_SESSION['csrf_token']);
      }
    }

    return $ok;
  }

  private function getFromRequest(): ?string {
    $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? NULL;
    if (is_string($headerToken) && $headerToken !== '') {
      return $headerToken;
    }

    $postToken = $_POST['__csrf_token'] ?? NULL;
    if (is_string($postToken) && $postToken !== '') {
      return $postToken;
    }

    return NULL;
  }
}