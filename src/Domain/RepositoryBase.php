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

  public function checkIfExists(array $args): bool {
    $whereClauses = [];
    $bindings = [];

    foreach ($args as $property => $data) {
      $column = preg_replace('/[^a-zA-Z0-9_]/', '', $property);

      if (is_array($data)) {
        $operator = in_array($data[1], ['=', '!=', '<', '>', '<=', '>=', 'LIKE']) ? $data[1] : '=';
        $bindings[$column] = $data[0];
      } else {
        $operator = '=';
        $bindings[$column] = $data;
      }

      $whereClauses[] = "{$column} {$operator} :{$column}";
    }

    $sql = "SELECT 1 FROM {$this->table} WHERE " . implode(' AND ', $whereClauses) . " LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($bindings);
    return (bool)$stmt->fetchColumn();
  }
}