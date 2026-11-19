# X-VR Skilled Trades — asset and brand guide

`index.html` is self-contained: all HTML, CSS and JavaScript live in that one
file. Everything below is referenced from this `/assets` directory.

```
x-vr-skilled-trades/
├── index.html
└── assets/
    ├── images/        photography + social card
    ├── video/         hero video + poster
    └── favicon/       favicon.svg, apple-touch-icon.png
```

---

## 1. Assets that need to be supplied

Nothing here is a finished asset. Until a file exists, the page draws a
placeholder panel that says **"Photography to be supplied"** — it is deliberately
honest rather than decorative. Add the file at the exact path below and the
photograph appears; no code change is needed.

### Photography — `assets/images/`

| File | Section | Crop | Subject |
|---|---|---|---|
| `hvac.webp` | Explore Trades → HVAC | 1280×560 | HVAC technician at a heating / ventilation unit |
| `electrical.webp` | Explore Trades → Electrical | 1280×560 | Student at a wiring training board |

**Already supplied:**
- `hero-vr-800/1200/1448.webp` — the original classroom artwork, re-encoded for `srcset`. Hero and Our Approach.
- `educators-01.webp` — instructor and student in an engineering technology centre.
- `emerging-technology.webp` — students with robotics equipment in a lab.
- `mission.webp` — graduates before a commencement ceremony.
- `educators-02.webp` — two welding students with helmet and gloves.
- `welding.webp` — a student welding a steel frame, arc and sparks visible.
- `manufacturing.webp` — a welded test coupon checked against a gauge.
- `approach-practice.webp` — a student welding at the bench, in Our Approach.
- `pathways.webp` — students finishing a concrete pad, in Career Pathways.
- `access-equipment.webp` — the shop floor of machines, in Access.
- `closing.webp` — three welding students and their instructor, in the closing CTA.
- `construction.webp` — students laying concrete block in the masonry yard.
- `educators-03.webp` / `-04.webp` — a prepared classroom and render practice walls.
- `high-schools.webp` — a student in the institute corridor.
- `video/hero-poster.webp` — a student torch-cutting, the poster frame for the hero video.

Sixteen distinct photographs are in use. No image is repeated: the commencement
photograph carries the Mission section, so it is deliberately not reused on the
High schools card.

Those three came from the client's own photography. Because they show
identifiable people, confirm you hold their permission to use the images
commercially before the site goes live.
`og-image.jpg` — the 1200×630 link-preview card, composed from the logo, the
hero headline and that photograph.

### Video — `assets/video/`

| File | Notes |
|---|---|
| `hero-video.mp4` | H.264, 1920×1080, ~8–15s seamless loop. Target under 6 MB. |
| `hero-video.webm` | Optional VP9/AV1 version — tried first. |
| `hero-poster.webp` | First frame, 1920×1080. |

The hero shows the classroom photograph by default. When a video file is added
it fades in over the photograph and the play/pause + sound controls appear on
their own. If the photograph is ever removed as well, a CSS/JS motion
background takes over as a last resort.

### Preparing images
Export WebP at quality 72–80, under ~180 KB each. AVIF is also fine — change the
extension in the matching `<img src>`.
```
cwebp -q 78 -resize 1280 0 source.jpg -o welding.webp
```
Every slot already has `loading="lazy"` (except the hero, which is
`fetchpriority="high"` and preloaded), `decoding="async"`, explicit `width`/
`height` to prevent layout shift, and `object-fit: cover`.

**Alt text** sits beside each `src` in `index.html`. Update it to describe the
photograph you actually use — it is the main accessibility touchpoint.

---

## 2. Logo and favicon

The mark is a **navy hexagon carrying a faceted four-point star**, with a small
electric-blue diamond at its centre.

Why this shape:
- the **hexagon** is the language of engineering — a bolt head, a machined
  fitting, a cut stone — which places the brand in the trades rather than in
  generic software;
- the **star** is an X, but drawn as four tapered blades rather than two bars.
  The top-left/bottom-right diagonal is deliberately longer and broader than the
  other, so it reads as an X rather than a compass rose;
- the **blue diamond** at the centre is an aperture — a viewport, the thing you
  look through into the virtual lesson. It is the only accent colour in the
  mark, which keeps it precious.

It was drawn against alternatives at 16, 24, 34, 64 and 120px on light and dark.
Earlier woven-bar and pinwheel versions collapsed into a "%" or a flower at
small sizes; the tapered blades hold their shape down to 16px.

| File | Use |
|---|---|
| `favicon/logo-mark.svg` | primary mark — navy hexagon, white star (light backgrounds) |
| `favicon/logo-mark-dark.svg` | inverted — white hexagon, navy star (dark backgrounds) |
| `favicon/favicon.svg` | the primary mark, served as the favicon |
| `favicon/apple-touch-icon.png` | 180×180 app-icon variant: full-bleed navy square with the star, no hexagon, so iOS applies its own rounding |
| `favicon/logo-lockup-light.png` | mark + wordmark for light backgrounds |
| `favicon/logo-lockup-dark.png` | mark + wordmark for dark backgrounds |

In the page the mark is **inline SVG** (search `brand__mark` — header and
footer) so it costs no extra request and stays crisp at any size. The wordmark
beside it is **live text** in Barlow Condensed 800, not an image, so it remains
selectable, searchable and sharp.

**Clear space:** keep at least one third of the mark's width free on all sides.

**Minimum size:** 20px for the mark on screen; 16px works for the favicon. Below
that, use the app-icon variant, whose star is larger in the frame.

**On photography or busy backgrounds:** use a lockup on a solid panel rather
than placing the mark directly on the image — the hexagon's silhouette is doing
the work and needs a clean edge.

**To replace the mark with other artwork:**
1. Swap the inline `<svg class="brand__mark">` in both places in `index.html`,
   keeping `class`, `viewBox` and `aria-hidden="true"`.
2. Replace `favicon/favicon.svg`, `logo-mark.svg` and `logo-mark-dark.svg`.
3. Re-export `apple-touch-icon.png` at 180×180 and both lockups.

The lockup PNGs and `og-image.jpg` are renders of live markup — if the mark or
the wordmark changes, re-export them rather than editing the PNGs.

## 3. Brand specification

### Colour — all defined once in `:root`
| Token | Value | Role |
|---|---|---|
| `--cloud` | `#F5F8FC` | primary background |
| `--white` | `#FFFFFF` | cards, secondary background |
| `--navy` | `#15283F` | primary text, dark surfaces |
| `--navy-2` | `#1E3550` | raised surface on a navy band |
| `--blue` | `#2864E8` | primary accent — CTAs, links, numbered markers, highlights |
| `--blue-dark` | `#1C4FC2` | accent hover / pressed |
| `--ice` | `#B9D5F5` | secondary accent — fills, connectors, accent text on navy |
| `--ice-wash` | `#EDF3FD` | soft blue background treatment |
| `--slate` | `#64748B` | supporting text |
| `--slate-aa` | `#5C6B82` | supporting text on `--cloud` and `--ice-wash` (see note) |
| `--border` | `#E2E8F0` | borders |

Distribution is roughly 60% light background, 20% navy surfaces, 15%
photography, 5% blue accents.

> **Note on `--slate`.** `#64748B` measures 4.47:1 on `#F5F8FC`, just under the
> 4.5:1 WCAG AA threshold for normal text. It passes on pure white (4.76:1), so
> it is used there, and `--slate-aa` (`#5C6B82`, 5.05:1) carries supporting text
> on the cloud and ice-wash backgrounds. If you would rather use one value
> everywhere, set `--slate` to `#5C6B82`.

> **Note on `--ice`.** At 1.5:1 on white it is a fill, never text. It is used for
> connectors, soft fills and accent text on navy bands (9.9:1 there). Numbered
> markers use `--blue`.

### Type
| Role | Family | Weight |
|---|---|---|
| Headlines | Barlow Condensed | 800, uppercase |
| Headline emphasis | Barlow Condensed Italic | 700 |
| Body | DM Sans | 400 |
| Eyebrows and labels | DM Sans | 500, uppercase, `.14em` tracking |

Sizes are responsive via `clamp()`: hero 48→100px, section 35→64px,
card 22→30px, body 16→18px, labels 11→13px.

---

## 4. Testing locally

The page uses no build step. Because it loads media over `file://`, serve it
rather than double-clicking:

```
python3 -m http.server 8000
```

Then open `http://localhost:8000`. Any static server works
(`npx serve`, `php -S localhost:8000`).

## 5. Deploying

**This is a static site.** One HTML file and a folder of images. There is no
build step, no database, no server code and no email.

That means the usual shared-hosting limits do not apply to it:

| Host says | Does it matter |
|---|---|
| Python not supported | No. Nothing here uses Python. |
| Node.js supported | Not needed. Node was never required to serve it. |
| SSH, not VPS/bare metal | Fine. SSH is only used to copy the files up. |
| Database | None required. |
| Email service | **Not required.** No contact form, no mailto, no newsletter. The Starter Planner runs entirely in the visitor's browser and submits nothing. |

### Uploading
Copy `index.html`, the `assets/` folder and `.htaccess` into the web root —
usually `public_html`, `htdocs` or `www`. Keep the folder structure exactly as
it is, or the image paths break.

```
scp -r index.html assets .htaccess user@host:~/public_html/
```

SFTP or the host's file manager work just as well; drag the same three items in.

`.htaccess` is optional. Apache and LiteSpeed read it, Nginx ignores it. It sets
the correct MIME type for `.webp` (some shared hosts still serve those as
plain text, which makes every photograph vanish), turns on compression, and
caches images for six months while keeping the HTML always fresh.

### The only outbound request
The visitor's browser fetches the four fonts from `fonts.googleapis.com`. If
that host is ever blocked for your audience, download the fonts into
`assets/` and swap the `<link>` for a local `@font-face` block.

### Before going live
1. Add the HVAC and electrical photographs, or leave the labelled placeholders.
3. Confirm you have permission to publish photographs of identifiable students
   and staff.
