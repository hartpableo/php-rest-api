<?php

namespace App\Domain;

use App\Core\Database;

class RepositoryBase {
  protected $db;
  protected string $table;

  public function __construct() {
    $this->db = Database::getConnection();
  }

  public function findAll(
    array $args = [],
    ?int  $offset = NULL,
    ?int  $limit = NULL
  ): array {
    [$whereClauses, $whereBindings] = $this->buildWhereClauses($args);

    $query = "SELECT * FROM `{$this->table}`";
    if (!empty($whereClauses)) {
      $query .= " WHERE " . implode(' AND ', $whereClauses);
    }

    // Request one extra row if a limit is set
    if (isset($limit)) {
      $searchLimit = $limit + 1;
      $query .= " LIMIT {$searchLimit}";
    }

    if (isset($offset)) {
      $query .= " OFFSET {$offset}";
    }

    $stmt = $this->db->prepare($query);
    $stmt->execute($whereBindings);
    $results = $stmt->fetchAll();

    // Check if the extra row exists
    $hasNextPage = FALSE;
    if (isset($limit) && count($results) > $limit) {
      $hasNextPage = TRUE;
      array_pop($results); // Remove the extra row
    }

    // Return both the data and the pagination status
    return [
      'data' => $results,
      'hasNextPage' => $hasNextPage
    ];
  }

  public function findBy(array $args) {
    [$whereClauses, $whereBindings] = $this->buildWhereClauses($args);
    $stmt = $this->db->prepare("
      SELECT * FROM `{$this->table}`
      WHERE " . implode(' AND ', $whereClauses) . " LIMIT 1
    ");
    $stmt->execute($whereBindings);
    return $stmt->fetch();
  }

  public function checkIfExists(array $args): bool {
    [$whereClauses, $whereBindings] = $this->buildWhereClauses($args);
    $stmt = $this->db->prepare("
      SELECT 1 FROM `{$this->table}` 
      WHERE " . implode(' AND ', $whereClauses) . " LIMIT 1
    ");
    $stmt->execute($whereBindings);
    return (bool)$stmt->fetchColumn();
  }

  protected function buildSetClauses(array $args): array {
    $setClauses = [];
    $setBindings = [];
    foreach ($args as $arg => $val) {
      $setClauses[] = "{$arg} = :{$arg}";
      $setBindings[":{$arg}"] = $val;
    }

    return [$setClauses, $setBindings];
  }

  protected function buildWhereClauses(array $args): array {
    $whereClauses = [];
    $whereBindings = [];
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
        $whereClauses[] = "`{$column}` {$operator}";
      }

      elseif (($operator === 'IN' || $operator === 'NOT IN') && is_array($value)) {
        $inPlaceholders = [];
        foreach ($value as $index => $val) {
          $subKey = "{$paramKey}_{$index}";
          $inPlaceholders[] = ":{$subKey}";
          $whereBindings[$subKey] = $val;
        }
        $placeholdersStr = implode(', ', $inPlaceholders);
        $whereClauses[] = "`{$column}` {$operator} ({$placeholdersStr})";
      }

      elseif ($operator === 'BETWEEN' && is_array($value) && count($value) >= 2) {
        $startKey = "{$paramKey}_start";
        $endKey = "{$paramKey}_end";
        $whereBindings[$startKey] = $value[0];
        $whereBindings[$endKey] = $value[1];
        $whereClauses[] = "`{$column}` BETWEEN :{$startKey} AND :{$endKey}";
      }

      else {
        $whereClauses[] = "`{$column}` {$operator} :{$paramKey}";
        $whereBindings[$paramKey] = $value;
      }
    }

    return [$whereClauses, $whereBindings];
  }
}