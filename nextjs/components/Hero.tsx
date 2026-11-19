"use client";
import { useEffect, useRef, useState } from "react";
import { hero } from "@/content/site";

export default function Hero() {
  const video = useRef<HTMLVideoElement>(null);
  const [ready, setReady] = useState(false);   // a real video file exists
  const [playing, setPlaying] = useState(true);
  const [muted, setMuted] = useState(true);

  useEffect(() => {
    const v = video.current;
    if (!v) return;
    const onCanPlay = () => {
      setReady(true);
      if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        v.pause(); setPlaying(false);
      }
    };
    v.addEventListener("canplay", onCanPlay, { once: true });
    return () => v.removeEventListener("canplay", onCanPlay);
  }, []);

  const togglePlay = () => {
    const v = video.current; if (!v) return;
    if (playing) { v.pause(); setPlaying(false); }
    else { v.play().catch(() => {}); setPlaying(true); }
  };
  const toggleSound = () => {
    const v = video.current; if (!v) return;
    const next = !muted;
    v.muted = next;
    if (!next) { v.play().catch(() => {}); setPlaying(true); }
    setMuted(next);
  };

  return (
    <section className="hero" aria-labelledby="hero-h">
      <div className="hero__media">
        {/* the photograph is the default; a supplied video fades in over it */}
        <img id="heroPoster" src={hero.image.src} srcSet={hero.image.srcSet} sizes="100vw"
          alt={hero.image.alt} width={hero.image.width} height={hero.image.height}
          decoding="async" fetchPriority="high" />
        <video ref={video} autoPlay muted loop playsInline preload="metadata"
          poster={hero.video.poster} aria-label={hero.video.label}
          style={{ position: "absolute", inset: 0, opacity: ready ? 1 : 0, transition: "opacity .6s ease" }}>
          {hero.video.sources.map((s) => <source key={s.src} src={s.src} type={s.type} />)}
        </video>
      </div>
      <div className="hero__scrim" aria-hidden="true" />

      {ready && (
        <div className="vidctl is-on">
          <button type="button" onClick={togglePlay} aria-pressed={playing}>
            <svg aria-hidden="true"><use href={playing ? "#i-pause" : "#i-play"} /></svg>
            <span>{playing ? "Pause" : "Play"}</span>
          </button>
          <button type="button" onClick={toggleSound} aria-pressed={!muted}>
            <svg aria-hidden="true"><use href={muted ? "#i-sound" : "#i-mute"} /></svg>
            <span>{muted ? "Play with sound" : "Mute"}</span>
          </button>
        </div>
      )}

      <div className="wrap hero__in">
        <p className="eyebrow rv">{hero.eyebrow}</p>
        <h1 id="hero-h" className="rv rv-d1">
          {hero.headline} <em>{hero.headlineEm}</em>
        </h1>
        <p className="lede rv rv-d2">{hero.lede}</p>
        <div className="hero__actions rv rv-d3">
          <a className="btn btn--primary" href={hero.primaryCta.href}>
            {hero.primaryCta.label} <span className="arw" aria-hidden="true">&rarr;</span>
          </a>
          <a className="btn btn--ghost" href={hero.secondaryCta.href}>
            {hero.secondaryCta.label} <span className="arw" aria-hidden="true">&rarr;</span>
          </a>
        </div>
        <div className="hero__strip rv rv-d3">
          <p>{hero.strip[0]}</p>
          <span className="dot" aria-hidden="true" />
          <p>{hero.strip[1]}</p>
        </div>
      </div>
    </section>
  );
}
