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

export default function Page() {
  return (
    <>
      <Icons />
      <Nav />
      <main id="main">
        <span id="top" />
        <Hero />
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
