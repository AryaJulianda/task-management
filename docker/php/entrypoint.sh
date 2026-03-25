#!/usr/bin/env sh
set -e

if [ -f /var/www/package.json ]; then
    if [ ! -f /var/www/public/build/manifest.json ]; then
        if command -v npm >/dev/null 2>&1; then
            echo "[entrypoint] Installing npm dependencies..."
            npm install --no-audit --no-fund
            echo "[entrypoint] Building assets..."
            npm run build
        else
            echo "[entrypoint] npm not found; skipping asset build."
        fi
    else
        echo "[entrypoint] Vite manifest exists; skipping asset build."
    fi
fi

exec "$@"
