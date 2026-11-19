/** Disclaimer note. **bold** in the text becomes <strong>. */
export default function Note({ text, style }: { text: string; style?: React.CSSProperties }) {
  const parts = text.split(/(\*\*[^*]+\*\*)/g).filter(Boolean);
  return (
    <div className="note rv" style={style}>
      <svg aria-hidden="true"><use href="#i-info" /></svg>
      <p>
        {parts.map((p, i) =>
          p.startsWith("**") ? <strong key={i}>{p.slice(2, -2)}</strong> : <span key={i}>{p}</span>
        )}
      </p>
    </div>
  );
}
