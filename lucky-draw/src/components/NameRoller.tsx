import { useEffect, useRef, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import type { Participant } from "../data";

interface Props {
  participants: Participant[];
  winner: Participant;
  reduced: boolean;
  onDone: () => void;
}

interface Plan {
  names: string[];
  delays: number[]; // ms each name stays on screen before the next one
}

const SETTLE_MS = 1200; // pause on the final name (glow + burst) before the reveal

/**
 * Builds the full roll up front. The winner is fixed before the animation
 * starts and is always the last entry, so the roll ends on the correct name.
 * Delays grow cubically (fast -> slow) and the last steps are stretched
 * for suspense. Total roll is ~6.5s, plus the settle pause.
 */
function buildPlan(participants: Participant[], winner: Participant, reduced: boolean): Plan {
  const count = reduced ? 8 : 30;
  const pool = participants.filter((p) => p.id !== winner.id).map((p) => p.name);
  const names: string[] = [];
  if (pool.length === 0) {
    for (let i = 0; i < count; i++) {
      names.push(winner.name);
    }
  } else if (pool.length === 1) {
    for (let i = 0; i < count - 1; i++) {
      names.push(pool[0]);
    }
    names.push(winner.name);
  } else {
    let prev = "";
    for (let i = 0; i < count - 1; i++) {
      let pick = pool[Math.floor(Math.random() * pool.length)];
      let attempts = 0;
      while (pick === prev && attempts < 10) {
        pick = pool[Math.floor(Math.random() * pool.length)];
        attempts++;
      }
      names.push(pick);
      prev = pick;
    }
    names.push(winner.name);
  }

  const delays = names.slice(0, -1).map((_, i, arr) => {
    if (reduced) return 170;
    const t = i / (arr.length - 1);
    let d = 45 + 560 * t ** 3;
    const fromEnd = arr.length - 1 - i; // 0 = last gap
    if (fromEnd === 2) d *= 1.0;
    if (fromEnd === 1) d *= 1.3;
    if (fromEnd === 0) d *= 1.7; // long final pause
    return d;
  });
  return { names, delays };
}

export function NameRoller({ participants, winner, reduced, onDone }: Props) {
  const [plan] = useState(() => buildPlan(participants, winner, reduced));
  const [idx, setIdx] = useState(0);
  const [settled, setSettled] = useState(false);
  const doneRef = useRef(onDone);
  doneRef.current = onDone;

  useEffect(() => {
    let i = 0;
    let timer: ReturnType<typeof setTimeout>;
    const last = plan.names.length - 1;

    const step = () => {
      setIdx(i);
      if (i === last) {
        setSettled(true);
        timer = setTimeout(() => doneRef.current(), SETTLE_MS);
        return;
      }
      timer = setTimeout(() => {
        i += 1;
        step();
      }, plan.delays[i]);
    };
    step();
    return () => clearTimeout(timer);
  }, [plan]);

  const speed = plan.delays[idx] ?? 700;
  const duration = reduced ? 0.12 : Math.min(0.5, Math.max(0.07, (speed * 0.7) / 1000));
  const fast = !reduced && speed < 160;

  return (
    <div className="relative h-full w-full" aria-hidden>
      {/* slot window hairlines */}
      <div className="pointer-events-none absolute inset-x-[8%] top-0 h-px bg-gradient-to-r from-transparent via-gold-400/60 to-transparent" />
      <div className="pointer-events-none absolute inset-x-[8%] bottom-0 h-px bg-gradient-to-r from-transparent via-gold-400/60 to-transparent" />

      {/* light burst when the roll lands */}
      {settled && !reduced && (
        <>
          <motion.div
            className="pointer-events-none absolute left-1/2 top-1/2 h-40 w-40 -translate-x-1/2 -translate-y-1/2 rounded-full border border-gold-200/70"
            initial={{ scale: 0.4, opacity: 0.9 }}
            animate={{ scale: 5, opacity: 0 }}
            transition={{ duration: 1.1, ease: "easeOut" }}
          />
          <motion.div
            className="pointer-events-none absolute left-1/2 top-1/2 h-56 w-56 -translate-x-1/2 -translate-y-1/2 rounded-full"
            style={{
              background:
                "radial-gradient(circle, rgba(255,240,190,0.55) 0%, rgba(240,208,120,0.2) 40%, transparent 70%)",
            }}
            initial={{ scale: 0.3, opacity: 1 }}
            animate={{ scale: 4, opacity: 0 }}
            transition={{ duration: 1, ease: "easeOut" }}
          />
        </>
      )}

      <div
        className="relative h-full w-full overflow-hidden"
        style={{
          maskImage: "linear-gradient(to bottom, transparent, #000 18%, #000 82%, transparent)",
          WebkitMaskImage: "linear-gradient(to bottom, transparent, #000 18%, #000 82%, transparent)",
        }}
      >
        <AnimatePresence mode="popLayout" initial={false}>
          <motion.div
            key={idx}
            className="absolute inset-0 flex items-center justify-center px-2"
            initial={
              reduced ? { opacity: 0 } : { y: "75%", opacity: 0, filter: fast ? "blur(5px)" : "blur(1px)" }
            }
            animate={{ y: 0, opacity: 1, filter: "blur(0px)" }}
            exit={
              reduced ? { opacity: 0 } : { y: "-75%", opacity: 0, filter: fast ? "blur(5px)" : "blur(1px)" }
            }
            transition={{ duration, ease: [0.22, 1, 0.36, 1] }}
          >
            <motion.span
              className={`whitespace-nowrap font-display text-[clamp(1.6rem,6.4vw,5.5rem)] font-semibold leading-none ${
                settled ? "gold-text" : "text-slate-100"
              }`}
              animate={{ scale: settled && !reduced ? 1.08 : 1 }}
              transition={{ type: "spring", stiffness: 180, damping: 14 }}
              style={{
                textShadow: settled
                  ? "0 0 40px rgba(240,208,120,0.55)"
                  : "0 0 24px rgba(240,208,120,0.18)",
              }}
            >
              {plan.names[idx]}
            </motion.span>
          </motion.div>
        </AnimatePresence>
      </div>
    </div>
  );
}
