#!/usr/bin/env bash
# Builds a plain static site — no Node process required on the server.
# The contact form posts to contact.php instead of the API route.
set -euo pipefail
cd "$(dirname "$0")/.."

# API routes cannot exist in a static export, so move them aside for the build
trap 'if [ -d .api-held ]; then mkdir -p app && mv .api-held app/api; fi' EXIT
if [ -d app/api ]; then mv app/api .api-held; fi

BUILD_TARGET=static NEXT_PUBLIC_CONTACT_ENDPOINT=/contact.php npx next build

rm -rf dist-static && cp -r out dist-static

# the content editor needs the GitHub login endpoints, which a static export
# cannot host — drop it rather than ship a page that can only fail
rm -rf dist-static/admin
cp ../.htaccess dist-static/.htaccess 2>/dev/null || true

echo
echo "dist-static/ is ready. Size: $(du -sh dist-static | cut -f1)"
echo "Upload its CONTENTS to public_html. No Node process needed."
