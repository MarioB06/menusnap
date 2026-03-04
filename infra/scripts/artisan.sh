#!/usr/bin/env bash
# Run any artisan command inside the php container.
# Usage: ./infra/scripts/artisan.sh migrate --seed
set -euo pipefail
cd "$(dirname "$0")/../.."
docker compose exec php php artisan "$@"
