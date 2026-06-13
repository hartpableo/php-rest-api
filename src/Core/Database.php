<?php

namespace App\Core;

use PDO;

final class Database {
  private static ?\PDO $instance = NULL;

  public static function getConnection(): PDO {
    if (empty(self::$instance)) {
      $dbType = getenv('DB_TYPE');
      $dbHost = getenv('DB_HOST');
      $dbUser = getenv('DB_USER');
      $dbPassword = getenv('DB_PASSWORD');
      $dbName = getenv('DB_NAME');
      $dbPort = getenv('DB_PORT');
      self::$instance = new PDO(
        "{$dbType}:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPassword,
        [
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
      );
    }

    return self::$instance;
  }
}