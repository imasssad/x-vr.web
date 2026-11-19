/** One inline SVG sprite, rendered once. Every icon is referenced with
 *  <svg><use href="#i-name"/></svg>, so no icon costs an extra request. */
export default function Icons() {
  return (
    <svg width="0" height="0" aria-hidden="true" focusable="false" style={{position:"absolute"}}>
      <defs>
        <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round"><polyline points="4 12.5 9.5 18 20 6"/></symbol>
        <symbol id="i-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 5 17 12 9 19"/></symbol>
        <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="9.2"/><line x1="12" y1="11" x2="12" y2="16.5"/><circle cx="12" cy="7.6" r=".9" fill="currentColor" stroke="none"/></symbol>
        <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="4.5" y="10.5" width="15" height="10.5" rx="2.2"/><path d="M8 10.5V7.4a4 4 0 0 1 8 0v3.1"/></symbol>
        <symbol id="i-play" viewBox="0 0 24 24" fill="currentColor"><path d="M7.5 5.2v13.6a.8.8 0 0 0 1.22.68l11-6.8a.8.8 0 0 0 0-1.36l-11-6.8a.8.8 0 0 0-1.22.68z"/></symbol>
        <symbol id="i-pause" viewBox="0 0 24 24" fill="currentColor"><rect x="6.4" y="5" width="4" height="14" rx="1.2"/><rect x="13.6" y="5" width="4" height="14" rx="1.2"/></symbol>
        <symbol id="i-sound" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 9.5h3.2L12.5 5v14L7.2 14.5H4z"/><path d="M16.2 9a4.6 4.6 0 0 1 0 6"/><path d="M18.8 6.2a8.2 8.2 0 0 1 0 11.6"/></symbol>
        <symbol id="i-mute" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 9.5h3.2L12.5 5v14L7.2 14.5H4z"/><line x1="16.5" y1="9.5" x2="21" y2="14.5"/><line x1="21" y1="9.5" x2="16.5" y2="14.5"/></symbol>
        <symbol id="i-weld" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M3 20.5h18"/><path d="M6.5 20.5 14 6.2"/><path d="M10.6 4.1l3.9 2.1-1.6 2.9-3.9-2.1z"/><path d="M17.4 12.8l1.3-2.4M19.9 15l2.1-1.1M16.4 16.6l.6-2.6"/></symbol>
        <symbol id="i-hvac" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><rect x="2.6" y="4.4" width="18.8" height="9.6" rx="2"/><path d="M6.4 8.1h11.2M6.4 10.6h11.2"/><path d="M7 17.4c0 1.3 1 2.3 2.3 2.3s2.3-1 2.3-2.3 1-2.3 2.3-2.3 2.3 1 2.3 2.3"/></symbol>
        <symbol id="i-elec" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M13.4 2.5 5.6 13.4h5.1l-1.3 8.1 8-11.2h-5.1z"/></symbol>
        <symbol id="i-manu" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M2.8 20.6V10l5.4 3.3V10l5.4 3.3V10l5.6 3.4v7.2z"/><path d="M2.8 20.6h18.4"/><path d="M6 6.4V3.6M10.4 6.4V3.6"/></symbol>
        <symbol id="i-cons" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M2.5 13.6h19v3.1h-19z"/><path d="M4.4 13.6c0-4.2 3.4-7.6 7.6-7.6s7.6 3.4 7.6 7.6"/><path d="M12 6V3.3M9.2 13.6V6.8M14.8 13.6V6.8"/><path d="M4 20.7h16"/></symbol>
        <symbol id="i-tech" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><rect x="2.4" y="7.8" width="19.2" height="8.8" rx="3.4"/><circle cx="7.9" cy="12.2" r="1.7"/><circle cx="16.1" cy="12.2" r="1.7"/><path d="M12 16.6v2.1M9.4 5.3 12 7.8l2.6-2.5"/></symbol>
        <symbol id="i-learn" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M3.2 6.1 12 2.7l8.8 3.4L12 9.6z"/><path d="M6 8.2v5.4c0 1.9 2.7 3.4 6 3.4s6-1.5 6-3.4V8.2"/><path d="M12 17v4.3"/></symbol>
        <symbol id="i-rehearse" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M20.6 12a8.6 8.6 0 1 1-2.9-6.4"/><polyline points="20.9 3.6 20.9 8.4 16.1 8.4"/><path d="M12 8.3V12l2.6 1.8"/></symbol>
        <symbol id="i-reflect" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M21 14.5a2.3 2.3 0 0 1-2.3 2.3H8.2L3.6 21V5.6a2.3 2.3 0 0 1 2.3-2.3h12.8A2.3 2.3 0 0 1 21 5.6z"/><path d="M8.6 8.4h7.6M8.6 11.8h4.6"/></symbol>
        <symbol id="i-apply" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M14.1 3.4 4.8 13.2l-1.4 6.4 6.4-1.4L19.6 8.9a3.2 3.2 0 0 0 0-4.5l-.9-1a3.2 3.2 0 0 0-4.6 0z"/><path d="M12.2 5.4 17.6 10.8"/></symbol>
        <symbol id="i-reset" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3.4 12a8.6 8.6 0 1 0 2.9-6.4"/><polyline points="3.1 3.6 3.1 8.4 7.9 8.4"/></symbol>
      </defs>
    </svg>
  );
}
