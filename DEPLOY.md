# Deploying tradeskillapps.com

The same codebase ships three ways. Pick one.

---

## 1. Vercel — recommended

Best fit for Next.js, and it fixes the one thing optimisation cannot: the
server. Measured on the Hostinger box, time-to-first-byte swung between
0.14s and 0.80s, because it is a shared machine (load was above 21). Vercel
serves from a CDN close to each visitor, so TTFB is consistently low.

It also gives you the real `/api/contact` route over SMTP instead of the PHP
fallback.

**Setup, once:**

1. vercel.com → **Add New Project** → import `imasssad/x-vr.web`.
2. Vercel reads `vercel.json` in the repo root and finds the app in `nextjs/`.
   If it asks, set **Root Directory** to `nextjs`.
3. Add these environment variables (Settings → Environment Variables):

   | Name | Value |
   |---|---|
   | `SMTP_HOST` | `smtp.hostinger.com` |
   | `SMTP_PORT` | `465` |
   | `SMTP_USER` | the mailbox, e.g. `hello@tradeskillapps.com` |
   | `SMTP_PASS` | that mailbox's password |
   | `CONTACT_TO` | where enquiries should arrive |
   | `GITHUB_CLIENT_ID` | from the GitHub OAuth App — see `docs/cms-setup.md` |
   | `GITHUB_CLIENT_SECRET` | from the same place |

   The last two power the content editor at `/admin`. Without them the site
   runs normally and only the editor's sign-in is unavailable.

4. Deploy. You get a `*.vercel.app` URL immediately.
5. Settings → **Domains** → add `tradeskillapps.com`. Vercel shows the DNS
   records to set. In Hostinger → **DNS Zone**, point the domain at Vercel.

**Keep the mailbox on Hostinger.** Only the website moves; email, DNS and the
domain registration stay where they are.

After this, every `git push` to `main` deploys automatically — including the
commits the content editor makes, which is what lets a non-technical editor
publish changes without anyone running a build.

Then follow `docs/cms-setup.md` to create the GitHub OAuth App, and hand
`docs/editing-the-site.md` to whoever will be editing the site.

---

## 2. Hostinger, static — what is live now

No Node process, nothing to keep running. The contact form posts to
`contact.php`.

```bash
cd ~/x-vr.web && git pull && cp -r deploy/static/. ~/domains/tradeskillapps.com/public_html/
```

> Use that full path. `~/public_html` on this account resolves to
> `~/domains/hypedpost.com/public_html`, a different site.

`cp` adds and overwrites but never deletes, so old build files accumulate
harmlessly. To clear them out first:

```bash
rm -rf ~/domains/tradeskillapps.com/public_html/_next
```

Before the form can send, set the two addresses at the top of
`public_html/contact.php`, and create that mailbox in hPanel → Emails.

---

## 3. Hostinger, Node app

Full Next.js with the SMTP API route, on your own server.

```bash
cd nextjs && npm run bundle     # produces dist/, about 25 MB
```

Upload the contents of `dist/`, then hPanel → **Node.js**: startup file
`server.js`, Node 20+, and the same environment variables as Vercel. Do not
run `npm install` on the server — `dist/` already contains what it needs.

---

## What each costs

Measured on the built output, not estimated:

| | Requests | Transferred | Framework JS |
|---|---|---|---|
| Next.js build (1, 2, 3 above) | 14 | ~1.0 MB | 474 KB raw, ~142 KB compressed |
| Original static site (repo root) | 11 | ~0.8 MB | none |

The original hand-built site is still at the repo root and is genuinely
lighter. It has no content layer and no API route, so it is the right answer
only if the CMS path is abandoned.
