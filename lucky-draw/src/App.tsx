import { useCallback, useRef, useState } from "react";
import { motion, useReducedMotion } from "framer-motion";
import { RotateCcw } from "lucide-react";
import { PARTICIPANTS, type Participant } from "./data";
import type { Phase } from "./types";
import { Header } from "./components/Header";
import { InfoRow } from "./components/InfoRow";
import { Particles } from "./components/Particles";
import { ParticipantPanel } from "./components/ParticipantPanel";
import { Stage } from "./components/Stage";
import { ConfirmModal } from "./components/ConfirmModal";
import { Toast } from "./components/Toast";

interface WeekSummary {
  id: number;
  week_number: number;
  quiz_date: string;
  status: string;
  lucky_draw_status: string;
  eligible_count: number;
  winner: { id: number; name: string } | null;
}

interface InitialData {
  weeks?: WeekSummary[];
  currentWeek?: {
    id: number;
    week_number: number;
    quiz_date?: string;
    status?: string;
  };
  eligibleParticipants?: {
    id: number;
    name: string;
    place?: string;
    whatsapp_number?: string;
    locality?: string;
    territory?: string;
  }[];
  luckyDraw?: {
    id?: number;
    status?: string;
    winner_user_id?: number;
    winner?: {
      id: number;
      name: string;
      place?: string;
      locality?: string;
      territory?: string;
    };
  };
  adminUser?: string;
}

declare global {
  interface Window {
    __INITIAL_DATA__?: InitialData;
    __CSRF_TOKEN__?: string;
  }
}

// React 18 typings don't include `inert`; it keeps dimmed UI out of the tab order while rolling.
const inertProps = (on: boolean) => (on ? ({ inert: "" } as Record<string, string>) : {});

const getInitialParticipants = (): Participant[] => {
  if (typeof window !== "undefined" && window.__INITIAL_DATA__) {
    const list = window.__INITIAL_DATA__.eligibleParticipants;
    if (Array.isArray(list)) {
      return list.map((p) => ({
        id: p.id,
        name: p.name,
        place: p.place || p.locality || p.territory || "",
        locality: p.locality,
        territory: p.territory,
      }));
    }
  }
  // Standalone preview fallback
  return PARTICIPANTS;
};

const getInitialState = () => {
  const luckyDraw = typeof window !== "undefined" ? window.__INITIAL_DATA__?.luckyDraw : null;
  if (luckyDraw?.winner && luckyDraw?.status === "published") {
    return {
      phase: "published" as Phase,
      winner: {
        id: luckyDraw.winner.id,
        name: luckyDraw.winner.name,
        place: luckyDraw.winner.place || luckyDraw.winner.locality || luckyDraw.winner.territory || "",
      },
    };
  }
  if (luckyDraw?.winner && luckyDraw?.status === "drawn") {
    return {
      phase: "revealed" as Phase,
      winner: {
        id: luckyDraw.winner.id,
        name: luckyDraw.winner.name,
        place: luckyDraw.winner.place || luckyDraw.winner.locality || luckyDraw.winner.territory || "",
      },
    };
  }
  return {
    phase: "ready" as Phase,
    winner: null as Participant | null,
  };
};

export default function App() {
  const reduced = !!useReducedMotion();
  const initialData = typeof window !== "undefined" ? window.__INITIAL_DATA__ : undefined;
  const isProduction = !!initialData?.currentWeek?.id;

  const [participants, setParticipants] = useState<Participant[]>(getInitialParticipants);
  const [initial] = useState(getInitialState);
  const [phase, setPhase] = useState<Phase>(initial.phase);
  const [winner, setWinner] = useState<Participant | null>(initial.winner);
  const [panelOpen, setPanelOpen] = useState(false);
  const [loading, setLoading] = useState(false);

  const [toast, setToast] = useState<{
    open: boolean;
    title: string;
    description: string;
    type: "success" | "error";
  }>({
    open: false,
    title: "",
    description: "",
    type: "success",
  });

  const lock = useRef(false); // blocks repeated clicks while rolling

  const rolling = phase === "rolling";
  const celebrating = phase === "revealed" || phase === "confirming" || phase === "published";
  const winnersSelected = celebrating && winner ? 1 : 0;

  const currentWeek = initialData?.currentWeek;
  const subtitle = currentWeek
    ? `Week ${currentWeek.week_number} · ${currentWeek.quiz_date ?? ""} · The moment we've all been waiting for`
    : undefined;

  const choose = useCallback(() => {
    if (lock.current || phase !== "ready" || participants.length === 0) return;
    lock.current = true;
    // The winner is picked in the browser *before* the animation
    // and stays fixed, so the roll always lands on the right name.
    const chosen = participants[Math.floor(Math.random() * participants.length)];
    setWinner(chosen);
    setPanelOpen(false);
    setPhase("rolling");
  }, [phase, participants]);

  const rollDone = useCallback(() => {
    lock.current = false;
    setPhase("revealed");
  }, []);

  const reset = useCallback(async () => {
    lock.current = false;
    setWinner(null);
    setPhase("ready");
    setPanelOpen(false);

    if (isProduction && currentWeek?.id) {
      try {
        const csrf =
          window.__CSRF_TOKEN__ ||
          document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
          "";
        await fetch("/admin/lucky-draw/api/redraw", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": csrf,
          },
          credentials: "same-origin",
          body: JSON.stringify({ week_id: currentWeek.id }),
        });
        setToast({
          open: true,
          title: "Draw Reset",
          description: `Lucky draw for Week ${currentWeek.week_number} has been reset.`,
          type: "success",
        });
      } catch (err) {
        console.error("Failed to reset lucky draw in backend", err);
      }
    }
  }, [isProduction, currentWeek]);

  const publish = useCallback(async () => {
    if (!winner) return;

    if (!isProduction || !currentWeek?.id) {
      // Standalone demo mode
      setPhase("published");
      setToast({
        open: true,
        title: "Result Published",
        description: "Demo prototype — result displayed in preview mode.",
        type: "success",
      });
      return;
    }

    setLoading(true);
    try {
      const csrf =
        window.__CSRF_TOKEN__ ||
        document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
        "";

      const res = await fetch("/admin/lucky-draw/api/publish", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": csrf,
        },
        credentials: "same-origin",
        body: JSON.stringify({
          week_id: currentWeek.id,
          winner_user_id: winner.id,
        }),
      });

      const data = await res.json().catch(() => ({}));

      if (res.ok && data.success !== false) {
        setPhase("published");
        setToast({
          open: true,
          title: "Result Published!",
          description:
            data.message || `Winner ${winner.name} is now published live on the public website.`,
          type: "success",
        });
      } else {
        const msg = data.message || `Server returned error (${res.status}).`;
        setToast({
          open: true,
          title: "Failed to Publish",
          description: msg,
          type: "error",
        });
        setPhase("revealed");
      }
    } catch (err) {
      console.error("Network or publishing error", err);
      setToast({
        open: true,
        title: "Network Error",
        description: "Could not communicate with the server. Please check your connection.",
        type: "error",
      });
      setPhase("revealed");
    } finally {
      setLoading(false);
    }
  }, [isProduction, currentWeek, winner]);

  const selectWeek = useCallback((weekId: number) => {
    window.location.search = `?week_id=${weekId}`;
  }, []);

  const loadSampleParticipants = useCallback(() => {
    setParticipants(PARTICIPANTS);
    setToast({
      open: true,
      title: "Sample Data Loaded",
      description: "Loaded 36 sample participants for ceremony rehearsal.",
      type: "success",
    });
  }, []);

  const closeToast = useCallback(() => {
    setToast((prev) => ({ ...prev, open: false }));
  }, []);

  return (
    <div className="relative isolate min-h-screen overflow-hidden bg-ink-950">
      {/* Background lighting */}
      <div
        aria-hidden
        className="pointer-events-none absolute inset-0 -z-10"
        style={{
          background:
            "radial-gradient(ellipse 80% 55% at 50% 0%, rgba(24,32,66,0.95) 0%, rgba(10,14,28,0.7) 55%, transparent 100%), radial-gradient(ellipse 60% 50% at 50% 105%, rgba(184,128,31,0.18) 0%, transparent 70%), #05070f",
        }}
      />
      <div className="absolute inset-0 -z-10">
        <Particles celebrate={celebrating} />
      </div>

      {/* Edge vignette: darkens the surroundings while rolling */}
      <motion.div
        aria-hidden
        className="pointer-events-none fixed inset-0 z-30"
        style={{
          background:
            "radial-gradient(ellipse 60% 55% at 50% 50%, transparent 0%, rgba(5,7,15,0.82) 100%)",
        }}
        animate={{ opacity: rolling ? 1 : 0 }}
        transition={{ duration: 0.8 }}
      />

      {/* Screen-reader announcements */}
      <p className="sr-only" aria-live="polite">
        {rolling && "Drawing in progress."}
        {celebrating && winner && `Winner selected: ${winner.name}.`}
        {phase === "published" && " Result published."}
      </p>

      <div className="relative mx-auto flex min-h-screen w-full max-w-[1600px] flex-col gap-8 px-5 py-6 sm:px-10 sm:py-8 lg:gap-10">
        <motion.div
          className="flex flex-col gap-6"
          animate={{ opacity: rolling ? 0.3 : 1 }}
          transition={{ duration: 0.6 }}
          style={{ pointerEvents: rolling ? "none" : "auto" }}
          {...inertProps(rolling)}
        >
          <Header phase={phase} subtitle={subtitle} />
          <InfoRow
            total={participants.length}
            winners={winnersSelected}
            listOpen={panelOpen}
            onToggleList={() => setPanelOpen((v) => !v)}
            weeks={initialData?.weeks}
            currentWeekId={currentWeek?.id}
            onSelectWeek={selectWeek}
          />
        </motion.div>

        <main className="flex flex-1 items-center">
          <Stage
            phase={phase}
            winner={winner}
            participants={participants}
            reduced={reduced}
            onChoose={choose}
            onRollDone={rollDone}
            onPublish={() => setPhase("confirming")}
            onBack={reset}
          />
        </main>

        <motion.footer
          className="flex flex-col items-center justify-between gap-3 text-xs text-slate-400 sm:flex-row"
          animate={{ opacity: rolling ? 0.3 : 1 }}
          transition={{ duration: 0.6 }}
          {...inertProps(rolling)}
        >
          <span className="tracking-[0.14em]">
            {isProduction
              ? `Live Lucky Draw Ceremony · ${initialData?.adminUser ?? "Administrator"}`
              : "Frontend prototype · all data is local sample data"}
          </span>
          <button
            type="button"
            onClick={reset}
            className="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 font-semibold uppercase tracking-[0.2em] text-slate-300 transition hover:border-gold-300/60 hover:text-gold-100"
          >
            <RotateCcw className="h-3.5 w-3.5" aria-hidden /> Reset draw
          </button>
        </motion.footer>
      </div>

      <ParticipantPanel
        open={panelOpen}
        onClose={() => setPanelOpen(false)}
        participants={participants}
        winnerId={celebrating ? winner?.id : undefined}
        onLoadSampleData={participants.length === 0 ? loadSampleParticipants : undefined}
      />

      <ConfirmModal
        open={phase === "confirming"}
        winnerName={winner?.name ?? ""}
        winnerPlace={winner?.place}
        onCancel={() => setPhase("revealed")}
        onConfirm={publish}
        isProduction={isProduction}
        loading={loading}
      />

      <Toast
        open={toast.open}
        onClose={closeToast}
        title={toast.title}
        description={toast.description}
        type={toast.type}
      />
    </div>
  );
}
