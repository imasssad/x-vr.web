# TradeSkillApps — WordPress theme

`themes/tradeskillapps/` is the site as a self-contained WordPress theme. It
carries the same design, the same words and the same pictures as the original
build, and everything on the page can be changed from the WordPress admin.

Nothing else is required: no page builder, no paid plugin, no setup wizard.
Activate it and the finished site is there.

---

## Installing

1. **Appearance → Themes → Add New → Upload Theme**
2. Choose `tradeskillapps.zip`
3. **Install Now**, then **Activate**

On activation the theme creates the six trades, three video slots, four FAQ
entries and four glossary terms it ships with. That happens once; it never
overwrites anything you have changed.

4. **Settings → Reading → Your homepage displays → A static page**, and pick a
   page (an empty one is fine — the theme supplies the content).

> If the home page shows a list of blog posts instead of the site, step 4 is
> the one that was missed.

---

## Where each thing is edited

| What | Where |
| --- | --- |
| Every heading, paragraph, button and picture | **Appearance → Customize → TradeSkillApps content** |
| The six trade cards | **Trades** in the main admin menu |
| The three video slots | **Videos** |
| Questions and answers | **FAQ** |
| Glossary terms | **Glossary** |
| Where enquiries are emailed | **Customize → Contact form → Send enquiries to** |

The Customizer panel has 22 sections in the order they appear down the page,
so finding a piece of text means scrolling to roughly where you see it.

Collections are posts because you add to and remove from them: a seventh
trade is **Add Trade**, and dragging the **Order** field reorders them.

---

## How the content works

Every string has a shipped default, held in
`inc/defaults.php`. The templates read through `tsa_text()`, which returns the
Customizer value if there is one and the default otherwise.

Two consequences worth knowing:

- A fresh install already looks like the finished site. Nothing has to be
  imported or pasted in.
- Clearing a field restores the original wording rather than leaving a gap.

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

Always fill in the screen-reader description. It is read aloud to people who
cannot see the image, and search engines use it.

---

## Videos

Each video slot takes **either** a YouTube id **or** a file URL. The YouTube
route is easier: copy the part of the address after `v=` and paste it in.
Until a slot has one or the other it shows a "Film coming soon" marker, which
is deliberate — better than an empty rectangle.

Nothing is embedded until a visitor presses play, so an unplayed video costs
nothing.

---

## The contact form

Enquiries are sent with `wp_mail()`, so whatever the site already uses for
email is what gets used. On shared hosting that is usually the host's mail
service; an SMTP plugin, if one is installed, is picked up automatically.

The form has a hidden field people never see and most bots fill in; a
submission that fills it is quietly discarded. Messages are validated on the
server, and the sender's name is stripped of anything that could tamper with
the mail headers.

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

The widget panel has a **TradeSkillApps** heading holding all seventeen
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

The home page is a PHP template, not an Elementor document. It is faster that
way and it is what the Customizer edits. To rebuild it in Elementor instead,
make a new page, set the full-width template, drag the seventeen section
widgets onto it in order, and point **Settings → Reading** at that page. The
Customizer keeps working either way.

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
    contact.php          the enquiry form
    icons.php            the SVG sprite and placeholder art
    elementor.php        Elementor support; does nothing without it
    elementor/
      class-section-widget.php   each section, as a draggable widget
  assets/
    css/elementor.css    loaded only when Elementor is active
    fonts/               Barlow Condensed and DM Sans, self-hosted
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
