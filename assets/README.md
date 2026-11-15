# /assets — what to drop in

Every file below is referenced by `index.html`. Until a file exists, the page
shows generated placeholder artwork in its place — nothing breaks, and no code
change is needed when you add the real file. **Keep the filenames exactly as
listed** (or update the matching `src` in `index.html`).

## Hero video (full-width, autoplay, muted, loop)
| File | Notes |
|---|---|
| `hero.mp4` | H.264, 1920×1080, ~8–15s loop, no audio track needed. Target under 6 MB. |
| `hero.webm` | Optional VP9/AV1 version — served first when the browser supports it. |
| `hero-poster.jpg` | First frame, 1920×1080, shown before the video paints. |

If no video is present, a canvas motion background (perspective grid + drifting
embers) runs instead and the "Play with sound" control hides itself.

## Photography
| File | Where it appears | Suggested crop |
|---|---|---|
| `approach-classroom.webp` | Our Approach, full width | 1600×700 (16:7) |
| `educators.webp` | For Educators, full width | 1600×650 (16:6.5) |
| `trade-welding.webp` | Welding card | 1280×640 (16:8) |
| `trade-hvac.webp` | HVAC card | 1280×640 |
| `trade-electrical.webp` | Electrical card | 1280×640 |
| `trade-manufacturing.webp` | Manufacturing card | 1280×640 |
| `trade-construction.webp` | Construction card | 1280×640 |
| `trade-emerging.webp` | Emerging Technology card | 1280×640 |

All photo slots are already `loading="lazy"` + `decoding="async"` with width and
height set, so they will not shift the layout.

**Compressing:** export WebP at quality 72–80. Aim for under 180 KB each.
```
cwebp -q 78 -resize 1280 0 source.jpg -o trade-welding.webp
```

**Alt text** lives in `index.html` next to each `src`. Update it to describe the
photo you actually use — this is the main accessibility touchpoint on the page.

## Social + icons
| File | Notes |
|---|---|
| `og-image.jpg` | 1200×630, used by Open Graph and Twitter cards. |
| `favicon.svg` | Supplied. Replace with the real logo mark when it exists. |
| `apple-touch-icon.png` | Supplied, 180×180. Regenerate from the real mark. |

## Brand colours
The single accent is defined once in `index.html` as `--accent: #ff6b1a`
(with `--accent-soft` and `--accent-dim` derived from it). Change those three
values in `:root` to re-brand the whole page.
