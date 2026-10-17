import { motion, AnimatePresence } from 'motion/react';
import { AlertTriangle, Loader2 } from 'lucide-react';
import type { Participant } from '../types';
import { getCountryFlag, formatParticipantLocation, maskMobileNumber } from '../types';

interface PublishConfirmationModalProps {
  isOpen: boolean;
  winner: Participant;
  isPublishing?: boolean;
  onConfirm: () => void;
  onCancel: () => void;
}

export default function PublishConfirmationModal({
  isOpen,
  winner,
  isPublishing = false,
  onConfirm,
  onCancel,
}: PublishConfirmationModalProps) {
  return (
    <AnimatePresence>
      {isOpen && (
        <motion.div
          className="modal-backdrop"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.25 }}
          onClick={isPublishing ? undefined : onCancel}
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-title"
          id="publish-modal"
        >
          <motion.div
            initial={{ scale: 0.92, opacity: 0, y: 16 }}
            animate={{ scale: 1, opacity: 1, y: 0 }}
            exit={{ scale: 0.95, opacity: 0, y: 8 }}
            transition={{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }}
            onClick={e => e.stopPropagation()}
            style={{
              width: '100%',
              maxWidth: 480,
              margin: '0 24px',
              background: '#3D1F14',
              border: '1px solid rgba(229,169,59,0.35)',
              borderRadius: 8,
              overflow: 'hidden',
              boxShadow: '0 40px 100px rgba(20,5,0,0.75), 0 0 0 1px rgba(229,169,59,0.12)',
            }}
          >
            {/* Top accent line – Thibyan amber */}
            <div
              style={{
                height: 3,
                background: 'linear-gradient(90deg, transparent 0%, var(--amber) 50%, transparent 100%)',
              }}
            />

            <div style={{ padding: '36px 40px' }}>
              {/* Icon */}
              <div
                style={{
                  width: 54,
                  height: 54,
                  borderRadius: '50%',
                  background: 'rgba(229,169,59,0.12)',
                  border: '1px solid rgba(229,169,59,0.35)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  marginBottom: 24,
                }}
              >
                <AlertTriangle size={22} style={{ color: 'var(--amber-light)' }} />
              </div>

              {/* Title */}
              <h2
                id="modal-title"
                style={{
                  fontFamily: 'var(--font-display)',
                  fontSize: 19,
                  fontWeight: 700,
                  letterSpacing: '0.12em',
                  color: 'var(--text-on-dark)',
                  marginBottom: 12,
                  textTransform: 'uppercase',
                }}
              >
                Publish Winner?
              </h2>

              {/* Message */}
              <p
                style={{
                  fontSize: 13.5,
                  color: 'var(--text-on-dark-muted)',
                  lineHeight: 1.65,
                  marginBottom: 28,
                }}
              >
                Are you sure you want to publish the selected winner? This action will
                save the winner and update the live public website.
              </p>

              {/* Winner display – Thibyan terracotta card style */}
              <div
                style={{
                  padding: '20px 24px',
                  background: 'linear-gradient(135deg, rgba(200,90,50,0.2) 0%, rgba(170,65,30,0.12) 100%)',
                  border: '1px solid rgba(200,90,50,0.35)',
                  borderRadius: 6,
                  marginBottom: 32,
                  textAlign: 'center',
                }}
              >
                <div
                  style={{
                    fontSize: 10,
                    fontFamily: 'var(--font-display)',
                    letterSpacing: '0.25em',
                    color: 'rgba(229,169,59,0.65)',
                    textTransform: 'uppercase',
                    marginBottom: 10,
                  }}
                >
                  Selected Winner
                </div>
                <div
                  style={{
                    fontFamily: 'var(--font-serif)',
                    fontSize: 26,
                    fontWeight: 700,
                    color: 'var(--amber-light)',
                    letterSpacing: '0.02em',
                  }}
                >
                  {winner.name}
                </div>
                <div
                  style={{
                    marginTop: 8,
                    fontSize: 12.5,
                    color: 'var(--text-on-dark-muted)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: 8,
                    flexWrap: 'wrap',
                  }}
                >
                  <span>{getCountryFlag(winner.countryCode)}</span>
                  <span>{formatParticipantLocation(winner)}</span>
                  {winner.mobile && (
                    <>
                      <span style={{ opacity: 0.4 }}>·</span>
                      <span style={{ color: 'var(--amber-light)', letterSpacing: '0.08em', fontWeight: 600 }}>
                        {maskMobileNumber(winner.mobile, winner.countryCallingCode)}
                      </span>
                    </>
                  )}
                </div>
              </div>

              {/* Actions */}
              <div style={{ display: 'flex', gap: 12, justifyContent: 'flex-end' }}>
                <button
                  className="btn-secondary"
                  onClick={onCancel}
                  disabled={isPublishing}
                  id="btn-modal-cancel"
                  aria-label="Cancel and close modal"
                >
                  Cancel
                </button>
                <button
                  className="btn-primary"
                  onClick={onConfirm}
                  disabled={isPublishing}
                  id="btn-modal-confirm"
                  aria-label="Confirm and publish result"
                  style={{ padding: '13px 26px' }}
                >
                  {isPublishing ? (
                    <>
                      <Loader2 size={15} className="animate-spin" />
                      Publishing…
                    </>
                  ) : (
                    'Confirm & Publish'
                  )}
                </button>
              </div>
            </div>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
