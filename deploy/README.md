# deploy/static — ready to upload, no build required

`deploy/static/` is the **built** site. It is committed on purpose so the
server never has to run `npm install` or `next build`, which shared hosting
often cannot do reliably.

## Putting it live on Hostinger

```bash
cd ~
git clone https://github.com/imasssad/x-vr.web.git      # first time only
cd x-vr.web && git pull                                  # every time after

# the web root for THIS domain — use the explicit path
WEB=~/domains/tradeskillapps.com/public_html

# remove Hostinger's placeholder; PHP takes index priority over index.html
rm -f $WEB/default.php

# copy the built site in — note the dot after static/, which copies the
# contents (including .htaccess) rather than the folder itself
cp -r deploy/static/. $WEB/
```

> **Do not use `~/public_html` on this account.** It resolves to
> `~/domains/hypedpost.com/public_html`, a different site. Copying there
> would overwrite it. Always name the tradeskillapps.com path in full.

Then open https://tradeskillapps.com — it is live. Nothing runs in the
background, so nothing can crash or need restarting.

## Updating it later

I rebuild and commit `deploy/static/`. You run:

```bash
cd ~/x-vr.web && git pull && cp -r deploy/static/. ~/domains/tradeskillapps.com/public_html/
```

## The contact form

`contact.php` ships inside this folder and the form posts to it. Before it
can send, open it and set the two addresses at the top:

```php
$to   = 'hello@tradeskillapps.com';     // where enquiries arrive
$from = 'noreply@tradeskillapps.com';   // must be a real mailbox on the domain
```

Create that mailbox in hPanel → Emails first, or PHP `mail()` will be
rejected or filed as spam.

## If you would rather run the Node server

`nextjs/` holds the full app. Build `npm run bundle` locally, upload the
resulting `dist/` (25 MB), and point hPanel → Node.js at `server.js`. That
gives you `/api/contact` over SMTP instead of PHP. The static build above
is the safer default because nothing has to stay running.
