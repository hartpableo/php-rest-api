<?php

namespace App\Domain;

use App\Core\Database;

class RepositoryBase {
  protected $db;
  protected string $table;

  public function __construct() {
    $this->db = new Database()->getConnection();
  }

  public function findBy($property, $value) {
    $stmt = $this->db->prepare("
      SELECT {$property} FROM {$this->table}
        WHERE {$property} = :value
    ");
    $stmt->execute([
      ':value' => $value,
    ]);
    return $stmt->fetch();
  }
}