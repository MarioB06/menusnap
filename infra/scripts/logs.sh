#!/usr/bin/env bash
# Tail logs (default: all services; pass service name to filter, e.g. logs.sh php)
set -euo pipefail
cd "$(dirname "$0")/../.."
docker compose logs -f "${1:-}" "${@:2}"
