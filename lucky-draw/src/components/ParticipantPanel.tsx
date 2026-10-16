import { AnimatePresence, motion } from "framer-motion";
import { X, Users, MapPin } from "lucide-react";
import type { Participant } from "../data";
import { useDialogFocus } from "../hooks";

interface Props {
  open: boolean;
  onClose: () => void;
  participants: Participant[];
  winnerId?: number;
  onLoadSampleData?: () => void;
}

export function ParticipantPanel({
  open,
  onClose,
  participants,
  winnerId,
  onLoadSampleData,
}: Props) {
  const ref = useDialogFocus<HTMLElement>(open, onClose, "[data-close]");

  return (
    <AnimatePresence>
      {open && (
        <>
          <motion.div
            key="backdrop"
            className="fixed inset-0 z-40 bg-ink-950/70 backdrop-blur-sm"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
            aria-hidden
          />
          <motion.aside
            key="panel"
            id="participant-panel"
            ref={ref}
            role="dialog"
            aria-modal="true"
            aria-label="Participant list"
            className="fixed inset-y-0 right-0 z-50 flex w-full max-w-sm flex-col border-l border-gold-400/25 bg-ink-900/95 shadow-[0_0_80px_-10px_rgba(232,185,74,0.25)] backdrop-blur-xl"
            initial={{ x: "100%" }}
            animate={{ x: 0 }}
            exit={{ x: "100%" }}
            transition={{ type: "spring", damping: 32, stiffness: 300 }}
          >
            <div className="flex items-center justify-between border-b border-white/10 px-6 py-5">
              <div>
                <h2 className="font-display text-2xl font-semibold tracking-wide text-gold-100">
                  Participants
                </h2>
                <p className="mt-0.5 text-xs uppercase tracking-[0.2em] text-slate-400">
                  {participants.length} eligible
                </p>
              </div>
              <button
                type="button"
                data-close
                onClick={onClose}
                aria-label="Close participant list"
                className="grid h-10 w-10 place-items-center rounded-full border border-white/10 text-slate-300 transition hover:border-gold-300/60 hover:text-gold-100"
              >
                <X className="h-4 w-4" aria-hidden />
              </button>
            </div>

            {participants.length === 0 ? (
              <div className="flex flex-1 flex-col items-center justify-center p-6 text-center text-slate-400">
                <Users className="mb-3 h-10 w-10 text-slate-600" aria-hidden />
                <p className="text-sm font-semibold text-slate-300">No Eligible Participants</p>
                <p className="mt-1 text-xs text-slate-400">
                  Only participants with a full 50/50 score appear here.
                </p>
                {onLoadSampleData && (
                  <button
                    type="button"
                    onClick={onLoadSampleData}
                    className="mt-4 rounded-full border border-gold-400/40 px-4 py-1.5 text-xs font-semibold text-gold-300 transition hover:bg-gold-400/10 hover:text-gold-100"
                  >
                    Load Sample Participants (36)
                  </button>
                )}
              </div>
            ) : (
              <ol className="flex-1 overflow-y-auto px-3 py-3">
                {participants.map((p, idx) => {
                  const isWinner = p.id === winnerId;
                  return (
                    <li
                      key={p.id}
                      className={`flex items-center gap-3.5 rounded-lg px-3 py-2.5 text-[0.95rem] transition ${
                        isWinner
                          ? "bg-gold-400/15 text-gold-100"
                          : "text-slate-200 hover:bg-white/[0.04]"
                      }`}
                    >
                      <span className="w-7 text-right text-xs tabular-nums text-slate-400 shrink-0">
                        {String(idx + 1).padStart(2, "0")}
                      </span>
                      <div className="flex flex-1 flex-col min-w-0">
                        <span className="truncate font-medium leading-snug">{p.name}</span>
                        {p.place && (
                          <span className="flex items-center gap-1 text-[0.75rem] text-slate-400 truncate mt-0.5">
                            <MapPin className="h-3 w-3 shrink-0 text-gold-400/70" aria-hidden />
                            <span className="truncate">{p.place}</span>
                          </span>
                        )}
                      </div>
                      {isWinner && (
                        <span className="rounded-full bg-gold-400/20 px-2.5 py-0.5 text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-gold-200 shrink-0">
                          Winner
                        </span>
                      )}
                    </li>
                  );
                })}
              </ol>
            )}
          </motion.aside>
        </>
      )}
    </AnimatePresence>
  );
}
