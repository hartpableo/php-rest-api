<?php

use App\Core\RateLimiter;

session_start();

// Bootstrap
require_once __DIR__ . '/../bootstrap.php';

RateLimiter::clearAllExpired();
