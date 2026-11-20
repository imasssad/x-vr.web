import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // 'standalone' produces a self-contained Node server in .next/standalone,
  // which is what Hostinger's Node app slot runs.
  // Switch to 'export' for a pure static build with no Node process.
  // Three deployment shapes from one codebase:
  //   BUILD_TARGET=static -> "export",     a plain static site, no Node needed
  //   on Vercel           -> undefined,    Vercel handles packaging itself
  //   anywhere else       -> "standalone", a self-contained Node server
  output:
    process.env.BUILD_TARGET === "static"
      ? "export"
      : process.env.VERCEL
        ? undefined
        : "standalone",
  images: {
    // the photographs are pre-sized and already WebP, so Next's optimizer
    // would only add a server round-trip for no gain
    unoptimized: true,
  },
  // Inlines the stylesheet into the HTML instead of linking it. Removes a
  // render-blocking round trip, which on a slow connection showed as a flash
  // of unstyled content before the CSS arrived.
  experimental: { inlineCss: true },
  poweredByHeader: false,
  compress: true,
  // /admin is a plain file in public/, and Next doesn't serve a directory's
  // index.html by itself. The CMS routes internally on the hash, so these two
  // entries are all it needs. Rewrites don't exist in a static export, where
  // the CMS isn't available anyway (it needs the login endpoints).
  ...(process.env.BUILD_TARGET === "static"
    ? {}
    : {
        async rewrites() {
          return [
            { source: "/admin", destination: "/admin/index.html" },
            { source: "/admin/", destination: "/admin/index.html" },
          ];
        },
      }),
};

export default nextConfig;
