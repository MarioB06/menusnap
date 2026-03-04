#!/usr/bin/env bash
# Start all containers (build if needed)
set -euo pipefail
cd "$(dirname "$0")/../.."
docker compose up -d --build "$@"
echo ""
echo "MenuSnap is running → http://localhost"
