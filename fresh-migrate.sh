#!/bin/bash

# Exit immediately if any command fails, if any undefined variable is used,
# or if any command in a pipeline fails.
set -euo pipefail

echo "========================================="
echo "🚀 Starting Fresh Migration"
echo "========================================="

echo ""

echo "🧹 Cleaning database"
ddev mysql  -e "DROP DATABASE IF EXISTS db; CREATE DATABASE db;"

echo ""

echo "⚙ Running migration"
ddev php migrations/000.init.php

echo ""

ddev mysql -e "SHOW TABLES;"

echo "========================================="
echo "✅ Fresh migration completed successfully!"
echo "========================================="
