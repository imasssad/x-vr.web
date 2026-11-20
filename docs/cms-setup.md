# Setting up the content editor

The site's words and pictures live in `nextjs/content/data/*.json`. The editor
at **tradeskillapps.com/admin** edits those files through GitHub: every save is
a commit to this repository, and Vercel redeploys the site automatically.

Nothing here needs a database, and there is no second password to manage —
whoever edits the site signs in with their own GitHub account.

This is a one-time setup. It takes about ten minutes.

---

## 1. Create a GitHub OAuth App

This is what lets the editor sign people in.

1. Go to **github.com → Settings → Developer settings → OAuth Apps → New OAuth App**
   (direct link: <https://github.com/settings/developers>)
2. Fill in:

   | Field | Value |
   | --- | --- |
   | Application name | `TradeSkillApps content editor` |
   | Homepage URL | `https://tradeskillapps.com` |
   | Authorization callback URL | `https://tradeskillapps.com/api/callback` |

3. Press **Register application**.
4. Copy the **Client ID**.
5. Press **Generate a new client secret** and copy that too. GitHub shows the
   secret once — if you lose it, generate another.

> The callback URL must match exactly, including `https://` and no trailing
> slash. A mismatch is the usual cause of a sign-in that fails straight away.

---

## 2. Add the two values to Vercel

In the Vercel project: **Settings → Environment Variables**. Add both for
Production, Preview and Development:

| Name | Value |
| --- | --- |
| `GITHUB_CLIENT_ID` | the Client ID from step 1 |
| `GITHUB_CLIENT_SECRET` | the client secret from step 1 |

Redeploy afterwards so the running site picks them up.

**Never commit these to the repository.** They belong only in Vercel's panel.

Until they are set, `/admin` loads but sign-in returns a plain message saying
the editor is not connected yet. That is expected, not a fault.

---

## 3. Give editors access

Anyone who should be able to edit the site needs **write access to the
`imasssad/x-vr.web` repository** on GitHub. Add them under
**Settings → Collaborators**.

Signing in to the editor uses their GitHub account, so there are no extra
passwords to issue or revoke — removing someone from the repository removes
their access to the editor at the same time.

---

## 4. Check it works

1. Open <https://tradeskillapps.com/admin>
2. Press **Login with GitHub** and approve the access request.
3. The list of sections appears. Change a word, press **Publish**.
4. GitHub shows a new commit; Vercel starts a deployment; the change is live
   in a minute or two.

---

## If the branch name changes

`nextjs/public/admin/config.yml` names the branch the editor commits to:

```yaml
backend:
  repo: imasssad/x-vr.web
  branch: main
```

If the site is ever deployed from a different branch, change `branch:` to match,
or the editor will save to a branch nobody is serving.

---

## Where things are

| What | Where |
| --- | --- |
| The editable content | `nextjs/content/data/*.json` |
| What the editor shows, and in what order | `nextjs/public/admin/config.yml` |
| The editor page itself | `nextjs/public/admin/index.html` |
| Sign-in endpoints | `nextjs/app/api/auth/`, `nextjs/app/api/callback/` |
| Uploaded pictures | `nextjs/public/assets/images/` |

The editor software is installed from npm (`decap-cms`, pinned in
`package.json`) and copied into place by `npm run build`. It is not loaded from
anyone else's server, so there is no CDN that can go down or change under us.

## A note on the static build

`npm run bundle:static` produces a copy of the site that needs no Node process
— the version that was uploaded to Hostinger. The editor is deliberately left
out of it, because signing in needs the two endpoints above and a static host
cannot run them. The static build is a fallback; the editor belongs on Vercel.
