/**
 * Every word on the site lives in content/data/*.json.
 * Those files are what the CMS at /admin edits; this module only gives them
 * types and re-exports them under the names the components already use.
 * Edit the JSON (or use the CMS) — not this file.
 */
import general from "./data/general.json";
import heroData from "./data/hero.json";
import purposeData from "./data/purpose.json";
import tradesData from "./data/trades.json";
import approachData from "./data/approach.json";
import videosData from "./data/videos.json";
import pathwaysData from "./data/pathways.json";
import educatorsData from "./data/educators.json";
import referenceData from "./data/reference.json";

export type Trade = {
  slug: string;
  name: string;
  icon: string;
  title: string;
  desc: string;
  skills: string[];
  goal: string;
  sample: string;
  image: { src: string; alt: string } | null;
};

export type VideoItem = {
  id: string;
  title: string;
  blurb: string;
  /** File in public/assets/video/. Leave empty until the file exists. */
  file: string;
  /** Poster frame in public/assets/video/. Optional. */
  poster: string;
  /** Or a YouTube id, used instead of a file. */
  embed: string;
};

export const site = general.site;
export const nav = general.nav;
export const drawerExtra = general.drawerExtra;
export const footer = general.footer;

export const hero = heroData.hero;
export const ticker = heroData.ticker;

export const purpose = purposeData.purpose;
export const mission = purposeData.mission;
export const closing = purposeData.closing;

export const tradesSection = tradesData.tradesSection;
export const trades = tradesData.trades as Trade[];

export const approach = approachData.approach;
export const learningCycle = approachData.learningCycle;

export const videos = { ...videosData.videos, items: videosData.videos.items as VideoItem[] };

export const pathways = pathwaysData.pathways;
export const levels = pathwaysData.levels;

export const educators = educatorsData.educators;
export const access = educatorsData.access;

export const glossary = referenceData.glossary;
export const faq = referenceData.faq;
