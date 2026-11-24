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
