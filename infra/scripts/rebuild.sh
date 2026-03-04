#!/usr/bin/env bash
# Force rebuild of all images and restart
set -euo pipefail
cd "$(dirname "$0")/../.."
docker compose down
docker compose build --no-cache
docker compose up -d
echo ""
echo "Rebuild complete → http://localhost"
