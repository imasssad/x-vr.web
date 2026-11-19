import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // 'standalone' produces a self-contained Node server in .next/standalone,
  // which is what Hostinger's Node app slot runs.
  // Switch to 'export' for a pure static build with no Node process.
  output: "standalone",
  images: {
    // the photographs are pre-sized and already WebP, so Next's optimizer
    // would only add a server round-trip for no gain
    unoptimized: true,
  },
  poweredByHeader: false,
  compress: true,
};

export default nextConfig;
