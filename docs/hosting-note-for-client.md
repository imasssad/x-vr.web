# Hosting arrangement — tradeskillapps.com

> **Update (November 2026):** the project has since moved to WordPress.
> This document describes the earlier Next.js/Vercel setup and is kept for
> reference only. The current instructions live in `wordpress/README.md`.

*A short note you can forward. Written to be read by someone non-technical.*

---

**Summary:** the website will be served by Vercel. The domain name and all
email stay exactly where they are, with Hostinger. Nothing about your email
addresses or domain ownership changes.

### Why the website is moving

The Hostinger plan does support Node.js, and the site was deployed there
successfully. The problem is not capability — it is speed, and it was
measured rather than assumed.

Shared hosting means many websites share one machine. We timed the server's
response eight times in a row for the same request: it ranged from 0.14 to
0.80 seconds, because the machine was busy serving other customers' sites.
Visitors feel that variation as the page hesitating before it appears, and
it is not something the site itself can fix.

The version currently live on Hostinger is a simplified build that needs no
Node process at all, which keeps it as fast as that machine allows. It also
means the contact form runs through PHP and the new content editor cannot
run there, because signing in needs two small server routes a plain static
host has no way to provide.

### What Vercel changes

Vercel is built by the team behind Next.js and hosts exactly this kind of
site. It serves the pages from locations close to each visitor, so the site
loads quickly and consistently rather than depending on how busy one shared
machine happens to be.

The plan we need is **free**. There is no new cost.

### What stays with Hostinger

- **The domain name** — registration and ownership, unchanged.
- **All email** — mailboxes, forwarding and settings, untouched.
- **DNS control** — managed in the Hostinger panel as now.

Only one DNS record changes, pointing the website address at Vercel. Email
records are untouched, so mail keeps flowing throughout.

### What happens next

1. You create a free Vercel account for the project and connect it to the
   code repository.
2. The site is deployed there and tested on a temporary address.
3. Once it is confirmed working, the DNS record is updated.
4. The switch takes effect within the hour, with no downtime.

### In short

| | Where it lives | Changing? |
|---|---|---|
| Website | Vercel | Yes — moving |
| Domain name | Hostinger | No |
| Email | Hostinger | No |
| DNS | Hostinger | One record |
| Cost | — | No change |
