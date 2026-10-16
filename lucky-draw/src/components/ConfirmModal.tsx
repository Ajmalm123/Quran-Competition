import { AnimatePresence, motion } from "framer-motion";
import { Send, Trophy, Loader2, MapPin } from "lucide-react";
import { useDialogFocus } from "../hooks";

interface Props {
  open: boolean;
  winnerName: string;
  winnerPlace?: string;
  onConfirm: () => void;
  onCancel: () => void;
  isProduction?: boolean;
  loading?: boolean;
}

export function ConfirmModal({
  open,
  winnerName,
  winnerPlace,
  onConfirm,
  onCancel,
  isProduction = false,
  loading = false,
}: Props) {
  const ref = useDialogFocus<HTMLDivElement>(open, onCancel, "[data-confirm]");

  return (
    <AnimatePresence>
      {open && (
        <motion.div
          className="fixed inset-0 z-50 grid place-items-center p-4"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
        >
          <div
            className="absolute inset-0 bg-ink-950/80 backdrop-blur-md"
            onClick={loading ? undefined : onCancel}
            aria-hidden
          />
          <motion.div
            ref={ref}
            role="dialog"
            aria-modal="true"
            aria-labelledby="confirm-title"
            aria-describedby="confirm-desc"
            className="relative w-full max-w-lg rounded-3xl border border-gold-400/40 bg-gradient-to-b from-ink-800 to-ink-900 p-8 text-center shadow-[0_0_100px_-10px_rgba(232,185,74,0.4)] sm:p-10"
            initial={{ opacity: 0, scale: 0.92, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.96, y: 10 }}
            transition={{ type: "spring", damping: 26, stiffness: 320 }}
          >
            <div className="mx-auto mb-5 grid h-14 w-14 place-items-center rounded-full border border-gold-300/50 bg-gold-400/10 text-gold-300">
              <Trophy className="h-6 w-6" aria-hidden />
            </div>
            <p className="text-[0.68rem] font-semibold uppercase tracking-[0.4em] text-gold-300">
              Selected winner
            </p>
            <p className="gold-text mt-3 break-words font-display text-4xl font-semibold leading-tight sm:text-5xl">
              {winnerName}
            </p>
            {winnerPlace && (
              <div className="mt-2.5 inline-flex items-center gap-1.5 rounded-full border border-gold-400/35 bg-gold-400/10 px-3.5 py-1 text-xs font-medium uppercase tracking-[0.2em] text-gold-300">
                <MapPin className="h-3.5 w-3.5 text-gold-400" aria-hidden />
                <span>{winnerPlace}</span>
              </div>
            )}
            <div className="mx-auto my-6 h-px w-24 bg-gradient-to-r from-transparent via-gold-400/70 to-transparent" />
            <h2 id="confirm-title" className="font-display text-2xl font-semibold text-gold-100">
              Are you sure you want to publish this result?
            </h2>
            <p id="confirm-desc" className="mt-2 text-xs tracking-wide text-slate-400">
              {isProduction
                ? "This will save the winner and publish it live to the public website."
                : "Demo only — nothing is sent or saved anywhere."}
            </p>
            <div className="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
              <button
                type="button"
                className="btn-ghost"
                onClick={onCancel}
                disabled={loading}
              >
                CANCEL
              </button>
              <button
                type="button"
                data-confirm
                className="btn-gold !px-8 !text-xs"
                onClick={onConfirm}
                disabled={loading}
              >
                {loading ? (
                  <>
                    <Loader2 className="h-4 w-4 animate-spin" aria-hidden /> PUBLISHING…
                  </>
                ) : (
                  <>
                    <Send className="h-4 w-4" aria-hidden /> CONFIRM &amp; PUBLISH
                  </>
                )}
              </button>
            </div>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
