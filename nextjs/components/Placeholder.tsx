/**
 * Stands in for a photograph that has not been supplied, and says so on its
 * face rather than pretending to be artwork. Pure SVG, no client JS.
 */
const MOTIF: Record<string, "sparks" | "flow" | "circuit" | "grid"> = {
  welding: "sparks", hvac: "flow", electrical: "circuit",
  manufacturing: "grid", construction: "grid", "emerging-technology": "circuit",
};
const BLUE = "#2864E8", NAVY = "#15283F", ICE = "#B9D5F5";

function motif(kind: string) {
  const out: React.ReactElement[] = [];
  if (kind === "sparks")
    for (let i = 0; i < 22; i++)
      out.push(<circle key={i} cx={`${6 + (i * 37) % 88}%`} cy={`${12 + (i * 53) % 76}%`}
        r={0.6 + (i % 4) * 0.5} fill={BLUE} opacity={(0.18 + (i % 5) * 0.08).toFixed(2)} />);
  else if (kind === "flow")
    for (let i = 0; i < 6; i++) {
      const oy = 18 + i * 12;
      out.push(<path key={i} d={`M-5,${oy} C 25,${oy - 9} 55,${oy + 9} 105,${oy - 3}`}
        fill="none" stroke={BLUE} strokeWidth="0.45" opacity={(0.42 - i * 0.05).toFixed(2)} />);
    }
  else if (kind === "circuit")
    for (let i = 0; i < 7; i++) {
      const px = 10 + i * 13, py = 20 + (i % 3) * 22;
      out.push(<path key={`p${i}`} d={`M${px},96 L${px},${py} L${px + 11},${py}`}
        fill="none" stroke={BLUE} strokeWidth="0.5" opacity="0.32" />);
      out.push(<circle key={`c${i}`} cx={px + 11} cy={py} r="1.1" fill={BLUE} opacity="0.55" />);
    }
  else
    out.push(<path key="g" d="M0,78 L30,40 L58,62 L100,26" fill="none" stroke={BLUE}
      strokeWidth="0.5" opacity="0.32" />);
  return out;
}

export default function Placeholder({ kind, icon }: { kind: string; icon: string }) {
  const id = `ph-${kind}`;
  return (
    <div className="art" aria-hidden="true">
      <svg viewBox="0 0 100 100" preserveAspectRatio="xMidYMid slice" width="100%" height="100%" focusable="false">
        <defs>
          <linearGradient id={`${id}bg`} x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stopColor="#EDF3FD" /><stop offset="1" stopColor="#FFFFFF" />
          </linearGradient>
          <radialGradient id={`${id}gl`} cx="0.78" cy="0.18" r="0.8">
            <stop offset="0" stopColor={ICE} stopOpacity="0.85" />
            <stop offset="1" stopColor={ICE} stopOpacity="0" />
          </radialGradient>
          <pattern id={`${id}p`} width="7" height="7" patternUnits="userSpaceOnUse">
            <path d="M7,0 L0,7" stroke={NAVY} strokeOpacity="0.05" strokeWidth="0.4" />
          </pattern>
        </defs>
        <rect width="100" height="100" fill={`url(#${id}bg)`} />
        <rect width="100" height="100" fill={`url(#${id}gl)`} />
        <rect width="100" height="100" fill={`url(#${id}p)`} />
        {motif(MOTIF[kind] ?? "grid")}
        <g opacity="0.14" transform="translate(50,46) scale(1.7) translate(-12,-12)">
          <use href={`#${icon}`} width="24" height="24" color={NAVY} />
        </g>
      </svg>
    </div>
  );
}
