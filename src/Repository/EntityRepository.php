<?php

namespace App\Repository;

use App\Core\Database;

class EntityRepository {
  public function __construct(
    private Database $db
  ) {}

  private function checkIfExists() {

  }

  public function insert() {
    // Check if exists using $this->checkIfExists()
    // Insert
    // Return new entity
  }
}