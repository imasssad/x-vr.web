"use client";
import { useState } from "react";

type State = { kind: "idle" | "sending" | "sent" | "error"; msg?: string };

/* The Node build posts to its own API route. The static build has no server,
   so it posts to contact.php instead — set at build time. */
const ENDPOINT = process.env.NEXT_PUBLIC_CONTACT_ENDPOINT || "/api/contact";

export default function Contact() {
  const [s, setS] = useState<State>({ kind: "idle" });
  const [form, setForm] = useState({ name: "", email: "", message: "", company: "" });
  const set = (k: keyof typeof form) => (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) =>
    setForm((f) => ({ ...f, [k]: e.target.value }));

  const submit = async (e: React.FormEvent) => {
    e.preventDefault();
    setS({ kind: "sending" });
    try {
      const r = await fetch(ENDPOINT, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(form),
      });
      const data = await r.json().catch(() => ({ ok: false }));
      if (r.ok && data.ok) {
        setS({ kind: "sent" });
        setForm({ name: "", email: "", message: "", company: "" });
      } else {
        setS({ kind: "error", msg: data.error ?? "Could not send just now." });
      }
    } catch {
      setS({ kind: "error", msg: "Could not reach the server. Please try again." });
    }
  };

  return (
    <section className="band" id="contact" aria-labelledby="contact-h">
      <div className="wrap">
        <div className="sec-head sec-head--split">
          <div>
            <p className="eyebrow rv">Get in touch</p>
            <h2 id="contact-h" className="rv">Start a conversation. <em>We will reply.</em></h2>
          </div>
          <p className="lede rv rv-d1">
            Whether you are a learner, an instructor or a training program, tell us what you
            are trying to build and we will come back to you.
          </p>
        </div>

        <div className="planner rv">
          <form onSubmit={submit} noValidate>
            <div className="field">
              <label htmlFor="cname">Your name</label>
              <input className="textin" id="cname" name="name" value={form.name}
                onChange={set("name")} autoComplete="name" required />
            </div>
            <div className="field">
              <label htmlFor="cemail">Email</label>
              <input className="textin" id="cemail" name="email" type="email" value={form.email}
                onChange={set("email")} autoComplete="email" required />
            </div>
            <div className="field">
              <label htmlFor="cmsg">Message</label>
              <textarea className="textin" id="cmsg" name="message" rows={5} value={form.message}
                onChange={set("message")} required />
            </div>

            {/* honeypot: hidden from people, irresistible to bots */}
            <div aria-hidden="true" style={{ position: "absolute", left: "-9999px" }}>
              <label htmlFor="company">Company</label>
              <input id="company" name="company" tabIndex={-1} autoComplete="off"
                value={form.company} onChange={set("company")} />
            </div>

            <div className="planner__actions">
              <button className="btn btn--primary" type="submit" disabled={s.kind === "sending"}>
                {s.kind === "sending" ? "Sending…" : "Send message"}
                <span className="arw" aria-hidden="true">&rarr;</span>
              </button>
            </div>

            <p role="status" aria-live="polite" className="privacy">
              {s.kind === "sent" && <span>Thank you — your message is on its way.</span>}
              {s.kind === "error" && <span>{s.msg}</span>}
            </p>
          </form>
        </div>
      </div>
    </section>
  );
}
