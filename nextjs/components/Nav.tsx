"use client";
import { useEffect, useRef, useState } from "react";
import { nav, drawerExtra } from "@/content/site";
import Logo from "./Logo";

export default function Nav() {
  const [stuck, setStuck] = useState(false);
  const [open, setOpen] = useState(false);
  const [current, setCurrent] = useState<string | null>(null);
  const burger = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    const onScroll = () => setStuck(window.scrollY > 20);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  /* active-section indication */
  useEffect(() => {
    const ids = nav.map((n) => n.href.slice(1));
    const sections = ids
      .map((id) => document.getElementById(id))
      .filter((el): el is HTMLElement => !!el);
    if (!sections.length || !("IntersectionObserver" in window)) return;
    const ratios = new Map<string, number>();
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((en) =>
          ratios.set(en.target.id, en.isIntersecting ? en.intersectionRatio : 0)
        );
        let best: string | null = null;
        let top = 0;
        ids.forEach((id) => {
          const r = ratios.get(id) ?? 0;
          if (r > top) { top = r; best = id; }
        });
        setCurrent(top > 0 ? best : null);
      },
      { rootMargin: "-45% 0px -45% 0px", threshold: [0, 0.25, 0.5, 1] }
    );
    sections.forEach((s) => io.observe(s));
    return () => io.disconnect();
  }, []);

  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape" && open) { setOpen(false); burger.current?.focus(); }
    };
    document.addEventListener("keydown", onKey);
    return () => document.removeEventListener("keydown", onKey);
  }, [open]);

  const mark = (href: string) =>
    current && href === `#${current}` ? { "aria-current": "true" as const } : {};

  return (
    <header className={`nav${stuck ? " is-stuck" : ""}`} id="nav">
      <div className="wrap nav__in">
        <a className="brand" href="#top" aria-label={`X-VR Skilled Trades — home`}>
          <Logo />
          <span className="brand__name">
            X&#8209;VR <span className="brand__rest">Skilled Trades</span>
          </span>
        </a>

        <nav aria-label="Primary">
          <ul className="nav__links" id="navLinks">
            {nav.map((n) => (
              <li key={n.href}><a href={n.href} {...mark(n.href)}>{n.label}</a></li>
            ))}
          </ul>
        </nav>

        <a className="btn btn--primary nav__cta" href="#start">
          Build your pathway <span className="arw" aria-hidden="true">&rarr;</span>
        </a>

        <button ref={burger} className="burger" type="button" aria-expanded={open}
          aria-controls="drawer" onClick={() => setOpen((v) => !v)}
          aria-label={open ? "Close navigation menu" : "Open navigation menu"}>
          <span className="burger__bars" aria-hidden="true" />
        </button>
      </div>

      <div className={`drawer${open ? " is-open" : ""}`} id="drawer">
        <ul>
          {[...nav, ...drawerExtra].map((n) => (
            <li key={n.href}>
              <a href={n.href} {...mark(n.href)} onClick={() => setOpen(false)}>{n.label}</a>
            </li>
          ))}
        </ul>
        <a className="btn btn--primary btn--block" href="#start" onClick={() => setOpen(false)}>
          Build your pathway <span className="arw" aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </header>
  );
}
