<?php

namespace App\Utility;

final class Template {
  public function __construct(
    string $template,
    array  $vars = [],
    int    $status = 200,
  ) {
    $template = str_replace('.', '/', $template);
    extract($vars);
    http_response_code($status);
    header('Content-Type: text/html; charset=UTF-8');
    require APP_ROOT . '/template/' . $template . '.php';
  }
}