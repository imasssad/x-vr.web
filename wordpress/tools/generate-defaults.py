#!/usr/bin/env python3
"""
Builds the theme's inc/defaults.php from the original site's content files.

Run from the repository root:   python3 wordpress/tools/generate-defaults.py

Everything the theme ships with comes from nextjs/content/data/*.json, plus
the handful of strings that lived inside components rather than content files
(the planner, the contact form, the menu button). Keeping this as a script
means the defaults can be regenerated rather than hand-edited.
"""
import json, pathlib, subprocess, sys

ROOT = pathlib.Path(__file__).resolve().parents[2]
OUT  = ROOT / "wordpress/themes/tradeskillapps/inc/defaults.php"


def php(v, ind=1):
    pad = "\t" * ind
    if isinstance(v, dict):
        if not v:
            return "array()"
        return "array(\n" + "".join(f"{pad}\t'{k}' => {php(x, ind+1)},\n" for k, x in v.items()) + pad + ")"
    if isinstance(v, list):
        if not v:
            return "array()"
        return "array(\n" + "".join(f"{pad}\t{php(x, ind+1)},\n" for x in v) + pad + ")"
    if isinstance(v, bool):
        return "true" if v else "false"
    if v is None:
        return "null"
    if isinstance(v, (int, float)):
        return str(v)
    return "'" + str(v).replace("\\", "\\\\").replace("'", "\\'") + "'"


def main():
    data = {}
    for f in sorted((ROOT / "nextjs/content/data").glob("*.json")):
        data.update(json.loads(f.read_text()))
    if not data:
        sys.exit("no content files found")

    planner_json = ROOT / "wordpress/tools/planner.json"
    pl = json.loads(planner_json.read_text())

    # Strings that were written into components instead of the content files.
    data["navCta"] = {"label": "Build your pathway", "href": "#start"}

    # Theme-specific overrides. The videos note in the source content was a
    # message to the site owner ("paste a YouTube link... no developer") and
    # has no business on the public page; empty means the note is not shown.
    # How the opening screen is built. "split" is the layout the client's own
    # reference uses: a dark band with the words on the left, the photograph
    # bleeding off the right edge, and the weld film offered underneath.
    # "overlay" is the original - one full-bleed photograph with the words
    # over it. Both are kept, because the choice is the owner's.
    data["hero"]["layout"] = "split"
    data["hero"]["videoHeading"] = "Watch XVR Weld in action"
    # The film offered under the copy in the split hero. This is the weld
    # film the client supplied, which ships with the theme - not the
    # hero.video above, whose files were never supplied and so never render.
    data["hero"]["film"] = {
        "src": "/assets/video/xvr-weld.mp4",
        "type": "video/mp4",
        "poster": "/assets/video/xvr-weld-poster.webp",
        "label": "XVR Weld: students welding together in instructor-led multiplayer VR",
    }

    data["videos"]["note"] = ""
    # How the films are laid out: "feature" gives the first one the full
    # width of the column, "grid" makes them all the same size.
    data["videos"]["layout"] = "feature"
    # The weld film supplied by the client ships with the theme, so the
    # section has something real to lead with out of the box.
    data["videos"]["items"][0].update({
        "title": "Watch XVR Weld in action",
        "blurb": "Learn the trades together in live, instructor-led multiplayer VR \u2014 see and "
                 "hear your instructor and classmates as you practise.",
        "file": "/assets/video/xvr-weld.mp4",
        "poster": "/assets/video/xvr-weld-poster.webp",
    })
    # og-image default path, editable in the Customizer and read by the theme.
    data["site"]["ogImage"] = "/assets/images/og-image.jpg"

    data["planner"] = dict(pl["plannerCopy"], **{
        "tradePlaceholder": "For example: welding, HVAC, electrical…",
        "roles": list(pl["roles"]),
        "stages": list(pl["stages"]),
        "tradeOptions": list(pl["tradeOptions"]),
        "learnerTitle": "Your starter plan — ",
        "instructorTitle": "Your program starting point — ",
        "fallbackTrade": "your trade",
        "fallbackTradeArea": "your trade area",
    })
    data["plannerSteps"] = {
        "learner": pl["learner"], "instructor": pl["instructor"], "closing": pl["closingStep"],
    }
    # Two existing lines said pricing was not published yet. The licensing
    # section below publishes the individual rate, so leaving them would make
    # the page contradict itself. Reworded to stay true, not deleted.
    data["access"]["cells"][1]["body"] = (
        "The individual annual licence is published on this page. What a school or "
        "workforce program pays depends on the learning package, headsets and "
        "deployment needs, so site licences are quoted per school."
    )
    data["faq"]["items"][2]["a"] = (
        "The individual annual licence is published on this page. Prices for schools and "
        "programs depend on the package, headsets and deployment needs, and are quoted "
        "per site. Very affordable access remains a core commitment."
    )

    # --- Sections taken from the client's XVR reference page -------------
    # The MAT-VR curriculum outline, summarised from the supplied guide.
    data["curriculum"] = {
        "eyebrow": "Manufacturing & automation / MAT-VR curriculum",
        "headline": "Understand the systems.",
        "headlineEm": "Build the technical foundation.",
        "lede": "A structured learning pathway connecting electrical theory, mechanical "
                "technology, and industrial automation with practical projects and virtual "
                "MAT / safety labs.",
        "stats": [
            {"value": "10", "label": "courses"},
            {"value": "140", "label": "total hours"},
            {"value": "14", "label": "hours per course"},
            {"value": "", "label": "Virtual MAT / safety labs"},
        ],
        "body": "From understanding a circuit to troubleshooting an automated system, learners "
                "explore how manufacturing equipment works \u2014 and how technicians maintain it.",
        "courses": [
            {"codes": "MAT 001 \u00b7 MAT 002 \u00b7 MAT 005",
             "title": "Electrical foundations & advanced AC",
             "body": "Study direct and alternating current, circuit components, basic "
                     "calculations, motors, relays, and resistors. Progress to three-phase "
                     "circuits, transformers, electrical measurements, troubleshooting, and an "
                     "introduction to NEC codes and standards."},
            {"codes": "MAT 003",
             "title": "Mechanical technology & shop fundamentals",
             "body": "Explore manufacturing work, safety precautions, hand and power tools, "
                     "fasteners, common mechanical components, and basic maintenance practices."},
            {"codes": "MAT 004 \u00b7 MAT 006 \u00b7 MAT 007",
             "title": "PLCs, programming & motor controls",
             "body": "Build familiarity with programmable logic controllers, digital multimeters, "
                     "and human-machine interfaces. Apply wiring, programming, assembly, and "
                     "troubleshooting concepts through projects, then explore PLC diagrams and "
                     "control of DC, AC, and three-phase motors."},
            {"codes": "MAT 008 \u00b7 MAT 009",
             "title": "Heat transfer & system troubleshooting",
             "body": "Learn conduction, convection, radiation, energy conversion, and industrial "
                     "thermostat systems. Explore fluid-system troubleshooting and PLC systems "
                     "that use HMI indicators and operator controls."},
            {"codes": "MAT 010",
             "title": "Industrial electronics & soldering",
             "body": "Develop familiarity with electronic components, soldering tools, and "
                     "printed circuit board projects that connect DC electrical concepts to "
                     "industrial applications."},
        ],
        "practiceEyebrow": "Education connected to practice",
        "practiceHeadline": "Learn the concept.",
        "practiceHeadlineEm": "Apply the process.",
        "practiceBody": "The guide pairs technical instruction with project-based learning and "
                        "lists a virtual MAT / safety lab for every course. Use immersive "
                        "learning alongside instructor-guided practical work to strengthen "
                        "understanding and prepare for further skill development.",
        "note": "Summary of the supplied MAT-VR Curriculum Guide. The 140 hours are the sum of "
                "ten 14-hour courses. Course availability and delivery arrangements should be "
                "confirmed with the program; this outline does not promise a certification.",
    }

    # Live, instructor-led multiplayer VR: people share one virtual shop.
    data["multiplayer"] = {
        "eyebrow": "Live multiplayer instruction",
        "headline": "Learn together",
        "headlineEm": "inside the virtual shop.",
        "lede": "Everyone joins the same virtual training space. You can see and hear your "
                "instructor and classmates inside VR.",
        "body": "Watch the instructor demonstrate a task, ask questions as they come up, and "
                "hear the feedback your classmates receive as they practice.",
        "steps": [
            {"n": "01", "icon": "i-learn", "title": "Learn",
             "body": "Watch the instructor demonstrate each step and explain why it matters."},
            {"n": "02", "icon": "i-rehearse", "title": "Practice",
             "body": "Try the procedure in VR and get feedback from your instructor as you work."},
            {"n": "03", "icon": "i-apply", "title": "Apply",
             "body": "Bring what you learned to the physical lab and practice with real tools."},
        ],
    }

    # Annual licensing. The figure comes from the client's own page.
    data["licensing"] = {
        "eyebrow": "Annual licensing",
        "headline": "What does it cost",
        "headlineEm": "for schools?",
        "lede": "Bring skilled trades education into your classroom with lessons, "
                "instructor-led VR activities, and skills assessments in welding, HVAC, "
                "advanced manufacturing, and drone technology.",
        "individual": {
            "label": "Individual",
            "price": "$199.99",
            "unit": "Per learner, per year",
        },
        "site": {
            "label": "School site license",
            "body": "Contact us for an annual quote covering unlimited users at one school site.",
            "cta": {"label": "Request school pricing", "href": "#contact"},
        },
        "note": "Prices are per annual licence. Figures shown are the published individual "
                "licence rate; site licences are quoted per school.",
    }

    data["contact"] = {
        "eyebrow": "Get in touch",
        "headline": "Start a conversation.",
        "headlineEm": "We will reply.",
        "lede": "Whether you are a learner, an instructor or a training program, tell us what you "
                "are trying to build and we will come back to you.",
        "nameLabel": "Your name",
        "emailLabel": "Email",
        "messageLabel": "Message",
        "submit": "Send message",
        "sending": "Sending…",
    }

    # --- The front page the client asked for ----------------------------
    # The client supplied xvr-skilled-trades.shermanmcleod.chatgpt.site as
    # "exactly what I wanted". That page is six sections between a white
    # header and footer; everything else this theme can draw now lives on
    # inner pages. The wording below is theirs, transcribed from the page.

    # Their menu is four anchors on the one page.
    data["nav"] = [
        {"label": "Programs", "href": "#trades"},
        {"label": "How it works", "href": "#multiplayer"},
        {"label": "Licensing", "href": "#licensing"},
    ]
    data["navCta"] = {"label": "For partners", "href": "#partners"}

    # Hero: no eyebrow, no buttons, no strip - the headline, the lede and
    # the weld film, exactly as on their page. The accent falls on the full
    # stop, which is how their headline is set.
    data["hero"]["eyebrow"] = ""
    data["hero"]["headline"] = "Train for the work ahead"
    data["hero"]["headlineEm"] = "."
    data["hero"]["lede"] = (
        "Learn the trades together in live, instructor-led multiplayer VR. See and hear "
        "your instructor, classmates and learn the skills to get into apprenticeships as "
        "you practice, ask questions and prepare for mastery."
    )
    data["hero"]["primaryCta"] = {"label": "", "href": ""}
    data["hero"]["secondaryCta"] = {"label": "", "href": ""}
    data["hero"]["strip"] = []

    # A short statement band, which this theme did not have.
    data["knowledge"] = {
        # empty, but present, so the Customizer and the widget both offer it
        "eyebrow": "",
        "headline": "Build expert",
        "headlineEm": "trade knowledge.",
        "body": "Build your trade skills in live, instructor-led VR classes. Prepare to "
                "enter a pre-apprenticeship, apply for a registered apprenticeship, get "
                "credentialed and/or continue your education or earn a workforce career "
                "opportunity.",
    }

    # The closing band, likewise new.
    data["partners"] = {
        "eyebrow": "Schools, employers and workforce partners",
        "headline": "Bring XVR to",
        "headlineEm": "your training program.",
        "body": "Give high school students, trade school learners and adult career changers "
                "a place to explore the trades and practice the fundamentals before "
                "entering the lab.",
        "cta": {"label": "Contact us", "href": "#contact"},
    }

    # Their trades block is four plain cards, not the tabbed explorer this
    # theme shipped. The explorer is kept and moves to the inner page, so
    # the section grew a layout choice rather than losing anything.
    data["tradesSection"]["eyebrow"] = "Training pathways"
    data["tradesSection"]["headline"] = "Explore"
    data["tradesSection"]["headlineEm"] = "the trades."
    data["tradesSection"]["lede"] = ""
    data["tradesSection"]["layout"] = "cards"
    data["tradesSection"]["limit"] = "4"

    # Their four cards, in their order, with their subtitles and wording.
    REF_TRADES = {
        "welding": ("VRWeld",
                    "Build familiarity with shop safety, joint preparation, blueprint "
                    "reading and groove and fillet welds in multiple positions."),
        "hvac": ("Heating and cooling",
                 "Identify system components, study the refrigeration cycle and work "
                 "through installation and service procedures."),
        "manufacturing": ("Production and automation",
                                   "Explore shop measurement, production workflows and the "
                                   "role of automation in manufacturing."),
        "drone-technology": ("Uncrewed aircraft systems",
                             "Explore aircraft components, preflight checks and "
                             "flight-planning fundamentals."),
    }
    # their card calls it "Advanced manufacturing"; ours said "Manufacturing"
    REF_NAMES = {"manufacturing": "Advanced manufacturing"}
    order = list(REF_TRADES.keys())
    for t in data["trades"]:
        if t["slug"] in REF_TRADES:
            t["title"], t["desc"] = REF_TRADES[t["slug"]]
        if t["slug"] in REF_NAMES:
            t["name"] = REF_NAMES[t["slug"]]
    data["trades"].sort(
        key=lambda t: order.index(t["slug"]) if t["slug"] in order else len(order)
    )

    # Their pricing block names the product; ours did not.
    data["licensing"]["headline"] = "What does XVR cost"
    data["licensing"]["headlineEm"] = "for schools?"
    data["licensing"]["lede"] = (
        "Bring skilled trades education into your classroom with XVR Trades lessons, "
        "instructor-led VR activities, and skills assessments in welding, HVAC, advanced "
        "manufacturing, and drone technology."
    )
    data["licensing"]["site"]["cta"]["href"] = "#partners"

    # The reference sets its headings in sentence case. This theme set
    # every h1 and h2 in condensed capitals. Which one is right is the
    # client's call, not a decision to bury in the stylesheet, so it is a
    # setting - and it defaults to the reference.
    data["site"]["headingStyle"] = "sentence"

    # The mobile drawer's extra links pointed at sections that are no
    # longer on the front page. They now point at the pages those sections
    # moved to; page.php resolves each slug to a real permalink.
    data["drawerExtra"] = [
        {"label": "How we teach", "href": "page:how-we-teach"},
        {"label": "Curriculum", "href": "page:curriculum"},
        {"label": "For educators", "href": "page:for-educators"},
        {"label": "Questions", "href": "page:questions-contact"},
    ]

    # Their footer names the institute.
    data["footer"]["tagline"] = "Philadelphia Technician Training Institute"
    data["footer"]["links"] = []

    OUT.write_text("""<?php
/**
 * Every string the site ships with.
 *
 * These are the DEFAULTS. The theme reads each one through tsa_text(), which
 * returns whatever the site owner has saved in the Customizer and falls back
 * to the value here. So a freshly activated theme already looks like the
 * finished site, and nothing is lost if a field is cleared.
 *
 * Generated by wordpress/tools/generate-defaults.py - edit in the Customizer,
 * or regenerate; do not hand-edit this file.
 */
defined('ABSPATH') || exit;

function tsa_defaults() {
\tstatic $d = null;
\tif ($d !== null) return $d;
\t$d = """ + php(data, 1) + """;
\treturn $d;
}
""")
    print(f"wrote {OUT.relative_to(ROOT)}: {len(data)} groups")


if __name__ == "__main__":
    main()
