import { NextRequest, NextResponse } from "next/server";
import { originOf } from "../auth/route";

export const dynamic = "force-dynamic";

/** A small page that hands the result back to the CMS window, then closes. */
function reply(body: string, status = 200) {
  return new NextResponse(body, {
    status,
    headers: {
      "Content-Type": "text/html; charset=utf-8",
      "Cache-Control": "no-store",
    },
  });
}

function handshake(message: string, visible: string) {
  // The CMS opened this window and is listening. It speaks first; we answer
  // back to the exact origin it reports, never to "*".
  return reply(`<!doctype html><html lang="en"><head><meta charset="utf-8">
<title>Signing in…</title></head><body style="font:16px/1.5 system-ui;padding:2rem;color:#15283F">
<p>${visible}</p>
<script>
(function () {
  var msg = ${JSON.stringify(message)};
  function answer(e) {
    if (!window.opener) return;
    window.opener.postMessage(msg, e.origin);
    window.removeEventListener("message", answer, false);
  }
  window.addEventListener("message", answer, false);
  if (window.opener) window.opener.postMessage("authorizing:github", "*");
  else document.body.insertAdjacentHTML("beforeend",
    "<p>You can close this window and return to the editor.</p>");
})();
</script></body></html>`);
}

/** Step 2: GitHub sends the editor back here with a code; swap it for a token. */
export async function GET(req: NextRequest) {
  const clientId = process.env.GITHUB_CLIENT_ID;
  const clientSecret = process.env.GITHUB_CLIENT_SECRET;
  if (!clientId || !clientSecret) {
    return reply("<p>The content editor is not connected to GitHub yet.</p>", 503);
  }

  const code = req.nextUrl.searchParams.get("code");
  const state = req.nextUrl.searchParams.get("state");
  const expected = req.cookies.get("cms_oauth_state")?.value;

  if (req.nextUrl.searchParams.get("error")) {
    return handshake(
      "authorization:github:error:" +
        JSON.stringify({ message: req.nextUrl.searchParams.get("error_description") ?? "Access was declined." }),
      "Access was declined.",
    );
  }
  if (!code || !state || !expected || state !== expected) {
    return reply("<p>That sign-in link has expired. Close this window and try again.</p>", 400);
  }

  let token: string | undefined;
  try {
    const r = await fetch("https://github.com/login/oauth/access_token", {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({
        client_id: clientId,
        client_secret: clientSecret,
        code,
        redirect_uri: `${originOf(req)}/api/callback`,
      }),
    });
    const data = (await r.json()) as { access_token?: string; error_description?: string };
    if (!data.access_token) {
      return handshake(
        "authorization:github:error:" +
          JSON.stringify({ message: data.error_description ?? "GitHub did not return a token." }),
        "Sign-in failed.",
      );
    }
    token = data.access_token;
  } catch {
    return handshake(
      "authorization:github:error:" + JSON.stringify({ message: "Could not reach GitHub." }),
      "Sign-in failed.",
    );
  }

  const res = handshake(
    "authorization:github:success:" + JSON.stringify({ token, provider: "github" }),
    "Signed in. This window will close itself.",
  );
  res.cookies.set("cms_oauth_state", "", { path: "/", maxAge: 0 });
  return res;
}
