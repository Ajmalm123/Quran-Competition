import { motion } from 'motion/react';
import { Trophy, Share2, RotateCcw } from 'lucide-react';
import type { Participant, DrawStatus } from '../types';
import { getCountryFlag, formatParticipantLocation, maskMobileNumber } from '../types';

interface WinnerRevealProps {
  winner: Participant;
  status: DrawStatus;
  onPublish: () => void;
  onReset: () => void;
}

export default function WinnerReveal({ winner, status, onPublish, onReset }: WinnerRevealProps) {
  const isPublished = status === 'published';
  const isConfirming = status === 'confirm-publish' || status === 'publishing';

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
      style={{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 20,
        padding: '16px 28px',
        background: 'linear-gradient(0deg, #3D1F14 0%, #4A2519 100%)',
        borderTop: '1px solid rgba(229,169,59,0.22)',
        flexShrink: 0,
        flexWrap: 'wrap',
      }}
    >
      {/* Winner info chip – Thibyan terracotta style with identifying details */}
      <div
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: 12,
          padding: '8px 18px',
          background: 'linear-gradient(135deg, rgba(200,90,50,0.25) 0%, rgba(170,65,30,0.15) 100%)',
          border: '1px solid rgba(200,90,50,0.4)',
          borderRadius: 6,
        }}
      >
        <div
          style={{
            width: 34,
            height: 34,
            borderRadius: '50%',
            background: 'linear-gradient(135deg, rgba(229,169,59,0.3), rgba(200,90,50,0.2))',
            border: '1px solid rgba(229,169,59,0.5)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            flexShrink: 0,
          }}
        >
          <Trophy size={16} style={{ color: 'var(--amber-light)' }} />
        </div>
        <div>
          <div
            style={{
              fontSize: 9,
              fontFamily: 'var(--font-display)',
              letterSpacing: '0.2em',
              color: 'var(--text-on-dark-muted)',
              textTransform: 'uppercase',
              marginBottom: 1,
            }}
          >
            Winner
          </div>
          <div
            style={{
              fontSize: 15,
              fontWeight: 600,
              color: 'var(--amber-light)',
              fontFamily: 'var(--font-body)',
              lineHeight: 1.2,
            }}
          >
            {winner.name}
          </div>
          <div
            style={{
              fontSize: 11,
              color: 'var(--text-on-dark-muted)',
              display: 'flex',
              alignItems: 'center',
              gap: 6,
              marginTop: 2,
            }}
          >
            <span role="img" aria-label="Country flag">{getCountryFlag(winner.countryCode)}</span>
            <span>{formatParticipantLocation(winner)}</span>
            {winner.mobile && (
              <>
                <span style={{ opacity: 0.4 }}>·</span>
                <span style={{ color: 'var(--amber-light)', letterSpacing: '0.08em' }}>
                  {maskMobileNumber(winner.mobile, winner.countryCallingCode)}
                </span>
              </>
            )}
          </div>
        </div>
      </div>

      {/* Action buttons */}
      <div style={{ display: 'flex', gap: 12, flexWrap: 'wrap', alignItems: 'center' }}>
        {!isPublished && (
          <button
            className="btn-primary"
            onClick={onPublish}
            disabled={isConfirming}
            id="btn-publish-result"
            aria-label="Publish the winner result"
            style={{ padding: '13px 32px' }}
          >
            <Share2 size={15} />
            Publish Result
          </button>
        )}

        {isPublished && (
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 8,
              padding: '10px 20px',
              background: 'rgba(95,206,168,0.1)',
              border: '1px solid rgba(95,206,168,0.3)',
              borderRadius: 6,
              fontFamily: 'var(--font-display)',
              fontSize: 11,
              letterSpacing: '0.16em',
              color: '#5FCEA8',
              textTransform: 'uppercase',
            }}
            aria-live="polite"
          >
            <span
              style={{
                width: 7,
                height: 7,
                borderRadius: '50%',
                background: '#5FCEA8',
                display: 'inline-block',
              }}
            />
            Result Published Successfully
          </div>
        )}

        <button
          className="btn-secondary"
          onClick={onReset}
          id="btn-back-overview"
          aria-label="Reset and go back to overview"
        >
          <RotateCcw size={14} />
          {isPublished ? 'Reset Draw' : 'Back to Overview'}
        </button>
      </div>
    </motion.div>
  );
}
