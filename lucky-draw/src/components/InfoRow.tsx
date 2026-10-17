import { ListChecks, Trophy, Users, Calendar, ChevronDown } from "lucide-react";

export interface WeekItem {
  id: number;
  week_number: number;
  status?: string;
  quiz_date?: string;
  lucky_draw_status?: string;
  eligible_count?: number;
}

interface Props {
  total: number;
  winners: number;
  onToggleList: () => void;
  listOpen: boolean;
  weeks?: WeekItem[];
  currentWeekId?: number;
  onSelectWeek: (weekId: number) => void;
}

export function InfoRow({
  total,
  winners,
  onToggleList,
  listOpen,
  weeks,
  currentWeekId,
  onSelectWeek,
}: Props) {
  return (
    <div className="flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-sm text-slate-300">
      {/* Week Selector Dropdown */}
      {weeks && weeks.length > 0 && (
        <>
          <div className="relative inline-flex items-center">
            <div className="group flex items-center gap-2 rounded-full border border-gold-400/40 bg-gradient-to-r from-ink-900/95 via-gold-500/10 to-ink-900/95 px-3.5 py-1.5 shadow-[0_0_20px_rgba(240,208,120,0.12)] transition duration-200 hover:border-gold-300/80 hover:shadow-[0_0_24px_rgba(240,208,120,0.22)]">
              <Calendar className="h-4 w-4 shrink-0 text-gold-400 transition-transform duration-200 group-hover:scale-110" aria-hidden />
              <label htmlFor="week-select" className="shrink-0 text-xs font-semibold uppercase tracking-[0.2em] text-gold-200">
                Week:
              </label>
              <div className="relative flex items-center">
                <select
                  id="week-select"
                  value={currentWeekId}
                  onChange={(e) => onSelectWeek(Number(e.target.value))}
                  aria-label="Select Quiz Week"
                  className="cursor-pointer appearance-none bg-transparent pr-6 text-xs font-bold uppercase tracking-wider text-gold-100 transition focus:outline-none focus:text-gold-200"
                >
                  {weeks.map((w) => (
                    <option key={w.id} value={w.id} className="bg-[#0b0f20] text-gold-100 py-1 font-sans font-medium normal-case">
                      Week {w.week_number} {w.quiz_date ? `· ${w.quiz_date}` : ""} {w.eligible_count !== undefined ? `(${w.eligible_count} eligible)` : ""}
                    </option>
                  ))}
                </select>
                <ChevronDown className="pointer-events-none absolute right-0.5 h-3.5 w-3.5 text-gold-400 transition-transform duration-200 group-hover:translate-y-0.5" aria-hidden />
              </div>
            </div>
          </div>
          <span className="hidden h-4 w-px bg-white/15 sm:block" aria-hidden />
        </>
      )}

      <div className="flex items-center gap-2.5">
        <Users className="h-4 w-4 shrink-0 text-gold-400" aria-hidden />
        <span>
          <strong className="font-semibold text-gold-100">{total}</strong> eligible participants
        </span>
      </div>

      <span className="hidden h-4 w-px bg-white/15 sm:block" aria-hidden />

      <div className="flex items-center gap-2.5">
        <Trophy className="h-4 w-4 shrink-0 text-gold-400" aria-hidden />
        <span>
          Winners selected: <strong className="font-semibold text-gold-100">{winners}</strong>
        </span>
      </div>

      <span className="hidden h-4 w-px bg-white/15 sm:block" aria-hidden />

      <button
        type="button"
        onClick={onToggleList}
        aria-expanded={listOpen}
        aria-controls="participant-panel"
        className="group inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-gold-200 transition hover:bg-gold-400/10 hover:text-gold-100"
      >
        <ListChecks className="h-4 w-4 transition group-hover:scale-110" aria-hidden />
        <span className="text-xs font-semibold uppercase tracking-[0.2em]">Participants</span>
      </button>
    </div>
  );
}
