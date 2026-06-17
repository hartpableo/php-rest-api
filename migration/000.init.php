<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

if (php_sapi_name() !== 'cli') die();

$query = <<<SQL
-- Set timezone to UTC
SET time_zone = '+00:00';

-- Create user table
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` VARCHAR(12) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `verified` TINYINT(1) NOT NULL DEFAULT '0',
  `deactivated` TINYINT(1) NOT NULL DEFAULT '0',
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
  INDEX `idx_user_id` (`user_id`),
  CONSTRAINT `fk_content_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- Create entity_base table
CREATE TABLE IF NOT EXISTS `content_type` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `label` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `label_singular` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `label_plural` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_user_label` (`user_id`, `label`),
  UNIQUE KEY `idx_user_slug` (`user_id`, `slug`),
  CONSTRAINT `fk_content_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- Create field table
CREATE TABLE IF NOT EXISTS `field` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `type` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
  `content_type_id` INT NOT NULL,
  `label` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `idx_type_content` (`type`, `content_type_id`),
  UNIQUE KEY `idx_user_slug` (`user_id`, `slug`),
  INDEX `idx_user_label_content` (`user_id`, `label`, `content_type_id`),
  CONSTRAINT `fk_content_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- Create content table
CREATE TABLE IF NOT EXISTS `content` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `content_type_id` INT UNSIGNED NOT NULL,
  `label` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_user_slug` (`user_id`, `slug`),
  CONSTRAINT `fk_content_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
SQL;

$db = Database::getConnection();
$db->exec($query);

echo "✅ Migration finished.\n";
