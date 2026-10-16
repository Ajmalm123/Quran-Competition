import { useEffect } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { CheckCircle2, AlertCircle } from "lucide-react";

interface Props {
  open: boolean;
  onClose: () => void;
  title?: string;
  description?: string;
  type?: "success" | "error";
}

export function Toast({
  open,
  onClose,
  title = "Result published",
  description = "Winner saved and published successfully to the live website.",
  type = "success",
}: Props) {
  useEffect(() => {
    if (!open) return;
    const t = setTimeout(onClose, 5000);
    return () => clearTimeout(t);
  }, [open, onClose]);

  const isError = type === "error";

  return (
    <div
      role="status"
      aria-live="polite"
      className="pointer-events-none fixed inset-x-0 top-6 z-[70] flex justify-center px-4"
    >
      <AnimatePresence>
        {open && (
          <motion.div
            initial={{ opacity: 0, y: -24, scale: 0.96 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: -16 }}
            transition={{ type: "spring", damping: 24, stiffness: 300 }}
            className={`pointer-events-auto flex items-center gap-4 rounded-2xl border px-5 py-4 shadow-2xl backdrop-blur-xl ${
              isError
                ? "border-rose-400/50 bg-ink-800/95 shadow-[0_10px_60px_-10px_rgba(244,63,94,0.4)]"
                : "border-emerald-300/40 bg-ink-800/95 shadow-[0_10px_60px_-10px_rgba(52,211,153,0.4)]"
            }`}
          >
            {isError ? (
              <AlertCircle className="h-6 w-6 shrink-0 text-rose-400" aria-hidden />
            ) : (
              <CheckCircle2 className="h-6 w-6 shrink-0 text-emerald-300" aria-hidden />
            )}
            <div className="text-left">
              <p className={`text-sm font-semibold ${isError ? "text-rose-200" : "text-slate-50"}`}>
                {title}
              </p>
              <p className="text-xs text-slate-300">{description}</p>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
