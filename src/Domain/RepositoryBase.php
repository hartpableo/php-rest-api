<?php

namespace App\Domain;

use App\Core\Database;

class RepositoryBase {
  protected $db;
  protected string $table;

  public function __construct() {
    $this->db = new Database()->getConnection();
  }
}