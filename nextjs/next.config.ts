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
};

export default nextConfig;
