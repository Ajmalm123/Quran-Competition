/** Decorative gold divider: hairlines with a centred diamond. */
export function Ornament() {
  return (
    <div className="flex w-full max-w-md items-center gap-3" aria-hidden>
      <span className="h-px flex-1 bg-gradient-to-r from-transparent to-gold-400/80" />
      <span className="h-1.5 w-1.5 rotate-45 bg-gold-300/80" />
      <span className="h-2.5 w-2.5 rotate-45 border border-gold-300 bg-gold-400/30" />
      <span className="h-1.5 w-1.5 rotate-45 bg-gold-300/80" />
      <span className="h-px flex-1 bg-gradient-to-l from-transparent to-gold-400/80" />
    </div>
  );
}
