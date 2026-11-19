/** The brand mark, exactly as drawn for the static build. */
export default function Logo({ className = "brand__mark" }: { className?: string }) {
  return (
    <svg className={className} viewBox="0 0 64 64" aria-hidden="true">
      <polygon points="32.00,61.90 6.11,46.95 6.11,17.05 32.00,2.10 57.89,17.05 57.89,46.95" fill="#15283F" stroke="#15283F" strokeWidth="2.4" strokeLinejoin="round"/><polygon points="34.97,23.37 13.40,11.00 11.00,13.40 23.37,34.97" fill="#FFFFFF" stroke="#FFFFFF" strokeWidth="1.5" strokeLinejoin="round"/><polygon points="29.03,40.63 50.60,53.00 53.00,50.60 40.63,29.03" fill="#FFFFFF" stroke="#FFFFFF" strokeWidth="1.5" strokeLinejoin="round"/><polygon points="39.07,33.41 48.55,17.43 46.57,15.45 30.59,24.93" fill="#FFFFFF" stroke="#FFFFFF" strokeWidth="1.5" strokeLinejoin="round"/><polygon points="24.93,30.59 15.45,46.57 17.43,48.55 33.41,39.07" fill="#FFFFFF" stroke="#FFFFFF" strokeWidth="1.5" strokeLinejoin="round"/><polygon points="32.00,28.80 35.20,32.00 32.00,35.20 28.80,32.00" fill="#2864E8"/>
    </svg>
  );
}
