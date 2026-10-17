import { useState } from 'react';
import { X, Search, User } from 'lucide-react';
import type { Participant } from '../types';
import { getCountryFlag, getCountryName, formatParticipantLocation, maskMobileNumber } from '../types';

interface ParticipantDrawerProps {
  participants: Participant[];
  winner: Participant | null;
  isOpen: boolean;
  onClose: () => void;
}

export default function ParticipantDrawer({
  participants,
  winner,
  isOpen,
  onClose,
}: ParticipantDrawerProps) {
  const [query, setQuery] = useState('');

  if (!isOpen) return null;

  const filtered = participants.filter(p =>
    p.name.toLowerCase().includes(query.toLowerCase()) ||
    (p.place && p.place.toLowerCase().includes(query.toLowerCase())) ||
    (p.countryCode && getCountryName(p.countryCode).toLowerCase().includes(query.toLowerCase()))
  );

  return (
    <>
      <div className="drawer-backdrop" onClick={onClose} aria-hidden="true" />
      <aside
        className="drawer"
        role="dialog"
        aria-label="Participant list"
        aria-modal="true"
      >
        {/* Header */}
        <div
          style={{
            padding: '20px 24px 16px',
            borderBottom: '1px solid rgba(229,169,59,0.2)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            flexShrink: 0,
            background: 'linear-gradient(180deg, #4A2519 0%, #3D1F14 100%)',
          }}
        >
          <div>
            <div
              style={{
                fontFamily: 'var(--font-display)',
                fontSize: 11,
                letterSpacing: '0.2em',
                color: 'var(--amber-light)',
                textTransform: 'uppercase',
                marginBottom: 3,
              }}
            >
              Participants
            </div>
            <div style={{ fontSize: 11, color: 'var(--text-on-dark-muted)', letterSpacing: '0.06em' }}>
              {participants.length} eligible
            </div>
          </div>
          <button
            className="btn-icon"
            onClick={onClose}
            aria-label="Close participant panel"
            id="btn-close-participants"
          >
            <X size={15} />
          </button>
        </div>

        {/* Search */}
        <div style={{ padding: '14px 20px', flexShrink: 0, borderBottom: '1px solid rgba(255,255,255,0.06)' }}>
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 10,
              padding: '9px 13px',
              background: 'rgba(255,255,255,0.05)',
              border: '1px solid rgba(255,255,255,0.1)',
              borderRadius: 5,
            }}
          >
            <Search size={13} style={{ color: 'var(--text-on-dark-muted)', flexShrink: 0 }} />
            <input
              type="text"
              placeholder="Search participants…"
              value={query}
              onChange={e => setQuery(e.target.value)}
              id="input-search-participants"
              aria-label="Search participants"
              style={{
                flex: 1,
                background: 'none',
                border: 'none',
                outline: 'none',
                color: 'var(--text-on-dark)',
                fontSize: 13,
                fontFamily: 'var(--font-body)',
              }}
            />
            {query && (
              <button
                onClick={() => setQuery('')}
                aria-label="Clear search"
                style={{
                  background: 'none',
                  border: 'none',
                  cursor: 'pointer',
                  color: 'var(--text-on-dark-muted)',
                  padding: 0,
                  display: 'flex',
                }}
              >
                <X size={12} />
              </button>
            )}
          </div>
        </div>

        {/* Results count */}
        <div
          style={{
            padding: '8px 24px 6px',
            fontSize: 11,
            color: 'rgba(255,245,230,0.3)',
            letterSpacing: '0.06em',
            flexShrink: 0,
          }}
        >
          {filtered.length === participants.length
            ? `All ${participants.length} participants`
            : `${filtered.length} of ${participants.length} shown`}
        </div>

        {/* List */}
        <div style={{ overflowY: 'auto', flex: 1, padding: '0 16px 20px' }}>
          {filtered.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '40px 0', color: 'var(--text-on-dark-muted)', fontSize: 13 }}>
              No participants found
            </div>
          ) : (
            <ul style={{ listStyle: 'none' }} role="list">
              {filtered.map((p, i) => {
                const isWinner = winner?.id === p.id;
                return (
                  <li
                    key={p.id}
                    style={{
                      display: 'flex',
                      alignItems: 'center',
                      gap: 12,
                      padding: '9px 11px',
                      marginBottom: 3,
                      borderRadius: 5,
                      background: isWinner
                        ? 'rgba(200,90,50,0.18)'
                        : i % 2 === 0
                        ? 'rgba(255,255,255,0.03)'
                        : 'transparent',
                      border: isWinner ? '1px solid rgba(200,90,50,0.35)' : '1px solid transparent',
                      transition: 'background 0.2s',
                    }}
                  >
                    <div
                      style={{
                        width: 28,
                        height: 28,
                        borderRadius: '50%',
                        background: isWinner
                          ? 'linear-gradient(135deg, rgba(229,169,59,0.3), rgba(200,90,50,0.2))'
                          : 'rgba(255,255,255,0.06)',
                        border: `1px solid ${isWinner ? 'rgba(229,169,59,0.45)' : 'rgba(255,255,255,0.1)'}`,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        flexShrink: 0,
                      }}
                    >
                      <User size={12} style={{ color: isWinner ? 'var(--amber-light)' : 'rgba(255,245,230,0.3)' }} />
                    </div>
                    <div style={{ flex: 1, minWidth: 0 }}>
                      <div
                        style={{
                          fontSize: 13,
                          fontWeight: isWinner ? 600 : 400,
                          color: isWinner ? 'var(--amber-light)' : 'var(--text-on-dark)',
                          whiteSpace: 'nowrap',
                          overflow: 'hidden',
                          textOverflow: 'ellipsis',
                        }}
                      >
                        {p.name}
                      </div>
                      <div
                        style={{
                          fontSize: 11,
                          color: 'var(--text-on-dark-muted)',
                          display: 'flex',
                          alignItems: 'center',
                          gap: 5,
                          marginTop: 2,
                        }}
                      >
                        <span role="img" aria-label="Flag">{getCountryFlag(p.countryCode)}</span>
                        <span style={{ whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                          {formatParticipantLocation(p)}
                        </span>
                        {p.mobile && (
                          <>
                            <span style={{ opacity: 0.35 }}>·</span>
                            <span style={{ color: isWinner ? 'var(--amber-light)' : 'rgba(255,245,230,0.5)', fontFamily: 'monospace' }}>
                              {maskMobileNumber(p.mobile, p.countryCallingCode)}
                            </span>
                          </>
                        )}
                      </div>
                    </div>
                    {isWinner && (
                      <span
                        style={{
                          fontSize: 8,
                          fontFamily: 'var(--font-display)',
                          letterSpacing: '0.15em',
                          color: 'var(--amber)',
                          textTransform: 'uppercase',
                          flexShrink: 0,
                        }}
                      >
                        Winner
                      </span>
                    )}
                  </li>
                );
              })}
            </ul>
          )}
        </div>
      </aside>
    </>
  );
}
