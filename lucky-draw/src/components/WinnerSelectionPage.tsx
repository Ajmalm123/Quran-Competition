import { useCallback, useRef, useState } from 'react';
import confetti from 'canvas-confetti';
import { RotateCcw } from 'lucide-react';
import { AnimatePresence } from 'motion/react';

import type { DrawStatus, Participant, WeekInfo } from '../types';
import {
  SAMPLE_PARTICIPANTS,
  callingCodeToIso,
} from '../types';

import ParticipantStats from './ParticipantStats';
import ParticipantDrawer from './ParticipantDrawer';
import RollingNameDisplay from './RollingNameDisplay';
import WinnerReveal from './WinnerReveal';
import PublishConfirmationModal from './PublishConfirmationModal';
import Toast from './Toast';

// Global window declarations for backend-injected data
declare global {
  interface Window {
    __CSRF_TOKEN__?: string;
    __INITIAL_DATA__?: {
      weeks: WeekInfo[];
      currentWeek: {
        id: number;
        week_number: number;
        quiz_date?: string;
        status?: string;
      } | null;
      eligibleParticipants: Array<{
        id: number;
        name: string;
        place?: string;
        country_code?: string;
        whatsapp_number?: string;
        locality?: string;
        territory?: string;
      }>;
      luckyDraw?: {
        id: number;
        status: 'pending' | 'drawn' | 'published';
        winner_user_id?: number | null;
        winner?: {
          id: number;
          name: string;
          place?: string;
          country_code?: string;
          whatsapp_number?: string;
          locality?: string;
          territory?: string;
        } | null;
        drawn_at?: string;
        published_at?: string;
      } | null;
      adminUser?: string;
    };
  }
}

// Confetti burst – warm amber/terracotta palette matching Thibyan
function fireConfetti() {
  const count = 140;
  const defaults: confetti.Options = {
    origin: { y: 0.55 },
    colors: ['#E5A93B', '#F0BE5C', '#ffffff', '#C85A32', '#F5EDE0', '#C4882A'],
    startVelocity: 32,
    spread: 55,
    ticks: 80,
    gravity: 1.1,
    scalar: 1.0,
    shapes: ['circle', 'square'],
  };

  const fire = (particleRatio: number, opts: confetti.Options) => {
    confetti({ ...defaults, ...opts, particleCount: Math.floor(count * particleRatio) });
  };

  fire(0.25, { spread: 26, startVelocity: 55 });
  fire(0.2, { spread: 60 });
  fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
  fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
  fire(0.1, { spread: 120, startVelocity: 45 });
}

// Default fallback weeks for preview or local mode
const DEFAULT_WEEKS: WeekInfo[] = Array.from({ length: 10 }, (_, i) => ({
  id: i + 1,
  week_number: i + 1,
  quiz_date: undefined,
  status: i === 0 ? 'completed' : 'upcoming',
  lucky_draw_status: 'pending',
  eligible_count: SAMPLE_PARTICIPANTS.length,
}));

function mapParticipant(raw: {
  id: number;
  name: string;
  place?: string;
  country_code?: string;
  whatsapp_number?: string;
  locality?: string;
  territory?: string;
}): Participant {
  return {
    id: raw.id,
    name: raw.name,
    countryCode: callingCodeToIso(raw.country_code),
    countryCallingCode: raw.country_code,
    place: raw.place || '',
    mobile: raw.whatsapp_number || '',
    locality: raw.locality,
    territory: raw.territory,
  };
}

export default function WinnerSelectionPage() {
  const initialData = typeof window !== 'undefined' ? window.__INITIAL_DATA__ : undefined;
  const isBackend = !!initialData;

  // Weeks state
  const [weeks, setWeeks] = useState<WeekInfo[]>(() => {
    if (initialData?.weeks && initialData.weeks.length > 0) {
      return initialData.weeks;
    }
    return DEFAULT_WEEKS;
  });

  // Current selected week ID
  const [selectedWeekId, setSelectedWeekId] = useState<number>(() => {
    if (initialData?.currentWeek?.id) {
      return initialData.currentWeek.id;
    }
    if (typeof window !== 'undefined') {
      const param = new URLSearchParams(window.location.search).get('week_id');
      if (param && !isNaN(Number(param))) {
        const found = (initialData?.weeks || DEFAULT_WEEKS).find(w => w.id === Number(param));
        if (found) return found.id;
      }
    }
    return (initialData?.weeks?.[0]?.id ?? DEFAULT_WEEKS[0].id);
  });

  const currentWeek = weeks.find(w => w.id === selectedWeekId) || weeks[0];

  // Participants list for the selected week
  const [participants, setParticipants] = useState<Participant[]>(() => {
    if (initialData?.eligibleParticipants && Array.isArray(initialData.eligibleParticipants)) {
      return initialData.eligibleParticipants.map(mapParticipant);
    }
    return SAMPLE_PARTICIPANTS;
  });

  // Status and winner state
  const [status, setStatus] = useState<DrawStatus>(() => {
    const draw = initialData?.luckyDraw;
    if (draw?.winner && draw.status === 'published') return 'published';
    if (draw?.winner && draw.status === 'drawn') return 'winner-selected';
    return 'ready';
  });

  const [winner, setWinner] = useState<Participant | null>(() => {
    const draw = initialData?.luckyDraw;
    if (draw?.winner) {
      return mapParticipant(draw.winner);
    }
    return null;
  });

  const [drawerOpen, setDrawerOpen] = useState(false);
  const [toastVisible, setToastVisible] = useState(false);
  const [toastMessage, setToastMessage] = useState('Result published successfully. The winner has been announced.');
  const [isPublishing, setIsPublishing] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  // Switch week handler
  const handleSelectWeek = useCallback(async (weekId: number) => {
    if (weekId === selectedWeekId && status !== 'ready') return;
    setSelectedWeekId(weekId);
    setStatus('ready');
    setWinner(null);
    setToastVisible(false);

    if (isBackend) {
      try {
        const res = await fetch(`/admin/lucky-draw/api/data?week_id=${weekId}`, {
          headers: { Accept: 'application/json' },
          credentials: 'same-origin',
        });
        if (res.ok) {
          const data = await res.json();
          if (data.weeks) setWeeks(data.weeks);
          if (Array.isArray(data.eligibleParticipants)) {
            setParticipants(data.eligibleParticipants.map(mapParticipant));
          }
          if (data.luckyDraw?.winner && data.luckyDraw.status === 'published') {
            setStatus('published');
            setWinner(mapParticipant(data.luckyDraw.winner));
          } else if (data.luckyDraw?.winner && data.luckyDraw.status === 'drawn') {
            setStatus('winner-selected');
            setWinner(mapParticipant(data.luckyDraw.winner));
          }
        }
      } catch (err) {
        console.error('Failed to load week data:', err);
      }
    } else {
      // Local demo mode
      setParticipants(SAMPLE_PARTICIPANTS);
    }

    try {
      const url = new URL(window.location.href);
      url.searchParams.set('week_id', String(weekId));
      window.history.pushState({ week_id: weekId }, '', url.toString());
    } catch {}
  }, [selectedWeekId, status, isBackend]);

  const handleSelectWinner = useCallback((p: Participant) => {
    setWinner(p);
    setStatus('selecting');
  }, []);

  const handleAnimationComplete = useCallback(() => {
    setStatus('winner-selected');
    setTimeout(() => {
      fireConfetti();
      setTimeout(fireConfetti, 800);
    }, 300);
  }, []);

  const handlePublishClick = useCallback(() => setStatus('confirm-publish'), []);

  const handlePublishConfirm = useCallback(async () => {
    if (!winner) return;

    if (isBackend && currentWeek?.id) {
      setIsPublishing(true);
      try {
        const token = window.__CSRF_TOKEN__ ||
          document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ||
          '';

        const res = await fetch('/admin/lucky-draw/api/publish', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': token,
          },
          credentials: 'same-origin',
          body: JSON.stringify({
            week_id: currentWeek.id,
            winner_user_id: winner.id,
          }),
        });

        const data = await res.json().catch(() => ({}));
        if (res.ok && data.success !== false) {
          setStatus('published');
          setToastMessage(data.message || `Winner ${winner.name} published successfully! Live on public website.`);
          setToastVisible(true);
          setWeeks(prev =>
            prev.map(w => (w.id === currentWeek.id ? { ...w, lucky_draw_status: 'published' } : w))
          );
        } else {
          alert(data.message || `Publish error (${res.status})`);
          setStatus('winner-selected');
        }
      } catch (err) {
        console.error('Error publishing lucky draw:', err);
        alert('Network error while publishing. Please try again.');
        setStatus('winner-selected');
      } finally {
        setIsPublishing(false);
      }
    } else {
      // Standalone simulation mode
      setStatus('publishing');
      setTimeout(() => {
        setStatus('published');
        setToastMessage('Result published successfully. The winner has been announced.');
        setToastVisible(true);
      }, 800);
    }
  }, [winner, isBackend, currentWeek]);

  const handlePublishCancel = useCallback(() => setStatus('winner-selected'), []);

  const handleReset = useCallback(async () => {
    if (isBackend && currentWeek?.id) {
      try {
        const token = window.__CSRF_TOKEN__ ||
          document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ||
          '';

        await fetch('/admin/lucky-draw/api/redraw', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': token,
          },
          credentials: 'same-origin',
          body: JSON.stringify({ week_id: currentWeek.id }),
        });
      } catch (err) {
        console.error('Failed to reset draw on server:', err);
      }
    }

    setStatus('ready');
    setWinner(null);
    setToastVisible(false);
  }, [isBackend, currentWeek]);

  const handleToastDismiss = useCallback(() => setToastVisible(false), []);

  const isWinnerVisible =
    status === 'winner-selected' ||
    status === 'confirm-publish' ||
    status === 'publishing' ||
    status === 'published';

  return (
    <div
      ref={containerRef}
      style={{
        minHeight: '100vh',
        height: '100vh',
        display: 'flex',
        flexDirection: 'column',
        background: '#3D1F14',
        position: 'relative',
        overflow: 'hidden',
      }}
      role="main"
    >
      {/* Week Selector & Participant Controls Bar */}
      <div style={{ position: 'relative', zIndex: 10 }}>
        <ParticipantStats
          weeks={weeks}
          selectedWeekId={selectedWeekId}
          selectedWeekNumber={currentWeek?.week_number ?? 1}
          onSelectWeek={handleSelectWeek}
          onViewParticipants={() => setDrawerOpen(true)}
        />
      </div>

      {/* Main stage */}
      <div
        style={{
          flex: 1,
          position: 'relative',
          zIndex: 2,
          minHeight: 0,
          display: 'flex',
          flexDirection: 'column',
        }}
      >
        <RollingNameDisplay
          status={status}
          winner={winner}
          participants={participants}
          onSelectWinner={handleSelectWinner}
          onAnimationComplete={handleAnimationComplete}
          selectedWeekNumber={currentWeek?.week_number ?? 1}
          selectedWeekDate={currentWeek?.quiz_date}
        />
      </div>

      {/* Winner reveal action bar */}
      <AnimatePresence>
        {isWinnerVisible && winner && (
          <div style={{ position: 'relative', zIndex: 3 }}>
            <WinnerReveal
              winner={winner}
              status={status}
              onPublish={handlePublishClick}
              onReset={handleReset}
            />
          </div>
        )}
      </AnimatePresence>

      {/* Footer */}
      <footer
        style={{
          padding: '10px 28px',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          borderTop: '1px solid rgba(255,255,255,0.06)',
          background: '#2D1508',
          flexShrink: 0,
          position: 'relative',
          zIndex: 2,
        }}
      >
        <span
          style={{
            fontFamily: 'var(--font-display)',
            fontSize: 9,
            letterSpacing: '0.28em',
            color: 'rgba(255,245,230,0.25)',
            textTransform: 'uppercase',
          }}
        >
          TIBIYAN — Aslam Quran Award 2026
        </span>

        <button
          className="btn-ghost"
          onClick={handleReset}
          id="btn-reset-demo-footer"
          aria-label="Reset lucky draw to initial state"
          style={{ fontSize: 10, padding: '5px 12px' }}
        >
          <RotateCcw size={10} />
          {isBackend ? 'Reset Draw' : 'Reset Demo'}
        </button>
      </footer>

      {/* Participant Drawer */}
      <ParticipantDrawer
        participants={participants}
        winner={winner}
        isOpen={drawerOpen}
        onClose={() => setDrawerOpen(false)}
      />

      {/* Publish Confirmation Modal */}
      <PublishConfirmationModal
        isOpen={status === 'confirm-publish' || status === 'publishing'}
        winner={winner ?? { id: 0, name: '', countryCode: '', place: '', mobile: '' }}
        isPublishing={isPublishing}
        onConfirm={handlePublishConfirm}
        onCancel={handlePublishCancel}
      />

      {/* Success Toast */}
      <Toast
        message={toastMessage}
        visible={toastVisible}
        onDismiss={handleToastDismiss}
      />
    </div>
  );
}
