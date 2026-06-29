<?php

namespace App\Core;

final class RateLimiter {
  public function isRateLimited(
    string $key,
    int    $maxAttempts,
  ): bool {
    $now = time();
    $details = $_SESSION['rate_limiters'][$key] ?? NULL;

    if (!is_array($details)) {
      return FALSE;
    }

    $expiresAt = $details['expires_at'] ?? (($details['time'] ?? 0) + ($details['duration'] ?? 0));
    if ($now >= $expiresAt) {
      return FALSE;
    }

    $attempts = $details['attempts'] ?? 1;
    return $attempts >= $maxAttempts;
  }

  public function hit(
    string $key,
    int    $decaySeconds
  ): void {
    $now = time();
    $details = $_SESSION['rate_limiters'][$key] ?? NULL;

    if (
      !is_array($details)
      || $now >= (
        $details['expires_at']
        ?? (($details['time'] ?? 0) + ($details['duration'] ?? 0)))
    ) {
      $_SESSION['rate_limiters'][$key] = [
        'attempts' => 1,
        'expires_at' => $now + $decaySeconds
      ];
      return;
    }

    $attempts = $details['attempts'] ?? 1;
    $_SESSION['rate_limiters'][$key] = [
      'attempts' => $attempts + 1,
      'expires_at' => $details['expires_at'] ?? ($now + $decaySeconds)
    ];
  }

  public function clear(string $key): void {
    unset($_SESSION['rate_limiters'][$key]);
  }

  public static function clearAllExpired(): void {
    if (empty($_SESSION['rate_limiters']) || !is_array($_SESSION['rate_limiters'])) {
      return;
    }

    $now = time();
    foreach ($_SESSION['rate_limiters'] as $key => $value) {
      if (!is_array($value)) {
        unset($_SESSION['rate_limiters'][$key]);
        continue;
      }

      $expiresAt = $value['expires_at'] ?? (($value['time'] ?? 0) + ($value['duration'] ?? 0));
      if ($now >= $expiresAt) {
        unset($_SESSION['rate_limiters'][$key]);
      }
    }
  }
}