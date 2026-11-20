/**
 * Copies the Decap CMS bundle out of node_modules into public/admin/cms/.
 * Self-hosting it means /admin doesn't depend on a CDN staying up, and the
 * version is pinned by package.json rather than by a URL.
 * Runs automatically before every build; the copied files are not committed.
 */
import { cp, mkdir, readdir, rm } from "node:fs/promises";
import { existsSync } from "node:fs";
import path from "node:path";

const src = path.resolve("node_modules/decap-cms/dist");
const dest = path.resolve("public/admin/cms");

// A static export has no login endpoints, so the CMS can't run there. Skip
// the copy rather than ship 6 MB of editor into a build that can't use it.
if (process.env.BUILD_TARGET === "static") {
  await rm(dest, { recursive: true, force: true });
  console.log("Decap CMS: skipped (static build has no login endpoints)");
  process.exit(0);
}

if (!existsSync(src)) {
  console.error("decap-cms is not installed — run npm install");
  process.exit(1);
}

await rm(dest, { recursive: true, force: true });
await mkdir(dest, { recursive: true });

// The package ships every chunk twice, once as *.cms.js and once as
// *.decap-cms.js. We load decap-cms.js, so only that set is needed.
const keep = (f) =>
  !f.endsWith(".map") &&
  !f.endsWith(".LICENSE.txt") &&
  (f === "decap-cms.js" || f.endsWith(".decap-cms.js") || f.endsWith(".wasm"));

let n = 0;
let bytes = 0;
for (const f of await readdir(src)) {
  if (!keep(f)) continue;
  await cp(path.join(src, f), path.join(dest, f));
  n++;
  bytes += (await import("node:fs")).statSync(path.join(src, f)).size;
}
console.log(`Decap CMS: copied ${n} files (${(bytes / 1024 / 1024).toFixed(1)} MB) to public/admin/cms/`);
