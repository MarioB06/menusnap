#!/usr/bin/env bash
# Run composer inside the php container.
# Usage: ./infra/scripts/composer.sh require some/package
set -euo pipefail
cd "$(dirname "$0")/../.."
docker compose exec php composer "$@"
