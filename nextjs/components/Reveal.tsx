"use client";
import { useEffect } from "react";

/**
 * Scroll reveal, mounted once.
 *
 * Marking elements from an effect rather than in the markup means the page
 * is fully visible in the HTML Next sends. If JavaScript never runs, nothing
 * is hidden — the animation is the enhancement, not the content.
 */
export default function Reveal() {
  useEffect(() => {
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const els = Array.from(document.querySelectorAll<HTMLElement>(".rv"));
    if (reduce || !("IntersectionObserver" in window)) return;

    els.forEach((el) => {
      // anything already on screen stays visible; the rest start hidden
      if (el.getBoundingClientRect().top >= window.innerHeight) {
        el.dataset.reveal = "pending";
      }
    });

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((en) => {
          if (en.isIntersecting) {
            (en.target as HTMLElement).dataset.reveal = "in";
            io.unobserve(en.target);
          }
        });
      },
      { rootMargin: "0px 0px -6% 0px", threshold: 0.1 }
    );
    els.forEach((el) => { if (el.dataset.reveal === "pending") io.observe(el); });
    return () => io.disconnect();
  }, []);

  return null;
}
