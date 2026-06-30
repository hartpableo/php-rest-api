#!/bin/bash

set -euo pipefail

echo "========================================="
echo "🚀 Starting Fresh Migration"
echo "========================================="
echo ""

echo "🧹 Cleaning database..."
ddev mysql -e "DROP DATABASE IF EXISTS db; CREATE DATABASE db;"
echo ""

# Keep the milestone echoes right next to the exact command execution
echo "⚙ Running system initialization..."
ddev php migration/000.init.php
echo ""

echo "📊 Current Database State:"
ddev mysql -e "SHOW TABLES;"
echo ""

echo "========================================="
echo "🎉 Fresh migration completed successfully!"
echo "========================================="
