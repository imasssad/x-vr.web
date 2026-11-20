"use client";
import { useState } from "react";
import { videos } from "@/content/site";
import Note from "./Note";

export default function Videos() {
  const [playing, setPlaying] = useState<string | null>(null);

  return (
    <section className="band band--white" id="videos" aria-labelledby="videos-h">
      <div className="wrap">
        <div className="sec-head sec-head--split">
          <div>
            <p className="eyebrow rv">{videos.eyebrow}</p>
            <h2 id="videos-h" className="rv">
              {videos.headline} <em>{videos.headlineEm}</em>
            </h2>
          </div>
          <p className="lede rv rv-d1">{videos.lede}</p>
        </div>

        <div className="videos">
          {videos.items.map((v, i) => {
            const ready = Boolean(v.file || v.embed);
            const isOn = playing === v.id;
            return (
              <article className={`vcard rv${i ? ` rv-d${i}` : ""}`} key={v.id}>
                <div className="vcard__media">
                  {isOn && v.embed && (
                    <iframe
                      src={`https://www.youtube-nocookie.com/embed/${v.embed}?autoplay=1`}
                      title={v.title} allow="autoplay; encrypted-media; picture-in-picture"
                      allowFullScreen loading="lazy" />
                  )}
                  {isOn && v.file && !v.embed && (
                    <video controls autoPlay playsInline poster={v.poster || undefined}>
                      <source src={`/assets/video/${v.file}`} />
                    </video>
                  )}
                  {!isOn && (
                    <button type="button" className="vcard__play"
                      onClick={() => ready && setPlaying(v.id)}
                      disabled={!ready}
                      aria-label={ready ? `Play: ${v.title}` : `${v.title} — film coming soon`}>
                      {v.poster && <img src={`/assets/video/${v.poster}`} alt="" aria-hidden="true" />}
                      <span className="vcard__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><use href="#i-play" /></svg>
                      </span>
                      {!ready && <span className="vcard__pending">Film coming soon</span>}
                    </button>
                  )}
                </div>
                <div className="vcard__body">
                  <h3>{v.title}</h3>
                  <p>{v.blurb}</p>
                </div>
              </article>
            );
          })}
        </div>

        <Note text={videos.note} style={{ maxWidth: "80ch" }} />
      </div>
    </section>
  );
}
