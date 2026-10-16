import { AnimatePresence, motion } from "framer-motion";
import { ArrowLeft, Check, Loader2, Send, Sparkles } from "lucide-react";
import type { Participant } from "../data";
import type { Phase } from "../types";
import { NameRoller } from "./NameRoller";
import { Ornament } from "./Ornament";
import { WinnerName } from "./WinnerName";

interface Props {
  phase: Phase;
  winner: Participant | null;
  participants: Participant[];
  reduced: boolean;
  onChoose: () => void;
  onRollDone: () => void;
  onPublish: () => void;
  onBack: () => void;
}

const fade = {
  initial: { opacity: 0, y: 10 },
  animate: { opacity: 1, y: 0 },
  exit: { opacity: 0, y: -10 },
  transition: { duration: 0.35 },
};

function Corner({ className }: { className: string }) {
  return (
    <span
      aria-hidden
      className={`pointer-events-none absolute h-7 w-7 border-gold-300/70 ${className}`}
    />
  );
}

export function Stage({
  phase,
  winner,
  participants,
  reduced,
  onChoose,
  onRollDone,
  onPublish,
  onBack,
}: Props) {
  const showReveal = phase === "revealed" || phase === "confirming" || phase === "published";
  const view: "ready" | "rolling" | "reveal" = showReveal ? "reveal" : phase === "rolling" ? "rolling" : "ready";

  return (
    <section aria-label="Winner selection stage" className="relative mx-auto w-full max-w-5xl">
      <div className={`frame rounded-[2rem] p-[1.5px] transition-shadow duration-1000 ${showReveal ? "frame-celebrate" : ""}`}>
        <div className="relative flex min-h-[31rem] flex-col items-center justify-between overflow-hidden rounded-[calc(2rem-1.5px)] bg-ink-900 px-5 py-9 text-center sm:min-h-[35rem] sm:px-12 sm:py-12">
          {/* spotlight */}
          <div
            aria-hidden
            className="spotlight pointer-events-none absolute left-1/2 top-[44%] h-[130%] w-[120%] -translate-x-1/2 -translate-y-1/2 transition-opacity duration-1000"
            style={{
              background: showReveal
                ? "radial-gradient(ellipse 50% 42% at 50% 50%, rgba(240,208,120,0.38) 0%, rgba(232,185,74,0.14) 45%, transparent 75%)"
                : "radial-gradient(ellipse 45% 36% at 50% 50%, rgba(240,208,120,0.17) 0%, rgba(232,185,74,0.06) 50%, transparent 75%)",
            }}
          />
          <div
            aria-hidden
            className="pointer-events-none absolute inset-x-0 top-0 h-1/2 opacity-60"
            style={{
              background:
                "radial-gradient(ellipse 40% 100% at 50% 0%, rgba(255,240,200,0.12), transparent 70%)",
            }}
          />

          {/* corner ornaments */}
          <Corner className="left-5 top-5 rounded-tl-lg border-l border-t" />
          <Corner className="right-5 top-5 rounded-tr-lg border-r border-t" />
          <Corner className="bottom-5 left-5 rounded-bl-lg border-b border-l" />
          <Corner className="bottom-5 right-5 rounded-br-lg border-b border-r" />

          {/* TOP: label / headline */}
          <div className="relative z-10 flex h-24 flex-col items-center justify-center">
            <AnimatePresence mode="wait">
              {showReveal ? (
                <motion.div key="top-win" {...fade} className="flex flex-col items-center gap-2">
                  <span className="inline-flex items-center gap-2 text-[0.68rem] font-semibold uppercase tracking-[0.4em] text-gold-300">
                    <Sparkles className="h-3.5 w-3.5" aria-hidden /> Winner Selected
                  </span>
                  <h2 className="font-display text-2xl font-semibold tracking-[0.2em] text-gold-100 sm:text-4xl">
                    WE HAVE A WINNER!
                  </h2>
                </motion.div>
              ) : (
                <motion.span
                  key="top-next"
                  {...fade}
                  className="text-xs font-semibold uppercase tracking-[0.5em] text-gold-300"
                >
                  The Next Winner
                </motion.span>
              )}
            </AnimatePresence>
          </div>

          {/* NAME: fixed min height so nothing shifts while rolling */}
          <div className="relative z-10 flex min-h-[8.5rem] w-full flex-col items-center justify-center sm:min-h-[11.5rem]">
            <AnimatePresence mode="wait">
              {view === "ready" && (
                <motion.div
                  key="name-ready"
                  {...fade}
                  className="flex h-full items-center justify-center"
                >
                  <span className="shimmer font-display text-[clamp(1.6rem,5.2vw,4.25rem)] font-semibold italic tracking-[0.18em]">
                    WHO WILL IT BE?
                  </span>
                </motion.div>
              )}
              {view === "rolling" && winner && (
                <motion.div
                  key="name-rolling"
                  className="h-full w-full"
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  exit={{ opacity: 0, transition: { duration: 0.25 } }}
                >
                  <NameRoller
                    participants={participants}
                    winner={winner}
                    reduced={reduced}
                    onDone={onRollDone}
                  />
                </motion.div>
              )}
              {view === "reveal" && winner && (
                <motion.div
                  key="name-reveal"
                  className="h-full w-full"
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  transition={{ duration: 0.4 }}
                >
                  <WinnerName name={winner.name} place={winner.place} reduced={reduced} />
                </motion.div>
              )}
            </AnimatePresence>
          </div>

          {/* BOTTOM: instruction + actions */}
          <div className="relative z-10 flex min-h-[9.5rem] w-full flex-col items-center justify-end gap-6">
            <AnimatePresence mode="wait">
              {view !== "reveal" ? (
                <motion.div key="bottom-draw" {...fade} className="flex flex-col items-center gap-6">
                  <p className="text-sm tracking-[0.12em] text-slate-300 sm:text-base">
                    {view === "rolling"
                      ? "Drawing the winner…"
                      : participants.length === 0
                      ? "No participants with a 50/50 score found yet."
                      : "Ready to reveal the winner?"}
                  </p>
                  <button
                    type="button"
                    className="btn-gold min-w-[16rem] text-base"
                    onClick={onChoose}
                    disabled={view === "rolling" || participants.length === 0}
                    aria-busy={view === "rolling"}
                  >
                    {view === "rolling" ? (
                      <>
                        <Loader2 className="h-5 w-5 animate-spin motion-reduce:animate-none" aria-hidden />
                        DRAWING…
                      </>
                    ) : participants.length === 0 ? (
                      "NO PARTICIPANTS"
                    ) : (
                      "CHOOSE WINNER"
                    )}
                  </button>
                </motion.div>
              ) : (
                <motion.div key="bottom-win" {...fade} className="flex w-full flex-col items-center gap-7">
                  <Ornament />
                  <div className="flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center sm:gap-4">
                    <button
                      type="button"
                      className="btn-gold min-w-[15rem] text-sm"
                      onClick={onPublish}
                      disabled={phase === "published"}
                    >
                      {phase === "published" ? (
                        <>
                          <Check className="h-4 w-4" aria-hidden /> RESULT PUBLISHED
                        </>
                      ) : (
                        <>
                          <Send className="h-4 w-4" aria-hidden /> PUBLISH RESULT
                        </>
                      )}
                    </button>
                    <button type="button" className="btn-ghost" onClick={onBack}>
                      <ArrowLeft className="h-4 w-4" aria-hidden /> BACK TO OVERVIEW
                    </button>
                  </div>
                </motion.div>
              )}
            </AnimatePresence>
          </div>
        </div>
      </div>
    </section>
  );
}
