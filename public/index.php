<?php

// TODO: Store the allowed origins somewhere more dynamic soon
$allowedOrigins = [
  'http://localhost:3000'
];

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? NULL;

// Validate origin
if (!in_array($origin, $allowedOrigins)) {
  http_response_code(403);
  echo json_encode([
    'ok' => FALSE,
    'message' => 'CORS origin not allowed'
  ]);
}

// Handle preflight request
if ($requestMethod === 'OPTIONS') {
  http_response_code(204); // No Content is best practice for OPTIONS
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH');
  header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');
  header('Access-Control-Max-Age: 7200');
  exit;
}

// Router

