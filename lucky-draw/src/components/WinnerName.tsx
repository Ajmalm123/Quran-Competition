import { useEffect } from "react";
import { motion } from "framer-motion";
import { MapPin } from "lucide-react";
import confetti from "canvas-confetti";

interface Props {
  name: string;
  place?: string;
  reduced: boolean;
}

const GOLD = ["#fff3c8", "#f6e2a6", "#f0d078", "#e8b94a", "#c98f26"];

function fireConfetti() {
  const base = {
    colors: GOLD,
    disableForReducedMotion: true,
    zIndex: 60,
    ticks: 180,
    gravity: 0.9,
    scalar: 0.95,
    shapes: ["square", "circle"] as confetti.Shape[],
  };
  // a short, tasteful burst: one centre pop and two side cannons
  confetti({ ...base, particleCount: 70, spread: 80, startVelocity: 42, origin: { x: 0.5, y: 0.55 } });
  setTimeout(() => {
    confetti({ ...base, particleCount: 36, angle: 60, spread: 55, startVelocity: 48, origin: { x: 0, y: 0.7 } });
    confetti({ ...base, particleCount: 36, angle: 120, spread: 55, startVelocity: 48, origin: { x: 1, y: 0.7 } });
  }, 220);
}

/** Final winner name: gold gradient, scale-in, rotating light rays behind it, and place pill badge. */
export function WinnerName({ name, place, reduced }: Props) {
  useEffect(() => {
    if (!reduced) fireConfetti();
  }, [reduced]);

  return (
    <div className="relative flex h-full w-full flex-col items-center justify-center">
      {!reduced && (
        <div
          aria-hidden
          className="pointer-events-none absolute left-1/2 top-1/2 h-[46rem] w-[46rem] max-w-[160%] -translate-x-1/2 -translate-y-1/2 opacity-[0.16]"
          style={{
            background:
              "repeating-conic-gradient(from 0deg, rgba(240,208,120,0.9) 0deg 4deg, transparent 4deg 18deg)",
            maskImage: "radial-gradient(circle, #000 0%, transparent 62%)",
            WebkitMaskImage: "radial-gradient(circle, #000 0%, transparent 62%)",
            animation: "rays 60s linear infinite",
          }}
        />
      )}
      <motion.h2
        className="gold-text relative whitespace-nowrap px-2 font-display text-[clamp(1.75rem,7vw,6.25rem)] font-semibold leading-none"
        initial={reduced ? { opacity: 0 } : { opacity: 0, scale: 0.88, filter: "blur(8px)" }}
        animate={{ opacity: 1, scale: 1, filter: "blur(0px)" }}
        transition={{ duration: 0.9, ease: [0.22, 1, 0.36, 1] }}
        style={{ textShadow: "0 0 60px rgba(240,208,120,0.45)", filter: "drop-shadow(0 0 22px rgba(240,208,120,0.35))" }}
      >
        {name}
      </motion.h2>

      {place && (
        <motion.div
          initial={reduced ? { opacity: 0 } : { opacity: 0, y: 10 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6, delay: 0.25, ease: "easeOut" }}
          className="relative z-10 mt-3.5 inline-flex items-center gap-1.5 rounded-full border border-gold-400/40 bg-gold-400/10 px-4 py-1 text-xs font-medium uppercase tracking-[0.22em] text-gold-300 backdrop-blur-sm sm:mt-4 sm:px-5 sm:py-1.5 sm:text-sm shadow-[0_0_24px_rgba(240,208,120,0.18)]"
        >
          <MapPin className="h-3.5 w-3.5 text-gold-400" aria-hidden />
          <span>{place}</span>
        </motion.div>
      )}
    </div>
  );
}
