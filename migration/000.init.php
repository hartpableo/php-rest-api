<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

if (php_sapi_name() !== 'cli') die();

$query = <<<SQL
-- Create user table
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` VARCHAR(12) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `verified` TINYINT(1) NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `email` (`email`)
);

-- Create verification table
CREATE TABLE IF NOT EXISTS `verification` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `token` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create api_key table
CREATE TABLE IF NOT EXISTS `api_key` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `key` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `site_host` VARCHAR(120) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `key` (`key`),
  INDEX `idx_user_id` (`user_id`)
);

-- Create entity_base table
CREATE TABLE IF NOT EXISTS `entity_base` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `label` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` VARCHAR(15) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_user_label` (`user_id`, `label`)
);
SQL;

$db = Database::getConnection();
$db->exec($query);

echo "✅ Migration finished.\n";
