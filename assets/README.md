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
Defined once in `index.html` under `:root`. Change these and the whole page follows.

| Token | Value | Role |
|---|---|---|
| `--paper` | `#F8F9FC` | warm white page background |
| `--paper-2` | `#FFFFFF` | cards |
| `--paper-3` | `#EEF2F8` | sunken panels |
| `--tint` | `#EFF6FF` | hover / highlight wash |
| `--navy` | `#14213D` | **primary** — headlines, body text, nav bar, footer, dark bands |
| `--navy-2` | `#1E2E52` | raised surface on a navy band |
| `--muted` | `#4C5A75` | secondary text |
| `--blue` | `#2563EB` | **secondary** — buttons, links, key highlights |
| `--cyan` | `#06B6D4` | **third** — icons, hover states, underlines, VR details |
| `--line` / `--line-strong` | `#DDE3EE` / `#B9C4D8` | borders |

Roughly 60% light background, 30% navy, 10% blue and cyan combined.

**One rule worth keeping:** cyan sits at 2.3:1 on white, so it is never used for
text. It carries icons, the eyebrow rules, hover edges, the ticker marks and the
accents on navy bands (where it reads at 6.6:1). Any text that needs the accent
uses `--blue` (4.9:1 on paper) or navy. The FAQ open/close control uses blue
rather than cyan for the same reason — it is the only thing marking that state.

## Fonts
DM Sans (body) and Barlow Condensed (labels, mark) match the live site.
Bricolage Grotesque (display) and Instrument Serif italic (emphasis) are added
for the headline treatment.
