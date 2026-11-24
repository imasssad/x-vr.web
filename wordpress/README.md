# XVR Trade Skills — WordPress theme

`themes/tradeskillapps/` is the site as a self-contained WordPress theme. It
carries the same design, the same words and the same pictures as the original
build, and everything on the page can be changed from the WordPress admin.

Nothing else is required: no page builder, no paid plugin, no setup wizard.
Activate it and the finished site is there.

---

> **The theme folder is still called `tradeskillapps`, on purpose.** WordPress
> keeps every Customizer setting under a key derived from the folder name, so
> renaming the folder would orphan all of the site's saved wording and
> pictures. The folder name is invisible to visitors; the theme shows as
> **XVR Trade Skills** everywhere a person looks.

## Installing

1. **Appearance → Themes → Add New → Upload Theme**
2. Choose `tradeskillapps.zip`
3. **Install Now**, then **Activate**

On activation the theme creates the seven trades, three films, four FAQ
entries and four glossary terms it ships with. That happens once; it never
overwrites anything you have changed.

4. **Settings → Reading → Your homepage displays → A static page**, and pick a
   page (an empty one is fine — the theme supplies the content).

> The home page looks right even before step 4 — the theme's front-page
> template always wins. The setting matters later: it gives the site a page
> that Elementor can take over (see the Elementor section), and it keeps
> future blog posts off the front of the site.

---

## Where each thing is edited

| What | Where |
| --- | --- |
| Every heading, paragraph, button and picture | **Appearance → Customize → XVR Trade Skills content** |
| The trade cards | **Trades** in the main admin menu |
| The films | **Videos** |
| Questions and answers | **FAQ** |
| Glossary terms | **Glossary** |
| Curriculum, licensing and multiplayer sections | **Customize → XVR Trade Skills content** |
| Where enquiries are emailed | **Customize → Contact form → Send enquiries to** |

The Customizer panel lists every section in the order it appears down the page,
so finding a piece of text means scrolling to roughly where you see it.

Collections are posts because you add to and remove from them: a seventh
trade is **Add Trade**, and the **Order** number on each entry sets the
sequence (lower numbers first).

---

## How the content works

Every string has a shipped default, held in
`inc/defaults.php`. The templates read through `tsa_text()`, which returns the
Customizer value if there is one and the default otherwise.

Two consequences worth knowing:

- A fresh install already looks like the finished site. Nothing has to be
  imported or pasted in.
- Clearing a field leaves that spot empty on the page — useful for hiding a
  note, say. It does **not** bring the original wording back; the original
  is shown in grey under each field, so restoring it is a copy away.

`inc/defaults.php` is generated. To rebuild it from the original content
files:

```bash
python3 wordpress/tools/generate-defaults.py
```

---

## Pictures

Every picture sits in a frame of a fixed shape and is cropped from the centre,
so put the subject in the middle. Around 1600px wide is plenty.

Clearing a picture does not break the layout — the frame shows a neutral
"Photograph to be supplied" panel instead. Trades fall back to an illustrated
placeholder drawn to match their icon.

One thing is not editable in wp-admin: the step-by-step guidance the
starter planner generates after a visitor presses its button. The planner's
labels, headings and buttons are all in the Customizer; the generated steps
themselves ship with the theme, and changing those is a (small) developer
task.

The site's logo is the supplied XVR Trades artwork. The header uses a
version without the strapline, because at header height the strapline is a
few pixels tall and reads as a smudge; the footer shows the whole lockup.
To use a different logo, upload it at **Appearance → Customize → Site
Identity** and it replaces both.

Always fill in the screen-reader description. It is read aloud to people who
cannot see the image, and search engines use it.

---

## Videos

**Videos** in the main admin menu holds one entry per film: a title, a
description, a still image, and the film itself.

For the film, **paste the whole YouTube address** — the one in the browser's
address bar. Watch links, `youtu.be` links and Shorts links all work, extra
bits like `&t=30` and playlist ids are ignored. (A bare video id still works
too.) If a film is uploaded to this site instead of YouTube, use the file
field underneath.

The first film is shown **large**, running the full width of the page with
its description beside it, so an introduction film gets the presence it
deserves. The rest follow underneath at card size. To make them all the same
size instead: **Customize → Videos — section heading → Layout**.

Until a slot has a film it shows a "Film coming soon" marker, which is
deliberate — better than an empty rectangle. Nothing is embedded until a
visitor presses play, so an unplayed film costs nothing.

**In Elementor**, the Videos section has a **Films** panel of its own. Leave
it empty and the entries from the Videos menu are used. Add a film there and
that page shows your list instead — handy for a landing page that needs its
own set.

---

## The contact form

Enquiries are sent with `wp_mail()`, so whatever the site already uses for
email is what gets used. On shared hosting that is usually the host's mail
service; an SMTP plugin, if one is installed, is picked up automatically.

Every enquiry is also kept under **Enquiries** in the admin menu, saved
before the mail is attempted — so if the mailbox is ever misconfigured, the
message waits in wp-admin instead of vanishing.

The form has a hidden field people never see and most bots fill in; a
submission that fills it is quietly discarded. Messages are validated on the
server, the sender's name is stripped of anything that could tamper with the
mail headers, and sending is rate-limited (five per hour per visitor, one
hundred per hour site-wide) so a script cannot flood the mailbox or burn the
host's mail quota.

There is deliberately no security token on this form: tokens baked into
cached pages expire and then reject real visitors, which on ordinary shared
hosting with a page cache silently kills the form. The protections above are
the ones that actually work for a public form.

With JavaScript the form answers in place. Without it, the form still posts
and still works.

---

## Elementor

The theme works with Elementor, and does not need it. With Elementor absent
none of this code runs; with it installed three things become available.

### 1. Pages can be built in Elementor

Edit any page with Elementor as usual. For a page that should control its own
full width, set **Page Attributes → Template → Full width (for page
builders)**; the header and footer stay, and the reading column is dropped.

A page built in Elementor is no longer wrapped in the theme's reading column,
so wide sections reach the edges of the screen the way they do on the home
page.

### 2. The site's own sections are draggable widgets

The widget panel has an **XVR Trade Skills** heading holding every
sections of the home page — Hero, Trades, Videos, Career pathways, Starter
planner, Contact form and the rest.

These are the same sections the home page uses, not copies. Fixing one fixes
both, and they cannot drift apart.

Drop one onto a page and it arrives finished, carrying the wording it has on
the home page. Each widget then offers the few fields worth changing per
placement — the small label, the headline and its highlighted half, the intro
paragraph. **Leave a box empty and the site-wide wording from the Customizer
is used**, so the same section can appear on two pages and still be edited in
one place.

Anything typed into a widget applies to that placement only. It never changes
the home page.

### 3. Anything you build alongside them matches

The theme's four colours and two typefaces are registered as Elementor's
global colours and fonts, so a heading or button added next to a section
inherits the site's look rather than Elementor's defaults. Elementor's stock
widgets — headings, text, buttons, lists, dividers — are styled to match.

Barlow Condensed and DM Sans are declared to Elementor as already present, so
it does not fetch the same two families from Google on top of the copies the
theme serves.

### With Elementor Pro

Theme Builder locations are registered for the header, footer, single and
archive. Build a header in Theme Builder and it replaces the theme's; build
none and the theme's is used. Nothing needs switching on.

### What this does not do

The home page is a PHP template, not an Elementor document, and **it does
not appear under Pages**. That is deliberate — it is why the page is fast,
and it is what the Customizer edits. If you go looking for the home page in
Pages you will not find it, and Elementor will not offer to edit it.

### Where the home page's words actually are

**Appearance → Customize → XVR Trade Skills content.** Twenty-two sections in
the order they appear down the page, every heading, paragraph, button and
picture among them. Trades, Videos, FAQ and Glossary are in the main admin
menu, because those are lists you add to and remove from.

### Taking the home page over in Elementor

One button does the whole thing:

**Appearance → Home in Elementor → "Build my home page in Elementor"**

It creates a page containing every section already arranged in
order, makes it the home page, and gives you an "Edit the home page with
Elementor" link. Open that and you are looking at your finished site inside
the builder — ready to swap pictures, reorder sections, or add anything
Elementor offers.

#### Selecting a section in the builder

Each website section is **one widget**. Clicking a section usually selects
the *section wrapper* first — the panel then shows Layout, Content Width and
Columns Gap, which are Elementor's own settings, not the content.

To reach the content, select the widget inside it. Three ways:

- Hover the section: it gets a dashed blue outline and a label reading
  "<name> — click to edit this section". Click inside that outline.
- Use the blue pencil handle at the **top-right corner** of the section.
- Open the **Navigator** (the layers icon at the bottom of the panel, or
  press `Cmd/Ctrl + I`) and click the section's widget in the tree. This is
  the most reliable way, and it names every section.

The panel then shows **Wording** and, where the section has photographs,
**Pictures**.

Pictures are editable right there: every section that shows a photograph has
a **Pictures** panel in its widget settings, and choosing a new image also
picks up that image's description from the Media Library, so the site stays
readable to screen readers. Twelve pictures across eight sections.

The lists — trades, videos, FAQ entries, glossary terms — stay in the main
admin menu, because you add to and remove from those. Each widget says so in
its own settings panel. Changes there appear on the page immediately.

(If you would rather assemble it yourself: make a page, set it as the
homepage under **Settings → Reading**, and press Edit with Elementor — the
theme stands aside as soon as the editor opens, so you get an empty canvas
and can drag in whichever sections you want.)

Point **Settings → Reading** back at "Your latest posts" (or remove
Elementor from that page) and the original template returns, exactly as it
was. Nothing is lost either way.

#### Everything is editable from the builder

Each section widget now carries **every** field that section has — not just
its headline. Select a section and the panel holds:

- **Wording** — all of its text, down to the individual pathway stages,
  learning-cycle steps, educator steps, planner labels and footer lines.
  Headings in the panel separate one block from the next.
- **Pictures** — every photograph that section shows.
- **A list**, where the section has one — Trade cards, Films, Questions or
  Terms — with add, remove and reorder.

Every box works the same way: **leave it empty and the site-wide value is
used**; type in it and that applies to this placement only. The original
wording is shown underneath each box, so putting it back is a copy away.

That means the same content has two homes, which is deliberate:

| | Best for |
| --- | --- |
| **Appearance → Customize** | Changing something once, everywhere it appears |
| **Elementor** | Changing it on one page, or rearranging the page itself |
| **Trades / Videos / FAQ / Glossary menus** | The master lists, used by every page that does not override them |

Nothing is trapped in one place any more.

---

## Going live

The order matters; each step is reversible until the last one.

1. **Back up first.** In Hostinger's panel take a full backup (files and
   database) of whatever currently lives on the account.
2. **Install WordPress** on the hosting account (Hostinger has a one-click
   installer) — ideally at a temporary address or subdomain first, so the
   current site stays up while you check the new one.
3. **Upload and activate the theme**, then run the smoke test below.
4. **Set the mail address**: Customize → Contact form → "Send enquiries
   to", then submit the form yourself and confirm the mail arrives AND the
   entry appears under Enquiries. If mail does not arrive, install an SMTP
   plugin (e.g. WP Mail SMTP) pointed at the Hostinger mailbox — the theme
   picks it up automatically.
5. **Swap the site**: point the domain's web root (or DNS) at the WordPress
   install. Email records are untouched, so mail keeps flowing.
6. **After the swap**: load the site logged out, click every menu item,
   submit the contact form once more from a phone.

Keep the backup from step 1 for at least a month.

## The ten-minute smoke test

Run on the staging install before going live, logged in as an administrator:

- [ ] Activate the theme: no error, and Trades (6), Videos (3), FAQ (4),
      Glossary (4) appear in the admin menu already filled in.
- [ ] The front page shows every section with pictures and icons.
- [ ] Trade tabs switch by mouse and by arrow keys; FAQ items open and close.
- [ ] The planner produces a plan; Reset clears it.
- [ ] Submit the contact form empty → a polite error. Submit it filled →
      a thank-you, a mail, and an entry under Enquiries.
- [ ] Appearance → Customize: change the hero headline, publish, see it live.
- [ ] Edit a trade: change its description, save, see it on the front page.
- [ ] Create a draft post and a page: both display correctly.
- [ ] On a phone-sized window: the menu drawer opens, closes with Escape,
      and no section scrolls sideways.
- [ ] If Elementor is installed: drop one section widget on a test page,
      edit its headline, publish, view.

Anything that fails here is a bug — send it back to be fixed, do not work
around it.

## Shipped content tops itself up

The trades, films, FAQ entries and glossary terms are posts, created the
first time the theme is activated. That used to be the only time it ever
ran, so a site activated last month never received anything added since.

It now runs whenever the theme version changes, and it is careful:

- An entry whose slug is missing is **created** — that is how a new trade
  or a new film reaches a site that is already running.
- A field on an existing entry is filled **only when it is currently empty**
  and the theme now ships something for it.
- Anything you have written is never replaced.

The one trade-off: a field you cleared deliberately and left empty may be
refilled by an update. If you want a field to stay blank, put a space in it.

## Updating the theme safely

Theme updates replace the theme folder, so **never edit theme files
directly** on a live site — those edits are lost on the next update.

- Wording and pictures → the Customizer (survives updates).
- Extra CSS → Appearance → Customize → Additional CSS (survives updates).
- Code changes → a child theme, or send them upstream to this repository so
  the next zip carries them.

The shipped content is seeded only once. If entries were deleted and the
originals are wanted back, delete the `tsa_seeded` option (or ask a
developer to) and re-activate the theme: only the missing shipped entries
are recreated, and nothing you added or changed is touched.

## The hero background video

The hero is a still photograph until a video exists. To add one: upload an
MP4 to **Media**, copy its URL, and paste it into **Customize → Hero →
Background video → Video files → Path** (type `video/mp4`). A WebM copy in
the second slot is nice for size but optional. The video autoplays muted,
honours "reduce motion" settings, and the photograph remains the fallback
and the poster.

---

## Files

```
themes/tradeskillapps/
  style.css              the design system, with the fonts self-hosted
  functions.php          setup and asset loading
  front-page.php         the one-page site, in section order
  page.php single.php    standalone pages, same design
  template-full-width.php  a page that controls its own layout
  index.php 404.php
  header.php footer.php
  template-parts/        one file per section
  inc/
    defaults.php         every shipped string (generated)
    helpers.php          how templates read content
    customizer.php       the editing screen, generated from the defaults
    post-types.php       trades, videos, FAQ, glossary
    contact.php          the enquiry form (rate-limited; stores Enquiries)
    icons.php            the SVG sprite and placeholder art
    elementor.php        Elementor support; does nothing without it
    elementor/
      class-section-widget.php   each section, as a draggable widget
      page-builder.php           builds the home page as an Elementor page
  assets/
    css/elementor.css    loaded only when Elementor is active
    fonts/               Barlow Condensed and DM Sans, self-hosted (OFL.txt)
    images/              the site's photography
    js/site.js           tabs, accordions, planner, videos, menu
```

No file loads anything from another server. The fonts are local, the icons are
inline, and the only outside request the site can make is a YouTube embed, and
only after someone presses play.

---

## Notes for whoever maintains it

- The theme needs PHP 7.4+ and WordPress 6.0+.
- The JavaScript is plain ES5-era script with no build step and no
  dependencies. Edit `assets/js/site.js` directly.
- `style.css` is the original stylesheet with a font block prepended. The
  design tokens are the `--` variables at the top.
- The Customizer is generated by walking `tsa_defaults()`, so adding a string
  to the defaults gives it a field automatically. There is a test for this in
  the repository's development notes.
