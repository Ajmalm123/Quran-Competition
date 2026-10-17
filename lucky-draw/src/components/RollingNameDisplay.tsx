import { useEffect, useRef, useState, useCallback } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Sparkles } from 'lucide-react';
import type { DrawStatus, Participant } from '../types';
import {
  getCountryFlag,
  getCountryName,
  formatParticipantLocation,
  maskMobileNumber,
} from '../types';
import CelebrationEffects from './CelebrationEffects';
import thibyanLogo from '../assets/thibyan-logo.png';

interface RollingNameDisplayProps {
  status: DrawStatus;
  winner: Participant | null;
  participants: Participant[];
  onSelectWinner: (winner: Participant) => void;
  onAnimationComplete: () => void;
  selectedWeekNumber?: number;
  selectedWeekDate?: string;
  logoSrc?: string;
}

// Calculate dynamic week details: Week 1 starts on October 16, 2026 at seven-day intervals
export function getWeekDateString(weekNumber: number, customDate?: string): string {
  if (customDate && customDate !== '—') {
    return `Week ${weekNumber} | ${customDate}`;
  }
  const date = new Date(2026, 9, 16 + (weekNumber - 1) * 7);
  const formattedDate = date.toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  });
  return `Week ${weekNumber} | ${formattedDate}`;
}

export const DEFAULT_TIBIYAN_LOGO_SRC = thibyanLogo;

// Build a shuffled sequence ending with the winner
function buildSequence(pool: Participant[], winner: Participant, totalNames: number): string[] {
  const eligiblePool = pool.filter(p => p.id !== winner.id);
  const source = eligiblePool.length > 0 ? eligiblePool : [winner];
  const result: string[] = [];
  for (let i = 0; i < totalNames - 1; i++) {
    result.push(source[i % source.length].name);
  }
  result.push(winner.name);
  return result;
}

export default function RollingNameDisplay({
  status,
  winner,
  participants,
  onSelectWinner,
  onAnimationComplete,
  selectedWeekNumber = 1,
  selectedWeekDate,
  logoSrc = DEFAULT_TIBIYAN_LOGO_SRC,
}: RollingNameDisplayProps) {
  const [logoError, setLogoError] = useState(false);
  const [displayName, setDisplayName] = useState('WHO WILL IT BE?');
  const [selectedWinner, setSelectedWinner] = useState<Participant | null>(null);
  const [isRolling, setIsRolling] = useState(false);
  const [frameKey, setFrameKey] = useState(0);
  const timeoutsRef = useRef<ReturnType<typeof setTimeout>[]>([]);
  const isAnimatingRef = useRef(false);

  const clearAll = useCallback(() => {
    timeoutsRef.current.forEach(clearTimeout);
    timeoutsRef.current = [];
  }, []);

  // Reset when status goes back to ready
  useEffect(() => {
    if (status === 'ready') {
      clearAll();
      isAnimatingRef.current = false;
      setIsRolling(false);
      setDisplayName('WHO WILL IT BE?');
      setSelectedWinner(null);
    }
  }, [status, clearAll]);

  const startAnimation = useCallback(() => {
    if (isAnimatingRef.current || participants.length === 0) return;
    isAnimatingRef.current = true;

    // Pick winner first – before any animation
    const idx = Math.floor(Math.random() * participants.length);
    const chosenWinner = participants[idx];
    setSelectedWinner(chosenWinner);
    onSelectWinner(chosenWinner);

    const sequence = buildSequence(participants, chosenWinner, 60);
    setIsRolling(true);

    let sequenceIdx = 0;

    // Phase 1: rapid cycling (0–3.4s) – 42 names at 80ms each
    const RAPID_COUNT = 42;
    for (let i = 0; i < RAPID_COUNT; i++) {
      const delay = i * 80;
      const t = setTimeout(() => {
        setDisplayName(sequence[sequenceIdx % sequence.length]);
        sequenceIdx++;
        setFrameKey(k => k + 1);
      }, delay);
      timeoutsRef.current.push(t);
    }

    const rapidEnd = RAPID_COUNT * 80; // ~3360ms

    // Phase 2: slowing intervals
    const slowIntervals = [130, 200, 300, 450, 650, 900, 1200];
    let slowTime = rapidEnd;
    for (const interval of slowIntervals) {
      slowTime += interval;
      const captured = slowTime;
      const t = setTimeout(() => {
        setDisplayName(sequence[sequenceIdx % sequence.length]);
        sequenceIdx++;
        setFrameKey(k => k + 1);
      }, captured);
      timeoutsRef.current.push(t);
    }

    // Final reveal – winner
    const revealTime = slowTime + 1200;
    const t2 = setTimeout(() => {
      setDisplayName(chosenWinner.name);
      setFrameKey(k => k + 1);
      setIsRolling(false);

      const t3 = setTimeout(() => {
        isAnimatingRef.current = false;
        onAnimationComplete();
      }, 800);
      timeoutsRef.current.push(t3);
    }, revealTime);
    timeoutsRef.current.push(t2);
  }, [participants, onSelectWinner, onAnimationComplete]);

  const isSelecting = status === 'selecting';
  const isWinnerRevealed =
    status === 'winner-selected' ||
    status === 'confirm-publish' ||
    status === 'publishing' ||
    status === 'published';
  const isPublished = status === 'published';
  const activeWinner = winner ?? selectedWinner;

  return (
    <div
      style={{
        flex: 1,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        position: 'relative',
        overflow: 'hidden',
        padding: '24px 32px',
        background: isWinnerRevealed
          ? 'linear-gradient(160deg, #5C2E1A 0%, #7A3E2E 40%, #6B3525 70%, #4A2519 100%)'
          : 'linear-gradient(160deg, #4A2519 0%, #6B3525 40%, #7A3E2E 70%, #4A2519 100%)',
        transition: 'background 1.2s ease',
      }}
    >
      {/* Subtle geometric dot pattern (Thibyan-style texture) */}
      <div
        className="thibyan-pattern"
        style={{ position: 'absolute', inset: 0, pointerEvents: 'none' }}
        aria-hidden="true"
      />

      {/* Spotlight */}
      <div
        className={isWinnerRevealed ? 'spotlight-intense' : 'spotlight'}
        style={{
          position: 'absolute',
          inset: 0,
          transition: 'all 1.2s ease',
          pointerEvents: 'none',
        }}
        aria-hidden="true"
      />

      {/* Celebration particles */}
      <div style={{ position: 'absolute', inset: 0, pointerEvents: 'none' }}>
        <CelebrationEffects active={isWinnerRevealed && !isPublished} />
      </div>

      {/* Stage content */}
      <div
        style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: 0,
          position: 'relative',
          zIndex: 2,
          width: '100%',
          maxWidth: 800,
        }}
      >
        {/* Dedicated Tibiyan Logo Container */}
        <div
          className="tibiyan-logo-container"
          style={{
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            background: 'transparent',
            marginBottom: 20,
            width: '100%',
            maxWidth: 240,
            height: 64,
          }}
        >
          {logoSrc && !logoError ? (
            <img
              src={logoSrc}
              alt="Tibiyan Logo"
              style={{
                maxWidth: '100%',
                maxHeight: '100%',
                objectFit: 'contain',
                background: 'transparent',
                display: 'block',
              }}
              onError={() => setLogoError(true)}
            />
          ) : (
            <div
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                justifyContent: 'center',
                padding: '8px 24px',
                border: '1px dashed rgba(229,169,59,0.3)',
                borderRadius: 4,
                color: 'rgba(229,169,59,0.55)',
                fontFamily: 'var(--font-display)',
                fontSize: 11,
                letterSpacing: '0.25em',
                textTransform: 'uppercase',
                userSelect: 'none',
                background: 'transparent',
              }}
            >
              TIBIYAN LOGO
            </div>
          )}
        </div>

        {/* Dynamic Week Details */}
        <motion.div
          key={`week-label-${selectedWeekNumber}`}
          initial={{ opacity: 0, y: -6 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.3 }}
          style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 24 }}
        >
          <div className="amber-line" style={{ width: 40 }} />
          <span
            style={{
              fontFamily: 'var(--font-display)',
              fontSize: 10,
              letterSpacing: '0.35em',
              color: 'rgba(229,169,59,0.85)',
              textTransform: 'uppercase',
              whiteSpace: 'nowrap',
            }}
          >
            {getWeekDateString(selectedWeekNumber, selectedWeekDate)}
          </span>
          <div className="amber-line" style={{ width: 40 }} />
        </motion.div>

        {/* Main name frame */}
        <motion.div
          initial={{ opacity: 0, scale: 0.95 }}
          animate={{
            opacity: 1,
            scale: 1,
            boxShadow: isWinnerRevealed
              ? [
                  '0 0 0 1px rgba(229,169,59,0.55), 0 0 30px rgba(229,169,59,0.35), 0 0 70px rgba(229,169,59,0.12)',
                  '0 0 0 1px rgba(229,169,59,0.85), 0 0 50px rgba(229,169,59,0.55), 0 0 110px rgba(229,169,59,0.22)',
                  '0 0 0 1px rgba(229,169,59,0.55), 0 0 30px rgba(229,169,59,0.35), 0 0 70px rgba(229,169,59,0.12)',
                ]
              : isSelecting
              ? '0 0 0 1px rgba(229,169,59,0.4), 0 0 20px rgba(229,169,59,0.2)'
              : '0 0 0 1px rgba(229,169,59,0.22), 0 0 12px rgba(229,169,59,0.07)',
          }}
          transition={{
            duration: 0.8,
            boxShadow: isWinnerRevealed
              ? { duration: 2, repeat: Infinity, ease: 'easeInOut' }
              : { duration: 0.8 },
          }}
          className="winner-frame corner-ornament"
          style={{
            width: '100%',
            minHeight: 220,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '36px 48px',
            position: 'relative',
            overflow: 'hidden',
          }}
        >
          {/* Top-right corner */}
          <div
            aria-hidden="true"
            style={{
              position: 'absolute',
              top: -2,
              right: -2,
              width: 20,
              height: 20,
              borderTop: '2px solid var(--amber)',
              borderRight: '2px solid var(--amber)',
            }}
          />

          {/* Shimmer on selecting */}
          {isSelecting && (
            <div
              style={{
                position: 'absolute',
                inset: 0,
                background: 'linear-gradient(90deg, transparent 0%, rgba(229,169,59,0.06) 50%, transparent 100%)',
                backgroundSize: '200% 100%',
                animation: 'shimmer 1.2s linear infinite',
              }}
              aria-hidden="true"
            />
          )}

          {/* Winner label */}
          <AnimatePresence>
            {isWinnerRevealed && (
              <motion.div
                initial={{ opacity: 0, y: -12 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0 }}
                transition={{ duration: 0.5 }}
                style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16 }}
              >
                <Sparkles size={14} style={{ color: 'var(--amber)' }} />
                <span
                  style={{
                    fontFamily: 'var(--font-display)',
                    fontSize: 12,
                    letterSpacing: '0.4em',
                    color: 'var(--amber-light)',
                    textTransform: 'uppercase',
                  }}
                >
                  We Have a Winner!
                </span>
                <Sparkles size={14} style={{ color: 'var(--amber)' }} />
              </motion.div>
            )}
          </AnimatePresence>

          {/* Rolling / Winner Name Container */}
          <div
            className="rolling-name-container"
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              width: '100%',
              minHeight: 70,
            }}
          >
            <AnimatePresence mode="wait">
              {isWinnerRevealed ? (
                <motion.div
                  key="winner-revealed"
                  initial={{ scale: 0.7, opacity: 0, filter: 'blur(10px)' }}
                  animate={{ scale: 1, opacity: 1, filter: 'blur(0px)' }}
                  transition={{ duration: 0.9, ease: [0.16, 1, 0.3, 1] }}
                  className="text-amber-gradient glow-amber"
                  style={{
                    fontFamily: 'var(--font-display)',
                    fontSize: 'clamp(32px, 5.5vw, 68px)',
                    fontWeight: 800,
                    lineHeight: 1.1,
                    letterSpacing: '0.04em',
                    textAlign: 'center',
                    display: 'flex',
                    alignItems: 'center',
                    gap: 16,
                  }}
                  role="status"
                  aria-live="polite"
                >
                  <span
                    style={{ fontSize: 'clamp(20px, 3vw, 36px)', opacity: 0.9 }}
                    aria-hidden="true"
                  >
                    🎉
                  </span>
                  <span>{activeWinner ? activeWinner.name : displayName}</span>
                </motion.div>
              ) : (
                <motion.div
                  key={`rolling-${frameKey}`}
                  initial={{ opacity: 0, y: -20, filter: 'blur(3px)' }}
                  animate={{ opacity: 1, y: 0, filter: 'blur(0px)' }}
                  exit={{ opacity: 0, y: 20, filter: 'blur(3px)' }}
                  transition={{ duration: 0.12, ease: 'easeOut' }}
                  style={{
                    fontFamily: isRolling ? 'var(--font-display)' : 'var(--font-serif)',
                    fontSize: isRolling
                      ? 'clamp(24px, 4vw, 48px)'
                      : 'clamp(20px, 3.5vw, 40px)',
                    fontWeight: isRolling ? 700 : 400,
                    lineHeight: 1.2,
                    letterSpacing: isRolling ? '0.06em' : '0.14em',
                    color: isRolling ? 'var(--text-on-dark)' : 'var(--text-on-dark-muted)',
                    textAlign: 'center',
                    textTransform: isRolling ? 'none' : 'uppercase',
                  }}
                  aria-live="off"
                  aria-hidden={!isRolling}
                >
                  {displayName}
                </motion.div>
              )}
            </AnimatePresence>
          </div>

          {/* Winner identifying details: Country flag, place, and masked mobile */}
          <AnimatePresence>
            {isWinnerRevealed && activeWinner && (
              <motion.div
                key="winner-identifying-details"
                initial={{ opacity: 0, y: 14 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0 }}
                transition={{ duration: 0.7, delay: 0.35, ease: [0.16, 1, 0.3, 1] }}
                style={{
                  marginTop: 18,
                  display: 'flex',
                  flexDirection: 'column',
                  alignItems: 'center',
                  gap: 10,
                }}
              >
                {/* Location row with country flag and place */}
                <div
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: 10,
                    padding: '6px 20px',
                    background: 'rgba(255, 255, 255, 0.05)',
                    border: '1px solid rgba(229, 169, 59, 0.3)',
                    borderRadius: 9999,
                    boxShadow: '0 4px 18px rgba(0, 0, 0, 0.25)',
                  }}
                >
                  <span
                    style={{
                      fontSize: 'clamp(18px, 2.2vw, 24px)',
                      lineHeight: 1,
                      display: 'inline-block',
                    }}
                    role="img"
                    aria-label={getCountryName(activeWinner.countryCode)}
                  >
                    {getCountryFlag(activeWinner.countryCode)}
                  </span>
                  <span
                    style={{
                      fontFamily: 'var(--font-display)',
                      fontSize: 'clamp(13px, 1.5vw, 16px)',
                      fontWeight: 600,
                      letterSpacing: '0.12em',
                      color: 'var(--text-on-dark)',
                      textTransform: 'uppercase',
                    }}
                  >
                    {formatParticipantLocation(activeWinner)}
                  </span>
                </div>

                {/* Partially masked mobile number */}
                {activeWinner.mobile && (
                  <div
                    style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      padding: '4px 16px',
                      background: 'rgba(229, 169, 59, 0.1)',
                      border: '1px solid rgba(229, 169, 59, 0.35)',
                      borderRadius: 6,
                      fontFamily: 'var(--font-body)',
                      fontSize: 'clamp(12px, 1.4vw, 15px)',
                      fontWeight: 600,
                      letterSpacing: '0.22em',
                      color: 'var(--amber-light)',
                      boxShadow: '0 2px 10px rgba(229, 169, 59, 0.12)',
                    }}
                    aria-label={`Masked contact: ${maskMobileNumber(activeWinner.mobile, activeWinner.countryCallingCode)}`}
                  >
                    <span style={{ userSelect: 'all' }}>
                      {maskMobileNumber(activeWinner.mobile, activeWinner.countryCallingCode)}
                    </span>
                  </div>
                )}
              </motion.div>
            )}
          </AnimatePresence>

          {/* Winner badge */}
          <AnimatePresence>
            {isWinnerRevealed && (
              <motion.div
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.6, duration: 0.5 }}
                style={{
                  marginTop: 18,
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: 6,
                  padding: '6px 18px',
                  background: 'rgba(200,90,50,0.2)',
                  border: '1px solid rgba(200,90,50,0.45)',
                  borderRadius: 4,
                  fontFamily: 'var(--font-display)',
                  fontSize: 9,
                  letterSpacing: '0.25em',
                  color: '#F0A880',
                  textTransform: 'uppercase',
                }}
                aria-label={isPublished ? 'Result Published' : 'Winner Selected'}
              >
                <span
                  style={{
                    width: 5,
                    height: 5,
                    borderRadius: '50%',
                    background: '#C85A32',
                    display: 'inline-block',
                  }}
                />
                {isPublished ? 'Result Published' : 'Winner Selected'}
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>

        {/* CTA area */}
        <AnimatePresence mode="wait">
          {status === 'ready' && (
            <motion.div
              key="ready-cta"
              initial={{ opacity: 0, y: 16 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -10 }}
              transition={{ duration: 0.5, delay: 0.3 }}
              style={{
                marginTop: 40,
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                gap: 20,
              }}
            >
              <p
                style={{
                  fontFamily: 'var(--font-body)',
                  fontSize: 13,
                  color: 'var(--text-on-dark-muted)',
                  letterSpacing: '0.06em',
                  textAlign: 'center',
                }}
              >
                {participants.length === 0
                  ? 'No participants with a 50/50 score found for this week.'
                  : 'Ready to reveal the winner?'}
              </p>
              <button
                className="btn-primary"
                onClick={startAnimation}
                disabled={participants.length === 0}
                id="btn-choose-winner"
                aria-label="Choose winner – start the lucky draw"
                style={{
                  fontSize: 13,
                  padding: '16px 52px',
                  letterSpacing: '0.22em',
                }}
              >
                {participants.length === 0 ? 'No Participants' : '✨ Choose Winner ✨'}
              </button>
            </motion.div>
          )}

          {status === 'selecting' && (
            <motion.div
              key="selecting-msg"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              transition={{ duration: 0.4 }}
              style={{
                marginTop: 40,
                fontFamily: 'var(--font-display)',
                fontSize: 11,
                letterSpacing: '0.35em',
                color: 'var(--amber)',
                textTransform: 'uppercase',
                display: 'flex',
                alignItems: 'center',
                gap: 10,
              }}
              aria-live="polite"
            >
              <span style={{ animation: 'badge-pulse 0.8s ease-in-out infinite' }}>●</span>
              Selecting…
              <span style={{ animation: 'badge-pulse 0.8s ease-in-out infinite 0.4s' }}>●</span>
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </div>
  );
}
