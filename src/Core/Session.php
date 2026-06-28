<?php

namespace App\Core;

final class Session {
  public static function isUserLoggedIn(): bool {
    return !empty($_SESSION['user'] ?? NULL);
  }

  public static function getCurrentUser(): ?array {
    return self::isUserLoggedIn() ? (array)$_SESSION['user'] : NULL;
  }

  public static function logout(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      return;
    }

    unset($_SESSION['user']);

    session_regenerate_id(TRUE);

    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => (bool)$params['secure'],
        'httponly' => (bool)$params['httponly'],
        'samesite' => 'Lax',
      ]
    );

    session_destroy();
  }
}