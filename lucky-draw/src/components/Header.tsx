import { Crown } from "lucide-react";
import type { Phase } from "../types";

const STATUS: Record<Phase, { label: string; dot: string; text: string }> = {
  ready: { label: "READY TO DRAW", dot: "bg-emerald-400", text: "text-emerald-200" },
  rolling: { label: "DRAWING…", dot: "bg-amber-300", text: "text-amber-100" },
  revealed: { label: "WINNER SELECTED", dot: "bg-gold-300", text: "text-gold-100" },
  confirming: { label: "AWAITING CONFIRMATION", dot: "bg-gold-300", text: "text-gold-100" },
  published: { label: "RESULT PUBLISHED", dot: "bg-emerald-400", text: "text-emerald-200" },
};

export function Header({ phase, subtitle }: { phase: Phase; subtitle?: string }) {
  const s = STATUS[phase];
  return (
    <header className="grid grid-cols-[auto_1fr] items-center gap-x-4 gap-y-5 sm:grid-cols-[1fr_auto_1fr]">
      {/* Logo placeholder */}
      <div className="flex items-center gap-3">
        <div
          className="grid h-12 w-12 place-items-center rounded-xl border border-dashed border-gold-400/50 bg-gold-400/5 text-gold-300"
          role="img"
          aria-label="Event logo placeholder"
        >
          <Crown className="h-5 w-5" aria-hidden />
        </div>
        <span className="hidden text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-slate-400 lg:block">
          Your logo
        </span>
      </div>

      {/* Status (mobile: top right) */}
      <div className="flex justify-end sm:order-3">
        <div
          role="status"
          className={`inline-flex items-center gap-2.5 rounded-full border border-white/10 bg-white/[0.04] px-4 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.24em] ${s.text}`}
        >
          <span className="relative flex h-2 w-2">
            <span className={`absolute inline-flex h-full w-full animate-ping rounded-full opacity-60 motion-reduce:animate-none ${s.dot}`} />
            <span className={`relative inline-flex h-2 w-2 rounded-full ${s.dot}`} />
          </span>
          {s.label}
        </div>
      </div>

      {/* Title */}
      <div className="col-span-2 text-center sm:order-2 sm:col-span-1">
        <h1 className="font-display text-2xl font-semibold tracking-[0.38em] text-gold-100 sm:text-3xl">
          WINNER SELECTION
        </h1>
        <p className="mt-2 text-xs tracking-[0.18em] text-slate-300 sm:text-sm">
          {subtitle ?? "The moment we've all been waiting for"}
        </p>
      </div>
    </header>
  );
}
