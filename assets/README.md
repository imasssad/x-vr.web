# /assets — what to drop in

Filenames below are referenced by `index.html`. Where a file is missing the page
shows generated placeholder artwork instead — nothing breaks, and no code change
is needed when you add the real file. **Keep the filenames exactly as listed**
(or update the matching `src` in `index.html`).

## Already in place
| File | Notes |
|---|---|
| `vr-classroom-800.webp` / `-1200` / `-1448` | The original classroom artwork from the live site, re-encoded and sized for `srcset`. Used in the hero and in Our Approach. 317 KB original → 57/93/114 KB. |
| `favicon.svg` | Brand mark: lime X on ink, skewed 7°. |
| `apple-touch-icon.png` | 180×180 version of the same mark. |

## Hero video (optional — plays over the photograph)
| File | Notes |
|---|---|
| `hero.mp4` | H.264, 1920×1080, ~8–15s loop. Target under 6 MB. |
| `hero.webm` | Optional VP9/AV1 version — tried first. |

The hero shows the classroom photograph by default. Add a video file and it
fades in over the photo, and the "Play with sound" control appears by itself.
If the photograph is ever removed too, a canvas motion background takes over.

## Still needed
| File | Where it appears | Suggested crop |
|---|---|---|
| `educators.webp` | For Educators, full width | 1600×650 (16:6.5) |
| `og-image.jpg` | Open Graph / Twitter card | 1200×630 |

## Compressing
Export WebP at quality 72–80, under ~180 KB each.
```
cwebp -q 78 -resize 1600 0 source.jpg -o educators.webp
```

**Alt text** lives in `index.html` beside each `src`. Update it to describe the
photo you actually use — it is the main accessibility touchpoint on the page.

## Brand colours
Defined once in `index.html` under `:root`, matching the live site:

| Token | Value | Use |
|---|---|---|
| `--paper` | `#f5f4ee` | page background |
| `--ink` | `#14272c` | text, dark bands, buttons |
| `--lime` | `#d5f56a` | the accent — button fills, highlights, dark-band accents |
| `--lime-deep` | `#4e6a0f` | the accent as small text, where lime alone would not meet AA |
| `--line` / `--line-strong` | `#d8ddd5` / `#b9c0b4` | borders |
| `--muted` | `#536364` | secondary text |

Change those and the whole page follows. Note `--lime` is a light tint: it is
used as a *fill* behind ink text, never as text on paper.

## Fonts
DM Sans (body) and Barlow Condensed (labels, mark) match the live site.
Bricolage Grotesque (display) and Instrument Serif italic (emphasis) are added
for the headline treatment.
