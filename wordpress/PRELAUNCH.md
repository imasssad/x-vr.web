# Keeping the site out of search before launch

The client asked for the site not to be indexable or traceable yet. That
is two different things, and only the first is a setting.

## 1. Ask search engines not to list it (one click)

**Settings → Reading → tick "Discourage search engines from indexing
this site" → Save.**

That is WordPress's own switch. With it on:

- every page carries `<meta name="robots" content="noindex, nofollow">`
- `robots.txt` becomes `Disallow: /` and stops advertising the sitemap
- this theme also sends an `X-Robots-Tag` header, which covers the
  things a meta tag cannot: feeds, REST responses, and anything served
  through WordPress that is not a page

Right now none of that is true. The live site has no robots meta tag, no
header, and its `robots.txt` publishes `https://tradeskillapps.com/wp-sitemap.xml`,
which actively invites crawlers.

**Turn it off again on launch day.** A site that goes live still carrying
noindex is invisible to Google until somebody notices. The theme shows a
warning on every admin screen while it is on, so it is hard to forget.

## 2. Files on disk need a server rule

Requests for `xvr-weld.mp4`, the logo and the photographs never reach
PHP, so no WordPress setting covers them. Add this to `.htaccess` in the
WordPress root, above the `# BEGIN WordPress` block:

```apache
<IfModule mod_headers.c>
  <FilesMatch "\.(mp4|webm|webp|png|jpe?g|gif|svg|pdf)$">
    Header set X-Robots-Tag "noindex, nofollow, noimageindex"
  </FilesMatch>
</IfModule>
```

Take it out again at launch, with the setting above.

## 3. "Not traceable" is a different job

`noindex` is a request. Google and Bing honour it; a scraper need not,
and anyone with the address can still open the site and watch the film.

If the client means nobody should be able to see it at all, the site
needs a password, not a robots rule. In hPanel: **Websites → Dashboard →
Security → Password Protect Directory**, and protect `public_html`. That
is a lock rather than a request, and it also stops the film being shared
around before launch.

One caveat worth passing on: a password-protected site cannot be shown
to anyone without the password, including the client's own colleagues.

## 4. What search engines already have

The site has been publicly reachable, so it may already be in an index.
Once the setting above is on, the fastest way to clear anything already
listed is Google Search Console → Removals → Temporary removals. Without
a verified Search Console property there is nothing to do but wait for
the recrawl.
