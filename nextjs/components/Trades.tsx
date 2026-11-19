"use client";
import { useRef, useState } from "react";
import { trades, tradesSection } from "@/content/site";
import Note from "./Note";
import Placeholder from "./Placeholder";

export default function Trades() {
  const [active, setActive] = useState(0);
  const tabs = useRef<(HTMLButtonElement | null)[]>([]);

  const onKey = (e: React.KeyboardEvent, i: number) => {
    const last = trades.length - 1;
    let next: number | null = null;
    if (e.key === "ArrowDown" || e.key === "ArrowRight") next = i === last ? 0 : i + 1;
    else if (e.key === "ArrowUp" || e.key === "ArrowLeft") next = i === 0 ? last : i - 1;
    else if (e.key === "Home") next = 0;
    else if (e.key === "End") next = last;
    if (next !== null) { e.preventDefault(); setActive(next); tabs.current[next]?.focus(); }
  };

  return (
    <section className="band band--white" id="trades" aria-labelledby="trades-h">
      <div className="wrap">
        <div className="sec-head sec-head--split">
          <div>
            <p className="eyebrow rv">{tradesSection.eyebrow}</p>
            <h2 id="trades-h" className="rv">
              {tradesSection.headline} <em>{tradesSection.headlineEm}</em>
            </h2>
          </div>
          <p className="lede rv rv-d1">{tradesSection.lede}</p>
        </div>

        <div className="trades">
          <div className="tradelist rv" role="tablist" aria-label="Choose a trade" aria-orientation="vertical">
            {trades.map((t, i) => (
              <button key={t.slug} ref={(el) => { tabs.current[i] = el; }}
                className="tradebtn" role="tab" id={`tab-${t.slug}`}
                aria-controls={`panel-${t.slug}`} aria-selected={active === i}
                tabIndex={active === i ? 0 : -1} type="button"
                onClick={() => setActive(i)} onKeyDown={(e) => onKey(e, i)}>
                <svg className="tradebtn__ico" aria-hidden="true"><use href={`#${t.icon}`} /></svg>
                <span className="tradebtn__name">{t.name}</span>
                <svg className="tradebtn__chev" width="16" height="16" aria-hidden="true"><use href="#i-chev" /></svg>
              </button>
            ))}
          </div>

          <div>
            {trades.map((t, i) => (
              <article key={t.slug} className="tradepanel rv rv-d1" role="tabpanel"
                id={`panel-${t.slug}`} aria-labelledby={`tab-${t.slug}`} tabIndex={0}
                hidden={active !== i}>
                <figure className="frame tradepanel__media frame--zoom">
                  {t.image
                    ? <img src={t.image.src} alt={t.image.alt} loading="lazy" decoding="async"
                        width={t.image.width} height={t.image.height} />
                    : <Placeholder kind={t.slug} icon={t.icon} />}
                </figure>
                <div className="tradepanel__body">
                  <span className="chip-label chip-label--solid">Trade focus / {t.name}</span>
                  <h3>{t.title}</h3>
                  <p className="tradepanel__lead">{t.desc}</p>
                  <ul className="skills">{t.skills.map((s) => <li key={s}>{s}</li>)}</ul>
                  <div className="panel-split">
                    <div className="panel-box">
                      <span className="minilabel">Learning goal</span>
                      <p>{t.goal}</p>
                    </div>
                    <div className="panel-box">
                      <span className="minilabel">Sample immersive experience</span>
                      <p>{t.sample}</p>
                    </div>
                  </div>
                </div>
              </article>
            ))}
          </div>
        </div>

        <Note text={tradesSection.note} style={{ maxWidth: "78ch" }} />
      </div>
    </section>
  );
}
