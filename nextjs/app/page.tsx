import fs from "node:fs";
import path from "node:path";
import Icons from "@/components/Icons";
import Nav from "@/components/Nav";
import Hero from "@/components/Hero";
import Trades from "@/components/Trades";
import Planner from "@/components/Planner";
import Contact from "@/components/Contact";
import Accordion from "@/components/Accordion";
import {
  Ticker, Purpose, Approach, LearningCycle, Pathways,
  Educators, Levels, Mission, Access, Closing, Footer, Glossary, Faq,
} from "@/components/Sections";
import { glossary, faq } from "@/content/site";

/* Checked at build time: drop hero-video.mp4 into public/assets/video/ and
   rebuild, and the hero starts playing it. No code or config change. */
const hasVideo = ["hero-video.mp4", "hero-video.webm"].some((f) =>
  fs.existsSync(path.join(process.cwd(), "public", "assets", "video", f))
);

export default function Page() {
  return (
    <>
      <Icons />
      <Nav />
      <main id="main">
        <span id="top" />
        <Hero hasVideo={hasVideo} />
        <Ticker />
        <Purpose />
        <Trades />
        <Approach />
        <LearningCycle />
        <Pathways />
        <Educators />
        <Levels />
        <Mission />
        <Glossary>
          <Accordion idPrefix="gl" items={glossary.items.map((g) => ({ q: g.term, a: g.def }))} />
        </Glossary>
        <Planner />
        <Access />
        <Faq>
          <Accordion idPrefix="fq" items={faq.items.map((f) => ({ q: f.q, a: f.a }))} />
        </Faq>
        <Contact />
        <Closing />
      </main>
      <Footer />
    </>
  );
}
