import { NextRequest, NextResponse } from "next/server";
import crypto from "crypto";

export const dynamic = "force-dynamic";

/** The site's own origin, trusting the proxy headers Vercel sets. */
export function originOf(req: NextRequest) {
  const host = req.headers.get("x-forwarded-host") ?? req.headers.get("host");
  const proto = req.headers.get("x-forwarded-proto") ?? "https";
  return host ? `${proto}://${host}` : req.nextUrl.origin;
}

/** Step 1 of the CMS login: send the editor to GitHub to approve access. */
export async function GET(req: NextRequest) {
  const clientId = process.env.GITHUB_CLIENT_ID;
  if (!clientId || !process.env.GITHUB_CLIENT_SECRET) {
    return new NextResponse(
      "The content editor is not connected to GitHub yet. Set GITHUB_CLIENT_ID and GITHUB_CLIENT_SECRET.",
      { status: 503, headers: { "Content-Type": "text/plain; charset=utf-8" } },
    );
  }

  // Random value tied to a cookie, so a login can only be completed by the
  // browser that started it.
  const state = crypto.randomBytes(16).toString("hex");

  const url = new URL("https://github.com/login/oauth/authorize");
  url.searchParams.set("client_id", clientId);
  url.searchParams.set("scope", "repo,user");
  url.searchParams.set("state", state);
  url.searchParams.set("redirect_uri", `${originOf(req)}/api/callback`);

  const res = NextResponse.redirect(url.toString());
  res.cookies.set("cms_oauth_state", state, {
    httpOnly: true,
    secure: true,
    sameSite: "lax",
    path: "/",
    maxAge: 600,
  });
  return res;
}
