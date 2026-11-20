/** TradeSkillApps mark: the hexagon carries the trades language, the T the name. */
export default function Logo({ className = "brand__mark" }: { className?: string }) {
  return (
    <svg className={className} viewBox="0 0 64 64" aria-hidden="true">
      <polygon points="32.00,61.90 6.11,46.95 6.11,17.05 32.00,2.10 57.89,17.05 57.89,46.95" fill="#15283F" stroke="#15283F" strokeWidth="2.4" strokeLinejoin="round"/><path d="M14.5 17 A2.6 2.6 0 0 1 17.1 14.4 L46.9 14.4 A2.6 2.6 0 0 1 49.5 17 L49.5 21.8 A2.6 2.6 0 0 1 46.9 24.4 L36.6 24.4 L36.6 46.6 A2.6 2.6 0 0 1 34 49.2 L30 49.2 A2.6 2.6 0 0 1 27.4 46.6 L27.4 24.4 L17.1 24.4 A2.6 2.6 0 0 1 14.5 21.8 Z" fill="#FFFFFF"/>
    </svg>
  );
}
