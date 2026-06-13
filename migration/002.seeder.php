<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

if (php_sapi_name() !== 'cli') die();

// TODO: Create dummy data for development