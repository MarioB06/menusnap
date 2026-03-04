#!/usr/bin/env bash
# Run npm inside a disposable Node 22 Alpine container.
# Shares the backend_build named volume so compiled assets are available
# to the php and nginx containers without touching the host.
#
# Usage:
#   ./infra/scripts/npm.sh install
#   ./infra/scripts/npm.sh run build
#   ./infra/scripts/npm.sh run dev      ← Vite dev-server on port 5173
#
# NOTE: node_modules on the host may differ from Alpine (musl vs glibc).
# Run `./infra/scripts/npm.sh install` once after cloning to create
# Alpine-compatible node_modules inside the bind-mounted source.
set -euo pipefail
cd "$(dirname "$0")/../.."

PORTS=""
if [[ "${1:-}" == "run" && "${2:-}" == "dev" ]]; then
    PORTS="-p 5173:5173"
fi

docker run --rm \
    -v "$(pwd)/apps/backend:/app" \
    -v "menusnap_backend_build:/app/public/build" \
    -w /app \
    $PORTS \
    node:22-alpine \
    npm "$@"
