#!/usr/bin/env bash
# Stop all containers (keeps volumes)
set -euo pipefail
cd "$(dirname "$0")/../.."
docker compose down "$@"
