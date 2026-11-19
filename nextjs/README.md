# X-VR Skilled Trades — Next.js

The same site as the static build, rebuilt on Next.js 16 (App Router, React 19)
with a server for the contact form and a content layer a CMS can take over.

## What changed, honestly

| | Static build | This |
|---|---|---|
| JavaScript to the browser | ~17 KB hand-written | **~142 KB** compressed (React + Next) |
| Fonts | fetched from Google | **self-hosted** by `next/font`, no third-party request |
| Contact form | none | `POST /api/contact`, SMTP |
| Copy lives in | the HTML | `content/site.ts`, one file |

Next.js costs about 142 KB of JavaScript the static page did not need. What it
buys is the API route, the content layer, and self-hosted fonts. If the contact
form and CMS ever go away, the static build is the faster answer.

Most of the page is a **server component** — only the nav, hero controls, trade
tabs, accordions, planner and contact form ship JavaScript.

## Running it

```bash
npm install
npm run dev        # http://localhost:3000
npm run build      # production build
npm start          # serve the production build
```

## Deploying to Hostinger

Hostinger's Node app slot runs a Node process for you. Next's `standalone`
output suits that, but it deliberately omits static assets, so use the bundler:

```bash
npm run bundle
```

That produces `dist/` — a self-contained folder. Upload its **contents** to the
directory Hostinger points your Node app at, then set:

| Setting | Value |
|---|---|
| Application root | the folder you uploaded to |
| Startup file | `server.js` |
| Node version | 20 or newer |
| Environment | the variables below |

Hostinger provides `PORT`; `server.js` reads it. Do not run `npm install` on the
server — `dist/` already contains the dependencies it needs.

### Environment variables

Copy `.env.example` to `.env.local` for local work, and set the same keys in
Hostinger's panel for production:

```
SMTP_HOST=smtp.hostinger.com
SMTP_PORT=465
SMTP_USER=hello@tradeskillapps.com
SMTP_PASS=…            # the mailbox password, set in the panel, never committed
CONTACT_TO=hello@tradeskillapps.com
```

Until these are set the contact form answers **"The contact form is not
configured yet."** rather than failing silently.

### If the Node slot gives trouble

Switch `output` in `next.config.ts` from `"standalone"` to `"export"` and run
`npm run build`. That writes a plain static site to `out/` which any host
serves — but the contact API disappears with the server, so the form would need
a third-party endpoint.

## Editing content

Every word lives in `content/site.ts` and `content/planner.ts`. Change the text
there and rebuild; no component needs touching. That shape is what a CMS would
populate, so moving to Sanity or Payload later means filling the same objects
from an API instead of a file.

## Adding video

The hero already has a `<video>` wired to `public/assets/video/`. Drop in
`hero-video.mp4` (and optionally `.webm`) and it fades in over the photograph,
with play/pause and sound controls appearing automatically. For video elsewhere
on the page, reuse that pattern or embed YouTube/Vimeo.

## Still outstanding

- HVAC and electrical photographs — both cards show a labelled placeholder.
- SMTP credentials for the contact form.
