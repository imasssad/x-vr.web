import {
  ticker, purpose, approach, learningCycle, pathways,
  educators, levels, mission, glossary, access, faq, closing, footer,
} from "@/content/site";
import Note from "./Note";
import Logo from "./Logo";

type Img = { src: string; alt: string; srcSet?: string };

function Figure({ img, ratio, caption, style, className = "" }:
  { img: Img; ratio: string; caption?: string[]; style?: React.CSSProperties; className?: string }) {
  return (
    <figure className={`frame frame--zoom rv ${className}`} style={{ aspectRatio: ratio, ...style }}>
      {img.src
        ? <img src={img.src} srcSet={img.srcSet || undefined} alt={img.alt}
            loading="lazy" decoding="async"
            sizes={img.srcSet ? "(min-width:1280px) 1216px, 100vw" : undefined} />
        : <span className="frame__empty">Photograph to be supplied</span>}
      {caption && (
        <figcaption className="frame__cap">
          {caption.map((c, i) =>
            i === 1 ? <span key={i}><strong>{c}</strong></span>
                    : <span className="chip-label" key={i}>{c}</span>)}
        </figcaption>
      )}
    </figure>
  );
}

const Ticks = ({ items }: { items: string[] }) => (
  <ul className="ticks">
    {items.map((p) => (
      <li key={p}><svg aria-hidden="true"><use href="#i-check" /></svg><span>{p}</span></li>
    ))}
  </ul>
);

export const Ticker = () => (
  <div className="ticker" aria-hidden="true">
    <div className="ticker__track">
      {[0, 1].map((k) => (
        <span key={k}>
          {ticker.map((t) => (
            <span key={t} style={{ display: "contents" }}>{t} <i>&#9671;</i> </span>
          ))}
        </span>
      ))}
    </div>
  </div>
);

export const Purpose = () => (
  <section className="band" id="purpose" aria-labelledby="purpose-h">
    <div className="wrap">
      <div className="sec-head sec-head--split">
        <div>
          <p className="eyebrow rv">{purpose.eyebrow}</p>
          <h2 id="purpose-h" className="rv">{purpose.headline} <em>{purpose.headlineEm}</em></h2>
        </div>
        <p className="lede rv rv-d1">{purpose.lede}</p>
      </div>
      <p className="copy rv" style={{ marginBottom: "clamp(2.25rem,5vw,3.5rem)" }}>{purpose.body}</p>
      <div className="grid grid--3">
        {purpose.pillars.map((p, i) => (
          <article className={`card rv${i ? ` rv-d${i}` : ""}`} key={p.n}>
            <span className="pillar__n">{p.n}</span>
            <h3>{p.title}</h3>
            <p>{p.body}</p>
          </article>
        ))}
      </div>
    </div>
  </section>
);

export const Approach = () => (
  <section className="band" id="approach" aria-labelledby="approach-h">
    <div className="wrap">
      <div className="sec-head sec-head--split">
        <div>
          <p className="eyebrow rv">{approach.eyebrow}</p>
          <h2 id="approach-h" className="rv">{approach.headline} <em>{approach.headlineEm}</em></h2>
        </div>
        <p className="lede rv rv-d1">{approach.lede}</p>
      </div>
      <Figure img={approach.heroImage} ratio="16/7" caption={approach.heroImage.caption}
        style={{ margin: "0 0 clamp(2rem,5vw,3.25rem)" }} />
      {approach.body.map((b, i) => (
        <p className="copy rv" key={i}
          style={{ marginBottom: i === 0 ? "1rem" : "clamp(2.25rem,5vw,3.25rem)" }}>{b}</p>
      ))}
      <p className="eyebrow rv">{approach.lessonEyebrow}</p>
      <h3 className="rv" style={{ fontSize: "var(--t-sub)", marginBottom: "clamp(1.75rem,4vw,2.5rem)", textTransform: "uppercase" }}>
        {approach.lessonHeadline} <em>{approach.lessonHeadlineEm}</em>
      </h3>
      <ol className="process rv">
        {approach.steps.map((s) => (
          <li key={s.title}><h4>{s.title}</h4><p>{s.body}</p></li>
        ))}
      </ol>
      <Figure img={approach.practiceImage} ratio="16/7" caption={approach.practiceImage.caption}
        style={{ margin: "clamp(2rem,5vw,3rem) 0 0" }} />
      <Note text={approach.note} style={{ maxWidth: "80ch" }} />
    </div>
  </section>
);

export const LearningCycle = () => (
  <section className="band on-navy" id="learning" aria-labelledby="learning-h">
    <div className="wrap">
      <div className="sec-head">
        <div>
          <p className="eyebrow rv">{learningCycle.eyebrow}</p>
          <h2 id="learning-h" className="rv">{learningCycle.headline} <em>{learningCycle.headlineEm}</em></h2>
        </div>
      </div>
      <div className="cycle">
        {learningCycle.items.map((it, i) => (
          <article className={`cycle__i rv${i ? ` rv-d${i}` : ""}`} key={it.n}>
            <svg aria-hidden="true"><use href={`#${it.icon}`} /></svg>
            <span className="cycle__n">{it.n}</span>
            <h4>{it.title}</h4>
            <p>{it.body}</p>
          </article>
        ))}
      </div>
    </div>
  </section>
);

export const Pathways = () => (
  <section className="band" id="pathways" aria-labelledby="pathways-h">
    <div className="wrap">
      <div className="sec-head sec-head--split">
        <div>
          <p className="eyebrow rv">{pathways.eyebrow}</p>
          <h2 id="pathways-h" className="rv">{pathways.headline} <em>{pathways.headlineEm}</em></h2>
        </div>
        <p className="lede rv rv-d1">{pathways.lede}</p>
      </div>
      <Figure img={pathways.image} ratio="16/7" style={{ margin: "0 0 clamp(2.25rem,5vw,3.25rem)" }} />
      <ol className="timeline">
        {pathways.stages.map((s, i) => (
          <li className={`tl rv${i ? ` rv-d${i}` : ""}`} key={s.n}>
            <span className="tl__node" aria-hidden="true">{s.n}</span>
            <span className="tl__stage">{s.stage}</span>
            <h3>{s.title}</h3>
            <p>{s.body}</p>
            <Ticks items={s.points} />
          </li>
        ))}
      </ol>
      <Note text={pathways.note} style={{ maxWidth: "82ch" }} />
    </div>
  </section>
);

export const Educators = () => (
  <section className="band band--white" id="educators" aria-labelledby="educators-h">
    <div className="wrap">
      <div className="sec-head sec-head--split">
        <div>
          <p className="eyebrow rv">{educators.eyebrow}</p>
          <h2 id="educators-h" className="rv">{educators.headline} <em>{educators.headlineEm}</em></h2>
        </div>
        <p className="lede rv rv-d1">{educators.lede}</p>
      </div>
      <p className="copy rv" style={{ marginBottom: "clamp(2.25rem,5vw,3.5rem)" }}>{educators.body}</p>
      <div className="alt">
        {educators.steps.map((s) => (
          <div className="alt__row rv" key={s.n}>
            <figure className="frame alt__media frame--zoom">
              {s.image.src
                ? <img src={s.image.src} alt={s.image.alt} loading="lazy" decoding="async" />
                : <span className="frame__empty">Photograph to be supplied</span>}
            </figure>
            <div className="alt__body">
              <span className="alt__n" aria-hidden="true">{s.n}</span>
              <h3>{s.title}</h3>
              <p>{s.body}</p>
            </div>
          </div>
        ))}
      </div>
      <p className="rv" style={{ marginTop: "clamp(2rem,4.5vw,3rem)" }}>
        <a className="btn btn--primary" href={educators.cta.href}>
          {educators.cta.label} <span className="arw" aria-hidden="true">&rarr;</span>
        </a>
      </p>
    </div>
  </section>
);

export const Levels = () => (
  <section className="band" id="levels" aria-labelledby="levels-h">
    <div className="wrap">
      <div className="sec-head sec-head--split">
        <div>
          <p className="eyebrow rv">{levels.eyebrow}</p>
          <h2 id="levels-h" className="rv">{levels.headline} <em>{levels.headlineEm}</em></h2>
        </div>
        <p className="lede rv rv-d1">{levels.lede}</p>
      </div>
      <div className="bento">
        <article className="bento__card bento__card--light rv">
          <figure className="frame bento__media frame--zoom">
            {levels.highSchool.image.src
              ? <img src={levels.highSchool.image.src} alt={levels.highSchool.image.alt}
                  loading="lazy" decoding="async" />
              : <span className="frame__empty">Photograph to be supplied</span>}
          </figure>
          <div className="bento__body">
            <span className="bento__eye">{levels.highSchool.eye}</span>
            <h3>{levels.highSchool.title}</h3>
            <p>{levels.highSchool.body}</p>
            <Ticks items={levels.highSchool.points} />
          </div>
        </article>
        <article className="bento__card bento__card--navy on-navy rv rv-d1">
          <div className="bento__body">
            <span className="bento__eye">{levels.tradeSchool.eye}</span>
            <h3>{levels.tradeSchool.title}</h3>
            <p>{levels.tradeSchool.body}</p>
            <Ticks items={levels.tradeSchool.points} />
          </div>
        </article>
      </div>
      <Note text={levels.note} style={{ maxWidth: "82ch" }} />
    </div>
  </section>
);

export const Mission = () => (
  <section className="band mission on-navy" id="mission" aria-labelledby="mission-h">
    <div className="wrap mission__grid">
      <div>
        <p className="eyebrow rv">{mission.eyebrow}</p>
        <h2 id="mission-h" className="vh">Our mission</h2>
        <blockquote className="rv">{mission.quote} <em>{mission.quoteEm}</em></blockquote>
        {mission.body.map((b, i) => (
          <p className="copy rv rv-d1" key={i} style={i === 0 ? { marginTop: "1.75rem" } : undefined}>{b}</p>
        ))}
        <p className="rv rv-d2" style={{ marginTop: "1.9rem" }}>
          <a className="btn btn--primary" href={mission.cta.href}>
            {mission.cta.label} <span className="arw" aria-hidden="true">&rarr;</span>
          </a>
        </p>
      </div>
      <Figure img={mission.image} ratio="4/3" className="mission__media rv-d1" />
    </div>
  </section>
);

export const Access = () => (
  <section className="band band--wash" id="access" aria-labelledby="access-h">
    <div className="wrap">
      <div className="sec-head sec-head--split">
        <div>
          <p className="eyebrow rv">{access.eyebrow}</p>
          <h2 id="access-h" className="rv">{access.headline} <em>{access.headlineEm}</em></h2>
        </div>
        <p className="lede rv rv-d1">{access.lede}</p>
      </div>
      <p className="copy rv">{access.body}</p>
      <Figure img={access.image} ratio="16/7" style={{ margin: "clamp(1.75rem,4vw,2.5rem) 0 0" }} />
      <div className="access__fig">
        {access.cells.map((c, i) => (
          <div className={`access__cell rv${i ? ` rv-d${i}` : ""}`} key={c.title}>
            <h4>{c.title}</h4><p>{c.body}</p>
          </div>
        ))}
      </div>
      <p className="rv" style={{ marginTop: "clamp(1.75rem,4vw,2.5rem)" }}>
        <a className="btn btn--primary" href={access.cta.href}>
          {access.cta.label} <span className="arw" aria-hidden="true">&rarr;</span>
        </a>
      </p>
    </div>
  </section>
);

export const Closing = () => (
  <section className="band closing on-navy" aria-labelledby="closing-h">
    <div className="wrap closing__in">
      <ul className="chain rv">{closing.chain.map((c) => <li key={c}>{c}</li>)}</ul>
      <h2 id="closing-h" className="rv rv-d1">{closing.headline} <em>{closing.headlineEm}</em></h2>
      <a className="btn btn--primary rv rv-d2" href={closing.cta.href}>
        {closing.cta.label} <span className="arw" aria-hidden="true">&rarr;</span>
      </a>
      <Figure img={closing.image} ratio="16/7" className="rv-d3"
        style={{ margin: "clamp(2.5rem,6vw,4rem) auto 0", maxWidth: 980, borderColor: "rgba(255,255,255,.18)" }} />
    </div>
  </section>
);

export const Footer = () => (
  <footer className="foot">
    <div className="wrap">
      <div className="foot__in">
        <div>
          <a className="brand" href="#top"><Logo /><span className="brand__name">Trade<span className="brand__accent">Skill</span>Apps</span></a>
          <p className="foot__tag">{footer.tagline}</p>
        </div>
        <nav aria-label="Footer">
          <ul className="foot__links">
            {footer.links.map((l) => <li key={l.href}><a href={l.href}>{l.label}</a></li>)}
          </ul>
        </nav>
      </div>
      <div className="foot__base"><p>{footer.copyright}</p><p>{footer.strap}</p></div>
    </div>
  </footer>
);

export const Glossary = ({ children }: { children: React.ReactNode }) => (
  <section className="band band--white" id="language" aria-labelledby="language-h">
    <div className="wrap">
      <div className="sec-head sec-head--split">
        <div>
          <p className="eyebrow rv">{glossary.eyebrow}</p>
          <h2 id="language-h" className="rv">{glossary.headline} <em>{glossary.headlineEm}</em></h2>
        </div>
        <p className="lede rv rv-d1">{glossary.lede}</p>
      </div>
      {children}
    </div>
  </section>
);

export const Faq = ({ children }: { children: React.ReactNode }) => (
  <section className="band band--white" id="faq" aria-labelledby="faq-h">
    <div className="wrap">
      <div className="sec-head">
        <div>
          <p className="eyebrow rv">{faq.eyebrow}</p>
          <h2 id="faq-h" className="rv">{faq.headline} <em>{faq.headlineEm}</em></h2>
        </div>
      </div>
      {children}
    </div>
  </section>
);
