<?php

namespace App\Domain;

use App\Core\Database;

class RepositoryBase {
  protected $db;
  protected string $table;

  public function __construct() {
    $this->db = new Database()->getConnection();
  }

  public function findAll(
    array $args = [],
    ?int  $offset = NULL,
    ?int  $limit = NULL
  ): array {
    [$whereClauses, $bindings] = $this->buildWhereClauses($args);
    $stmt = $this->db->prepare("
      SELECT * FROM {$this->table}
      WHERE " . implode(' AND ', $whereClauses) . "
    ");


    if (isset($offset)) {
      $stmt .= " OFFSET {$offset}";
    }

    if (isset($limit)) {
      $stmt .= " LIMIT {$limit}";
    }

    $stmt->execute($bindings);
    return $stmt->fetchAll();
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
    [$whereClauses, $bindings] = $this->buildWhereClauses($args);
    $stmt = $this->db->prepare("
      SELECT 1 FROM {$this->table} 
      WHERE " . implode(' AND ', $whereClauses) . " LIMIT 1
    ");
    $stmt->execute($bindings);
    return (bool)$stmt->fetchColumn();
  }

  private function buildWhereClauses(array $args): array {
    $whereClauses = [];
    $bindings = [];
    $paramCounter = 0;
    $allowedOperators = [
      '=', '!=', '<', '>', '<=', '>=',
      'LIKE', 'NOT LIKE', 'ILIKE', 'IN',
      'NOT IN', 'BETWEEN', 'IS NULL', 'IS NOT NULL'
    ];

    foreach ($args as $property => $data) {
      $column = preg_replace('/[^a-zA-Z0-9_]/', '', $property);

      // Normalize inputs so everything acts like an array payload
      if (!is_array($data)) {
        $value = $data;
        $operator = '=';
      }
      else {
        $value = $data[0] ?? NULL;
        $operator = in_array(strtoupper($data[1] ?? ''), $allowedOperators)
          ? strtoupper($data[1])
          : '=';
      }

      // Prepare a unique binding key
      $paramKey = "{$column}_{$paramCounter}";
      $paramCounter++;

      // Handle the different operators
      if ($operator === 'IS NULL' || $operator === 'IS NOT NULL') {
        $whereClauses[] = "{$column} {$operator}";
      }

      elseif (($operator === 'IN' || $operator === 'NOT IN') && is_array($value)) {
        $inPlaceholders = [];
        foreach ($value as $index => $val) {
          $subKey = "{$paramKey}_{$index}";
          $inPlaceholders[] = ":{$subKey}";
          $bindings[$subKey] = $val;
        }
        $placeholdersStr = implode(', ', $inPlaceholders);
        $whereClauses[] = "{$column} {$operator} ({$placeholdersStr})";
      }

      elseif ($operator === 'BETWEEN' && is_array($value) && count($value) >= 2) {
        $startKey = "{$paramKey}_start";
        $endKey = "{$paramKey}_end";
        $bindings[$startKey] = $value[0];
        $bindings[$endKey] = $value[1];
        $whereClauses[] = "{$column} BETWEEN :{$startKey} AND :{$endKey}";
      }

      else {
        $whereClauses[] = "{$column} {$operator} :{$paramKey}";
        $bindings[$paramKey] = $value;
      }

      $whereClauses[] = "{$column} {$operator} :{$column}";
    }

    return [$whereClauses, $bindings];
  }
}