/**
 * Single source of truth for every word on the site.
 *
 * Editing copy means editing this file — no component changes needed.
 * When a CMS is added, it populates this same shape, so nothing downstream
 * has to change.
 */

export const site = {
  domain: "https://tradeskillapps.com",
  name: "X-VR Skilled Trades",
  title: "X-VR Skilled Trades — Learn the trade. Build the skill. Change your future.",
  description:
    "X-VR Skilled Trades brings technical lessons to life through immersive VR education — connecting what students need to know with what they need to do. Education first. Affordability at the core.",
  ogImage: "/assets/images/og-image.jpg",
};

export const nav = [
  { label: "Explore trades", href: "#trades" },
  { label: "Our approach", href: "#approach" },
  { label: "Career pathways", href: "#pathways" },
  { label: "For educators", href: "#educators" },
];

export const drawerExtra = [
  { label: "Trade terminology", href: "#language" },
  { label: "Access", href: "#access" },
  { label: "Questions", href: "#faq" },
];

export const hero = {
  eyebrow: "The next dimension of trade education",
  headline: "Learn the trade. Build the skill.",
  headlineEm: "Change your future.",
  lede:
    "X-VR Skilled Trades brings technical lessons to life through immersive VR education — connecting what students need to know with what they need to do. Understand the tools. Practice the process. Prepare for hands-on work. All with very affordable access at the heart of our mission.",
  primaryCta: { label: "Find your trade", href: "#trades" },
  secondaryCta: { label: "Explore the vision", href: "#purpose" },
  strip: ["Education first. Affordability at the core.", "Experience that builds understanding."],
  image: {
    src: "/assets/images/hero-vr-1448.webp",
    srcSet:
      "/assets/images/hero-vr-800.webp 800w, /assets/images/hero-vr-1200.webp 1200w, /assets/images/hero-vr-1448.webp 1448w",
    alt: "Students wearing Meta Quest 3 headsets at desks in a skilled-trades classroom, holding controllers, with welding, HVAC, construction and manufacturing posters on the wall behind them",
    width: 1448,
    height: 1086,
  },
  video: {
    poster: "/assets/video/hero-poster.webp",
    sources: [
      { src: "/assets/video/hero-video.webm", type: "video/webm" },
      { src: "/assets/video/hero-video.mp4", type: "video/mp4" },
    ],
    label: "Students learning in a VR skilled-trades classroom",
  },
};

export const ticker = [
  "Welding", "HVAC", "Electrical", "Manufacturing", "Construction", "Emerging Technology",
];

export const purpose = {
  eyebrow: "01 / Our Purpose",
  headline: "Better education. Within reach.",
  headlineEm: "Within budget.",
  lede:
    "The case for better trade education is well established. The challenge has always been deployment — bringing effective instruction, equipment, practice and support to every learner who needs them.",
  body:
    "X-VR Skilled Trades is built to close the gap between knowing about a trade and understanding how the work happens. Our educational vision combines technical instruction, immersive practice, instructor guidance and real-world application. Students explore tools and systems, connect each step to its purpose, and prepare to apply that understanding under supervision in the shop.",
  pillars: [
    {
      n: "01",
      title: "Make learning affordable.",
      body: "Affordability is central to our vision, so more learners and training programs can access meaningful trade education.",
    },
    {
      n: "02",
      title: "Teach the thinking.",
      body: "Help learners explain what they are doing, why the sequence matters, and how to recognize when something needs attention.",
    },
    {
      n: "03",
      title: "Bring it to the shop.",
      body: "Turn learning into supervised practice with real tools and materials.",
    },
  ],
};

export type Trade = {
  slug: string;
  name: string;
  icon: string;
  title: string;
  desc: string;
  skills: string[];
  goal: string;
  sample: string;
  image: { src: string; alt: string; width: number; height: number } | null;
};

export const trades: Trade[] = [
  {
    slug: "welding", name: "Welding", icon: "i-weld",
    title: "Join materials. Build possibility.",
    desc: "Explore the science, preparation, and precision behind strong metal connections. Learn how process, technique, and inspection work together.",
    skills: ["Safety & PPE", "Joint preparation", "MIG fundamentals", "Blueprint reading"],
    goal: "Recognize common joint types and explain the purpose of joint preparation.",
    sample: "Explore a virtual welding workstation. Identify components, select the appropriate protective equipment, and explain the sequence for preparing a joint.",
    image: { src: "/assets/images/welding.webp", alt: "A welding student in a protective helmet and leather gloves welding a steel frame in a training shop, sparks coming off the arc", width: 1440, height: 630 },
  },
  {
    slug: "hvac", name: "HVAC", icon: "i-hvac",
    title: "Understand the systems behind comfort.",
    desc: "Explore heating, ventilation, and air conditioning through the relationships between airflow, heat transfer, and system components.",
    skills: ["System components", "Heat transfer", "Airflow", "Diagnostic thinking"],
    goal: "Identify major HVAC components and describe their roles in a system.",
    sample: "Trace airflow through a virtual system, identify major components, and explain how each contributes to indoor comfort.",
    image: null,
  },
  {
    slug: "electrical", name: "Electrical", icon: "i-elec",
    title: "Make the connection.",
    desc: "Build a foundation in circuit concepts, component recognition, diagrams, and safe work awareness before supervised practical instruction.",
    skills: ["Circuit concepts", "Diagram reading", "Component recognition", "Safety awareness"],
    goal: "Read a basic circuit diagram and identify its main components.",
    sample: "Explore a de-energized virtual circuit and trace the relationship between a source, protective device, switch, and load.",
    image: null,
  },
  {
    slug: "manufacturing", name: "Manufacturing", icon: "i-manu",
    title: "From an idea to a working process.",
    desc: "Explore how materials, machines, measurement, and people come together to produce consistent work.",
    skills: ["Measurement", "Process flow", "Quality awareness", "Automation concepts"],
    goal: "Describe a production sequence and identify where quality checks belong.",
    sample: "Walk through a virtual production sequence, identify inspection points, and explain how a measurement affects the next step.",
    image: { src: "/assets/images/manufacturing.webp", alt: "Hands checking a welded test coupon marked 3F SMAW against a gauge, with more finished coupons laid out on the steel bench", width: 1080, height: 472 },
  },
  {
    slug: "construction", name: "Construction", icon: "i-cons",
    title: "Shape the spaces around us.",
    desc: "Discover the craftsmanship behind carpentry, masonry, and building systems. Connect plans and measurement to materials and assembly.",
    skills: ["Plan reading", "Measurement", "Materials", "Assembly sequence"],
    goal: "Interpret basic plan dimensions and explain an assembly sequence.",
    sample: "Review a virtual room plan, identify dimensions and materials, and organize a basic assembly sequence for instructor discussion.",
    image: { src: "/assets/images/construction.webp", alt: "Students in hard hats laying concrete block in an outdoor masonry training yard", width: 1280, height: 560 },
  },
  {
    slug: "emerging-technology", name: "Emerging technology", icon: "i-tech",
    title: "Build skills for what comes next.",
    desc: "Explore the connections between skilled trades, robotics, drones, and digital systems, with an emphasis on foundational understanding.",
    skills: ["System awareness", "Robotics concepts", "Sensors", "Inspection planning"],
    goal: "Explain how sensors and digital tools can support a technician's work.",
    sample: "Explore a virtual inspection scenario, identify what information a sensor could collect, and discuss how a technician would use it.",
    image: { src: "/assets/images/emerging-technology.webp", alt: "Two students kneeling beside a humanoid robot and several wheeled rover robots on the floor of a technology lab", width: 1760, height: 770 },
  },
];

export const tradesSection = {
  eyebrow: "02 / Explore the Possibilities",
  headline: "Find the work",
  headlineEm: "that moves you.",
  lede: "Explore the trade areas shaping our educational vision. Select a field to see its foundations and a sample immersive learning experience.",
  note: "Learning experiences shown are **illustrative curriculum concepts**, not a catalog of currently available courses or certifications.",
};

export const approach = {
  eyebrow: "What makes this education?",
  headline: "A headset opens the door.",
  headlineEm: "The lesson builds the skill.",
  lede: "An immersive experience matters when it helps a student understand something they can use.",
  heroImage: {
    src: "/assets/images/hero-vr-1448.webp",
    srcSet: "/assets/images/hero-vr-800.webp 800w, /assets/images/hero-vr-1200.webp 1200w, /assets/images/hero-vr-1448.webp 1448w",
    alt: "Original artwork of young Black American students learning together with VR headsets in a skilled-trades classroom",
    width: 1448, height: 1086,
    caption: ["Meta Quest 3", "Real people. Real possibilities.", "AI-generated learning scene"],
  },
  body: [
    "Our approach starts with a learning objective. Each experience should connect a technical concept to a practical task, give students a chance to make decisions, and lead to a discussion about what they learned.",
    "For example, an HVAC learner can study a system, identify its components, trace the airflow, and explain what each part does. An instructor then connects that understanding to supervised work with real equipment.",
  ],
  lessonEyebrow: "Illustrative lesson / HVAC",
  lessonHeadline: "From “I see it” to",
  lessonHeadlineEm: "“I understand it.”",
  steps: [
    { title: "Learn the concept.", body: "Understand how air moves through a system." },
    { title: "Explore the environment.", body: "Locate components and follow the airflow." },
    { title: "Explain the reasoning.", body: "Describe each component's purpose in your own words." },
    { title: "Apply and demonstrate.", body: "Identify the same components on real equipment with an instructor." },
  ],
  practiceImage: {
    src: "/assets/images/approach-practice.webp",
    alt: "An instructor in welding gear showing a student a finished weld coupon in the training shop",
    width: 1440, height: 630,
    caption: ["Where the learning lands.", "Instructor-led review of real work"],
  },
  note: "VR prepares and reinforces. **Supervised hands-on training develops and verifies practical competence.**",
};

export const learningCycle = {
  eyebrow: "03 / Education drives the experience",
  headline: "More than seeing.",
  headlineEm: "Learning by doing.",
  items: [
    { n: "01 / Learn", icon: "i-learn", title: "Build the foundation.", body: "Start with technical vocabulary, safety awareness, tool recognition, and the principles behind each task." },
    { n: "02 / Rehearse", icon: "i-rehearse", title: "Make the connection.", body: "Explore a virtual work environment. Follow a sequence, identify an issue, and explain your decisions." },
    { n: "03 / Reflect", icon: "i-reflect", title: "Understand your next step.", body: "Review the experience with an instructor, identify gaps, and set a focus for the next practice session." },
    { n: "04 / Apply", icon: "i-apply", title: "Take it into the real world.", body: "Reinforce learning through supervised shop work and practical assessment." },
  ],
};

export const pathways = {
  eyebrow: "04 / Careers built over time",
  headline: "A starting point.",
  headlineEm: "A path forward.",
  lede: "Skilled trades grow through education, practice and experience. Explore common stages — and the learning that supports each one.",
  image: {
    src: "/assets/images/pathways.webp",
    alt: "Two students in hard hats kneeling to finish a freshly poured concrete pad on a training site",
    width: 1300, height: 569,
  },
  stages: [
    { n: "01", stage: "Explore & prepare", title: "Discover your direction.",
      body: "Start with interests, technical vocabulary, and an introduction to the work. Foundation learning can help you prepare for a training program or apprenticeship.",
      points: ["Explore different trades and work environments.", "Build safety awareness, measurement, and technical reading skills.", "Compare local training programs and entry requirements."] },
    { n: "02", stage: "Apprentice", title: "Learn alongside experienced people.",
      body: "An apprentice learns through structured on-the-job experience and related instruction. Program design and entry requirements depend on the trade and provider.",
      points: ["Develop foundational skills under qualified supervision.", "Connect classroom learning with workplace practice.", "Document progress against your program's requirements."] },
    { n: "03", stage: "Journeyman", title: "Develop independent competence.",
      body: "In trades that use this title, a journeyman has completed the required training and experience. Certification, licensing, and the scope of independent work vary.",
      points: ["Strengthen diagnosis, judgment, and consistency.", "Meet applicable qualification and continuing education requirements.", "Support newer learners and deepen your specialty."] },
    { n: "04", stage: "Master / contractor", title: "Lead work. Grow your impact.",
      body: "Master-level practice and contracting are distinct paths that may overlap. Experienced tradespeople may lead complex work, supervise crews, or operate a business.",
      points: ["Develop project planning, leadership, and quality oversight.", "Build business and customer communication skills.", "Verify any advanced licensing or contractor requirements."] },
  ],
  note: "Titles and requirements vary by occupation, employer and jurisdiction. Not every trade follows this sequence. **VR learning alone does not confer apprenticeship status, licensure or qualification.**",
};

export const educators = {
  eyebrow: "05 / For the people who teach",
  headline: "Great teaching.",
  headlineEm: "Very affordable access.",
  lede: "An education budget should open doors. Our goal is to make immersive trade learning a very affordable part of your teaching toolkit.",
  body: "Our deployment vision starts with your curriculum, your learners and your shop. Introduce immersive experiences where they serve a clear educational purpose, then connect them to instructor-led practice.",
  steps: [
    { n: "01", title: "Choose a learning goal", body: "Identify one concept or procedure students need to understand more clearly.",
      image: { src: "/assets/images/educators-01.webp", alt: "An instructor and a student standing together in an engineering technology centre, with workbenches, parts bins and an emergency eye-wash station behind them", width: 1140, height: 855 } },
    { n: "02", title: "Prepare the learning environment", body: "Plan headset access, space, accessibility, instructor support, and an alternative learning activity.",
      image: { src: "/assets/images/educators-03.webp", alt: "A training classroom set up for a session, tables laid out with boxed auto-darkening welding helmets and hard hats racked on the wall", width: 1140, height: 855 } },
    { n: "03", title: "Connect virtual and practical work", body: "Use a short immersive lesson, a guided discussion, and supervised shop practice.",
      image: { src: "/assets/images/educators-02.webp", alt: "Two welding students at a training institute, one holding an auto-darkening welding helmet and gloves", width: 1140, height: 855 } },
    { n: "04", title: "Evaluate and improve", body: "Compare understanding and practical performance, then refine the experience.",
      image: { src: "/assets/images/educators-04.webp", alt: "Students in hard hats applying render to practice wall panels inside a training structure", width: 1140, height: 855 } },
  ],
  cta: { label: "Plan your learning experience", href: "#start" },
};

export const levels = {
  eyebrow: "From your classroom to your future",
  headline: "Start with curiosity.",
  headlineEm: "Grow into a craft.",
  lede: "Very affordable immersive education can help bring trade exploration into high schools and strengthen technical learning in trade schools.",
  highSchool: {
    eye: "High schools / Discover your direction",
    title: "See what you could become.",
    body: "Connect science, math and classroom learning to the work of technicians and craftspeople. Explore trade environments, learn the language of tools and systems, and discover interests worth pursuing.",
    points: ["Career exploration before choosing a training pathway", "Technical vocabulary and foundational concepts", "Teacher-led reflection and a next-step learning plan"],
    image: { src: "/assets/images/high-schools.webp", alt: "A student standing in the corridor of a technician training institute, framed certificates and awards on the wall behind him", width: 1260, height: 709 },
  },
  tradeSchool: {
    eye: "Trade schools / Build your foundation",
    title: "Turn interest into understanding.",
    body: "Connect immersive lessons to shop instruction. Revisit a process, explain a sequence, and identify where you need more guidance before demonstrating your learning with real equipment.",
    points: ["Concept review alongside technical coursework", "Purposeful repetition and instructor discussion", "Preparation for supervised hands-on practice"],
  },
  note: "Learning examples describe our educational vision. The original classroom artwork shows the youth-focused learning environment that inspires X-VR Skilled Trades.",
};

export const mission = {
  eyebrow: "Talent is everywhere. Opportunity should be too.",
  quote: "See yourself",
  quoteEm: "building what comes next.",
  body: [
    "Black Americans belong at the center of the future of skilled trades — as technicians, craftspeople, instructors, innovators and business owners.",
    "X-VR Skilled Trades envisions an accessible path for learners from every background. Whether you are discovering a first career, changing direction, or strengthening an existing skill, the goal is the same: make meaningful education more affordable, more engaging, and easier to reach.",
  ],
  cta: { label: "Build your learning pathway", href: "#start" },
  image: { src: "/assets/images/mission.webp", alt: "Graduates in blue caps and gowns gathered together before a commencement ceremony", width: 1100, height: 825 },
};

export const glossary = {
  eyebrow: "06 / Know the language",
  headline: "Different terms.",
  headlineEm: "Shared purpose.",
  lede: "Specialized knowledge. Practical ability. Pride in the work.",
  items: [
    { term: "Skilled trades", def: "Occupations centered on specialized practical skills, technical knowledge, and hands-on work—such as welding, electrical work, HVAC, carpentry, and masonry." },
    { term: "Skilled labor", def: "A broader economic term for workers with specialized training, knowledge, or experience. Skilled trades are one part of this wider category." },
    { term: "Craftsmanship / craft", def: "The care, judgment, precision, and practiced ability behind quality work. Craftsmanship is visible in how a tradesperson plans, executes, and finishes a task." },
    { term: "Vocational or technical careers", def: "Careers developed through applied education and occupational training, including technical schools, trade schools, apprenticeships, and employer programs. Educational routes can overlap with college study." },
  ],
};

export const access = {
  eyebrow: "Built around access",
  headline: "Big possibilities.",
  headlineEm: "A very affordable way in.",
  lede: "High-quality trade education should be within reach of the people ready to learn — and the people ready to teach.",
  body: "X-VR Skilled Trades puts affordability at the center of its educational vision. Our goal is to help individual learners, technical schools, and workforce programs bring immersive learning into their training without making cost the barrier. Repeatable virtual practice creates opportunities to revisit a lesson before using shop time and physical materials.",
  image: { src: "/assets/images/access-equipment.webp", alt: "A training shop floor lined with drill presses, bench grinders and welding machines", width: 1500, height: 656 },
  cells: [
    { title: "Review before you consume", body: "Repeatable virtual practice creates opportunities to revisit a lesson before using shop time and physical materials." },
    { title: "Pricing", body: "Actual costs depend on the learning package, headsets, and deployment needs; specific pricing will be published separately." },
  ],
  cta: { label: "Explore your learning pathway", href: "#start" },
};

export const faq = {
  eyebrow: "A few things to know",
  headline: "Questions,",
  headlineEm: "answered.",
  items: [
    { q: "Does VR replace hands-on training?", a: "No. Our vision uses VR to prepare for and reinforce instructor-led learning. Real tools, supervised shop work, and practical assessment remain essential." },
    { q: "Can I earn a license or certification here?", a: "This website introduces the X-VR Skilled Trades vision and offers career exploration. It does not award credentials. Check requirements with the relevant training provider, licensing authority, or certifying organization." },
    { q: "Which products can I purchase today?", a: "Very affordable access is a core commitment of X-VR Skilled Trades. Specific prices, packages, product availability, and technical requirements are not published here yet. The trade experiences illustrate our educational direction." },
    { q: "Where should a school start?", a: "Choose one clear learning objective, identify how an immersive activity would support it, and plan the accompanying instruction and hands-on assessment. Use the starter planner above to organize an initial lesson." },
  ],
};

export const closing = {
  chain: ["Understanding", "Practice", "Proficiency", "Opportunity"],
  headline: "The future takes skill.",
  headlineEm: "Let's build it.",
  cta: { label: "Build your pathway", href: "#start" },
  image: { src: "/assets/images/closing.webp", alt: "Three welding students and their instructor in the shop, one holding a finished weld coupon", width: 1400, height: 612 },
};

export const footer = {
  tagline: "Better Education. Very Affordable. Within Reach.",
  links: [
    { label: "Trade terminology", href: "#language" },
    { label: "For educators", href: "#educators" },
    { label: "Get started →", href: "#start" },
  ],
  copyright: "© 2026 X-VR Skilled Trades",
  strap: "Education first. Opportunity ahead.",
};
