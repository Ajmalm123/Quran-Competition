import { ListChecks, Trophy, Users, Calendar } from "lucide-react";

interface WeekItem {
  id: number;
  week_number: number;
  status: string;
  quiz_date: string;
}

interface Props {
  total: number;
  winners: number;
  onToggleList: () => void;
  listOpen: boolean;
  weeks?: WeekItem[];
  currentWeekId?: number;
  onSelectWeek?: (weekId: number) => void;
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
    <div className="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm text-slate-300">
      {weeks && weeks.length > 1 && onSelectWeek && (
        <>
          <div className="flex items-center gap-2">
            <Calendar className="h-4 w-4 text-gold-400" aria-hidden />
            <select
              value={currentWeekId}
              onChange={(e) => onSelectWeek(Number(e.target.value))}
              aria-label="Select Quiz Week"
              className="cursor-pointer rounded-full border border-gold-400/30 bg-ink-900/90 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-gold-200 transition hover:border-gold-300 focus:outline-none"
            >
              {weeks.map((w) => (
                <option key={w.id} value={w.id} className="bg-ink-900 text-slate-200">
                  Week {w.week_number} ({w.quiz_date})
                </option>
              ))}
            </select>
          </div>
          <span className="hidden h-4 w-px bg-white/15 sm:block" aria-hidden />
        </>
      )}

      <div className="flex items-center gap-2.5">
        <Users className="h-4 w-4 text-gold-400" aria-hidden />
        <span>
          <strong className="font-semibold text-gold-100">{total}</strong> eligible participants
        </span>
      </div>
      <span className="hidden h-4 w-px bg-white/15 sm:block" aria-hidden />
      <div className="flex items-center gap-2.5">
        <Trophy className="h-4 w-4 text-gold-400" aria-hidden />
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
