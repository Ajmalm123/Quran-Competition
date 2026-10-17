import { useMemo, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { X, Users, Search, Trophy } from "lucide-react";
import type { Participant } from "../data";
import { useDialogFocus } from "../hooks";

interface Props {
  open: boolean;
  onClose: () => void;
  participants: Participant[];
  winnerId?: number;
  onLoadSampleData?: () => void;
}

export function getDisplayPlace(p: { place?: string; locality?: string; territory?: string }): string {
  const loc = (p.locality || "").trim();
  const ter = (p.territory || "").trim();
  const rawPlace = (p.place || "").trim();

  let place = "";

  if (loc && ter) {
    if (loc.toLowerCase() === ter.toLowerCase()) {
      place = loc;
    } else if (loc.toLowerCase().includes(ter.toLowerCase())) {
      place = loc;
    } else if (ter.toLowerCase().includes(loc.toLowerCase())) {
      place = ter;
    } else {
      place = `${loc} · ${ter}`;
    }
  } else {
    place = loc || ter || rawPlace;
  }

  if (!place) return "";

  // Deduplicate any repeated parts separated by ' · ' or ' • ' (e.g., "Thrissur · Thrissur")
  const delimiter = place.includes(" · ") ? " · " : place.includes(" • ") ? " • " : null;
  if (delimiter) {
    const parts = place.split(delimiter).map((s) => s.trim()).filter(Boolean);
    const uniqueParts: string[] = [];
    for (const part of parts) {
      if (!uniqueParts.some((existing) => existing.toLowerCase() === part.toLowerCase())) {
        uniqueParts.push(part);
      }
    }
    return uniqueParts.join(" · ");
  }

  return place;
}

export function ParticipantPanel({
  open,
  onClose,
  participants,
  winnerId,
  onLoadSampleData,
}: Props) {
  const [search, setSearch] = useState("");
  const ref = useDialogFocus<HTMLElement>(open, onClose, "[data-close]");

  const filtered = useMemo(() => {
    if (!search.trim()) return participants;
    const q = search.toLowerCase();
    return participants.filter((p) => {
      const name = p.name.toLowerCase();
      const place = getDisplayPlace(p).toLowerCase();
      return name.includes(q) || place.includes(q);
    });
  }, [participants, search]);

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
            aria-label="Eligible participant list"
            className="fixed inset-y-0 right-0 z-50 flex w-full max-w-md flex-col border-l border-gold-400/25 bg-ink-900/95 shadow-[0_0_80px_-10px_rgba(232,185,74,0.25)] backdrop-blur-xl"
            initial={{ x: "100%" }}
            animate={{ x: 0 }}
            exit={{ x: "100%" }}
            transition={{ type: "spring", damping: 32, stiffness: 300 }}
          >
            {/* Header */}
            <div className="flex items-center justify-between border-b border-white/10 px-6 py-5">
              <div>
                <h2 className="font-display text-2xl font-semibold tracking-wide text-gold-100">
                  50/50 Contestants
                </h2>
                <p className="mt-0.5 text-xs uppercase tracking-[0.2em] text-slate-400">
                  {participants.length} eligible for lucky draw
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

            {/* Search */}
            <div className="border-b border-white/10 px-6 py-3">
              <div className="relative flex items-center">
                <Search className="pointer-events-none absolute left-3.5 h-4 w-4 text-slate-400" aria-hidden />
                <input
                  type="text"
                  placeholder="Search by name, locality, or place..."
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="w-full rounded-xl border border-white/10 bg-white/[0.04] py-2 pl-10 pr-4 text-xs text-slate-200 placeholder-slate-400 focus:border-gold-300 focus:outline-none focus:ring-1 focus:ring-gold-300"
                />
              </div>
            </div>

            {/* List */}
            {filtered.length === 0 ? (
              <div className="flex flex-1 flex-col items-center justify-center p-6 text-center text-slate-400">
                <Users className="mb-3 h-10 w-10 text-slate-600" aria-hidden />
                <p className="text-sm font-semibold text-slate-300">
                  {participants.length === 0
                    ? "No Eligible Contestants"
                    : "No Matching Contestants"}
                </p>
                <p className="mt-1 text-xs text-slate-400">
                  {participants.length === 0
                    ? "Only participants with a full 50/50 score appear here."
                    : `No participants matched "${search}".`}
                </p>
                {participants.length === 0 && onLoadSampleData && (
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
              <ol className="flex-1 divide-y divide-white/[0.05] overflow-y-auto px-4 py-3">
                {filtered.map((p, idx) => {
                  const isWinner = p.id === winnerId;
                  const displayPlace = getDisplayPlace(p);

                  return (
                    <li
                      key={p.id}
                      className={`flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-sm transition ${
                        isWinner
                          ? "bg-gold-400/20 text-gold-100 ring-1 ring-gold-400/50"
                          : "text-slate-200 hover:bg-white/[0.04]"
                      }`}
                    >
                      <span className="w-6 text-right text-xs tabular-nums text-slate-500 shrink-0">
                        {idx + 1}
                      </span>
                      <div className="flex-1 min-w-0">
                        <div className="flex items-center gap-2 font-medium">
                          <span className="truncate">{p.name}</span>
                          {isWinner && (
                            <span className="inline-flex items-center gap-1 rounded-full bg-gold-400/20 px-2 py-0.5 text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-gold-200 shrink-0">
                              <Trophy className="h-3 w-3" aria-hidden /> Winner
                            </span>
                          )}
                        </div>
                        {displayPlace && (
                          <div className="text-xs text-slate-400 truncate mt-0.5">
                            {displayPlace}
                          </div>
                        )}
                      </div>
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
