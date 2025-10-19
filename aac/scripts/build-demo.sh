#!/usr/bin/env bash
set -euo pipefail
ROOT=$(cd "$(dirname "$0")/.." && pwd)
ARCHIVE="aac-demo-$(date +%Y%m%d).tar.gz"
tar czf "$ARCHIVE" -C "$ROOT" public app modules config storage README.md CHANGELOG.md LICENSE composer.json tests scripts
echo "Created $ARCHIVE"
