# Hosting arrangement — tradeskillapps.com

*A short note you can forward. Written to be read by someone non-technical.*

---

**Summary:** the website will be served by Vercel. The domain name and all
email stay exactly where they are, with Hostinger. Nothing about your email
addresses or domain ownership changes.

### Why the website is moving

The Hostinger plan is shared hosting. It runs PHP well, which is what most
traditional websites use. The new site is built with Next.js, which needs a
Node.js process running continuously in the background — and shared hosting
is not able to keep one running reliably. That is a limitation of the plan
type, not a fault in the site or the host.

There is a second reason, measured rather than assumed. Shared hosting means
many websites share one machine. We timed the server's response eight times
in a row and it ranged from 0.14 to 0.80 seconds for the same request,
because the machine was busy with other customers' sites. Visitors feel that
as the page hesitating before it appears.

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

1. A free Vercel account is created for the project.
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
