"use client";
import { useState } from "react";

export type AccItem = { q: string; a: React.ReactNode };

/** Used by both the glossary and the FAQ. Keyboard accessible by construction:
 *  the control is a real button and the panel is linked by aria-controls. */
export default function Accordion({ items, idPrefix, firstOpen = true }:
  { items: AccItem[]; idPrefix: string; firstOpen?: boolean }) {
  const [open, setOpen] = useState<Set<number>>(new Set(firstOpen ? [0] : []));
  const toggle = (i: number) =>
    setOpen((prev) => {
      const next = new Set(prev);
      next.has(i) ? next.delete(i) : next.add(i);
      return next;
    });

  return (
    <div className="acc rv">
      {items.map((it, i) => {
        const isOpen = open.has(i);
        return (
          <div className="acc__i" key={i}>
            <h3>
              <button className="acc__btn" type="button" id={`${idPrefix}-b${i}`}
                aria-expanded={isOpen} aria-controls={`${idPrefix}-p${i}`}
                onClick={() => toggle(i)}>
                <span>{it.q}</span>
                <span className="acc__pm" aria-hidden="true" />
              </button>
            </h3>
            <div className="acc__panel" id={`${idPrefix}-p${i}`} role="region"
              aria-labelledby={`${idPrefix}-b${i}`} data-open={isOpen}>
              <div><p>{it.a}</p></div>
            </div>
          </div>
        );
      })}
    </div>
  );
}
