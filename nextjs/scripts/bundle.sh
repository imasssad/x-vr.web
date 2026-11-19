#!/usr/bin/env bash
# Builds a self-contained folder you can upload to Hostinger's Node app slot.
# Next's standalone output does not include static assets, so they are copied in.
set -euo pipefail
cd "$(dirname "$0")/.."

npm run build
rm -rf dist && mkdir -p dist
cp -r .next/standalone/. dist/
mkdir -p dist/.next
cp -r .next/static dist/.next/static
cp -r public dist/public

# Next's image optimiser is disabled (images are pre-sized WebP), so sharp and
# its platform binaries are never loaded. They are ~46 MB of the bundle, which
# matters a great deal when uploading over SFTP to shared hosting.
rm -rf dist/node_modules/@img dist/node_modules/sharp

cat > dist/start.sh <<'INNER'
#!/usr/bin/env bash
# Hostinger sets PORT; default to 3000 when run by hand.
export NODE_ENV=production
exec node server.js
INNER
chmod +x dist/start.sh

echo
echo "dist/ is ready to upload. Size: $(du -sh dist | cut -f1)"
echo "Entry point for the Node app: server.js"
