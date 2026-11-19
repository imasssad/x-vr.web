"use client";
import { useState } from "react";
import { roles, stages, tradeOptions, learner, instructor, closingStep, plannerCopy as c } from "@/content/planner";

export default function Planner() {
  const [role, setRole] = useState<string>(roles[0]);
  const [stage, setStage] = useState<string>(stages[0]);
  const [trade, setTrade] = useState("");
  const [plan, setPlan] = useState<{ title: string; steps: [string, string][] } | null>(null);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const raw = trade.trim();
    const t = raw || "your trade";
    const steps = role === "Learner" ? (learner[stage] ?? learner[stages[0]]) : instructor;
    setPlan({
      title: (role === "Learner" ? "Your starter plan — " : "Your program starting point — ") + (raw || "your trade area"),
      steps: [...steps.map(([a, b]) => [a, b.replace(/TRADE/g, t)] as [string, string]), closingStep],
    });
  };

  const reset = () => {
    setRole(roles[0]); setStage(stages[0]); setTrade(""); setPlan(null);
    (document.getElementById("tradeInput") as HTMLInputElement | null)?.focus();
  };

  return (
    <section className="band" id="start" aria-labelledby="start-h">
      <div className="wrap">
        <div className="sec-head sec-head--split">
          <div>
            <p className="eyebrow rv">{c.eyebrow}</p>
            <h2 id="start-h" className="rv">{c.headline} <em>{c.headlineEm}</em></h2>
          </div>
          <p className="lede rv rv-d1">{c.lede}</p>
        </div>

        <div className="planner rv">
          <form onSubmit={submit} noValidate>
            <fieldset className="field">
              <legend>{c.roleLabel}</legend>
              <div className="chips">
                {roles.map((r) => (
                  <label className="chip" key={r}>
                    <input type="radio" name="role" value={r} checked={role === r}
                      onChange={() => setRole(r)} />
                    <span>{r}</span>
                  </label>
                ))}
              </div>
            </fieldset>

            <div className="field">
              <label htmlFor="tradeInput">{c.tradeLabel}</label>
              <input className="textin" type="text" id="tradeInput" name="trade"
                autoComplete="off" list="tradeOptions" value={trade}
                onChange={(e) => setTrade(e.target.value)}
                placeholder="For example: welding, HVAC, electrical…" />
              <datalist id="tradeOptions">
                {tradeOptions.map((o) => <option key={o} value={o} />)}
              </datalist>
            </div>

            <fieldset className="field">
              <legend>{c.stageLabel}</legend>
              <div className="chips">
                {stages.map((s) => (
                  <label className="chip" key={s}>
                    <input type="radio" name="stage" value={s} checked={stage === s}
                      onChange={() => setStage(s)} />
                    <span>{s}</span>
                  </label>
                ))}
              </div>
            </fieldset>

            <div className="planner__actions">
              <button className="btn btn--primary" type="submit">
                {c.submit} <span className="arw" aria-hidden="true">&rarr;</span>
              </button>
              <button className="btn btn--quiet" type="button" onClick={reset}>
                <svg width="15" height="15" aria-hidden="true"><use href="#i-reset" /></svg> {c.reset}
              </button>
            </div>

            <p className="privacy">
              <svg aria-hidden="true"><use href="#i-lock" /></svg>
              <span>{c.privacy}</span>
            </p>
          </form>

          {plan && (
            <div className="plan-out">
              <div className="plan-out__box">
                <h3>{plan.title}</h3>
                <ol>
                  {plan.steps.map(([t, b], i) => (
                    <li key={i}><strong>{t}.</strong> {b}</li>
                  ))}
                </ol>
              </div>
            </div>
          )}
          <p className="vh" role="status" aria-live="polite">
            {plan ? "Your starter plan is ready, below the form." : ""}
          </p>
        </div>
      </div>
    </section>
  );
}
