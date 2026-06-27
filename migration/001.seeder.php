<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

if (php_sapi_name() !== 'cli') die();

$db = Database::getConnection();

$password = password_hash('1234567', PASSWORD_DEFAULT);
$db->exec("
INSERT INTO `user` (email, password)
  VALUES ('sample@gmail.com', '{$password}');
");

$user = $db->query("SELECT id, name FROM `user` WHERE name = 'Hart'")->fetch();

$db->exec("
INSERT INTO `api_key` (user_id, `key`, site_host)
VALUES ('{$user['id']}', 'samplekey', 'hart.test');
");

$db->exec("
INSERT INTO `content_type` (user_id, label, label_singular, label_plural, slug)
  VALUES ('{$user['id']}', 'Article', 'Article', 'Articles', 'article');
");

$ct = $db->query("SELECT id, label FROM `content_type` WHERE user_id = '{$user['id']}'")->fetch();

echo "==== Dummy Content Generated" . PHP_EOL;
echo "==== User: {$user['name']} ({$user['id']})" . PHP_EOL;
echo "==== Content Type: {$ct['label']} ({$ct['id']})" . PHP_EOL;
echo "✔ Seeder finished." . PHP_EOL;