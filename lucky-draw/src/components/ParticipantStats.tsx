import { useState, useRef, useEffect } from 'react';
import { Users, ChevronDown, Check, Calendar } from 'lucide-react';
import type { WeekInfo } from '../types';

interface ParticipantStatsProps {
  weeks: WeekInfo[];
  selectedWeekId: number;
  selectedWeekNumber: number;
  onSelectWeek: (weekId: number) => void;
  onViewParticipants: () => void;
}

export default function ParticipantStats({
  weeks,
  selectedWeekId,
  selectedWeekNumber,
  onSelectWeek,
  onViewParticipants,
}: ParticipantStatsProps) {
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsOpen(false);
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  return (
    <div
      style={{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        padding: '12px 28px',
        background: 'linear-gradient(180deg, #4A2519 0%, #3D1F14 100%)',
        borderBottom: '1px solid rgba(229,169,59,0.18)',
        flexShrink: 0,
        gap: 16,
      }}
    >
      {/* Week Selector Control */}
      <div
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: 10,
        }}
      >
        <span
          style={{
            fontFamily: 'var(--font-display)',
            fontSize: 11,
            letterSpacing: '0.18em',
            color: 'var(--text-on-dark-muted)',
            textTransform: 'uppercase',
            display: 'flex',
            alignItems: 'center',
            gap: 6,
          }}
        >
          <Calendar size={13} style={{ color: 'var(--amber)' }} />
          Select Week:
        </span>

        <div
          ref={dropdownRef}
          style={{
            position: 'relative',
          }}
        >
          <button
            type="button"
            onClick={() => setIsOpen(prev => !prev)}
            id="btn-week-selector"
            aria-haspopup="listbox"
            aria-expanded={isOpen}
            aria-label={`Select week. Currently Week ${selectedWeekNumber}`}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              gap: 12,
              padding: '7px 14px',
              background: 'rgba(255, 255, 255, 0.07)',
              border: isOpen
                ? '1px solid var(--amber)'
                : '1px solid rgba(229, 169, 59, 0.4)',
              borderRadius: 5,
              color: 'var(--amber-light)',
              fontFamily: 'var(--font-display)',
              fontSize: 12,
              fontWeight: 600,
              letterSpacing: '0.12em',
              cursor: 'pointer',
              transition: 'all 0.25s ease',
              boxShadow: isOpen ? '0 0 12px rgba(229,169,59,0.2)' : 'none',
            }}
          >
            <span>Week {selectedWeekNumber}</span>
            <ChevronDown
              size={14}
              style={{
                color: 'var(--amber)',
                transform: isOpen ? 'rotate(180deg)' : 'none',
                transition: 'transform 0.2s ease',
              }}
            />
          </button>

          {isOpen && (
            <div
              role="listbox"
              aria-label="Available weeks"
              style={{
                position: 'absolute',
                top: 'calc(100% + 6px)',
                left: 0,
                zIndex: 50,
                minWidth: 150,
                maxHeight: 280,
                overflowY: 'auto',
                background: '#34190F',
                border: '1px solid rgba(229, 169, 59, 0.4)',
                borderRadius: 6,
                boxShadow: '0 8px 24px rgba(0,0,0,0.6), 0 0 16px rgba(229,169,59,0.15)',
                padding: '4px',
                display: 'flex',
                flexDirection: 'column',
                gap: 2,
              }}
            >
              {weeks.map(w => {
                const isSelected = w.id === selectedWeekId;
                return (
                  <button
                    key={w.id}
                    type="button"
                    role="option"
                    aria-selected={isSelected}
                    onClick={() => {
                      onSelectWeek(w.id);
                      setIsOpen(false);
                    }}
                    style={{
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'space-between',
                      padding: '8px 12px',
                      background: isSelected ? 'rgba(229, 169, 59, 0.2)' : 'transparent',
                      border: 'none',
                      borderRadius: 4,
                      color: isSelected ? 'var(--amber-light)' : 'var(--text-on-dark)',
                      fontFamily: 'var(--font-display)',
                      fontSize: 11.5,
                      fontWeight: isSelected ? 700 : 500,
                      letterSpacing: '0.12em',
                      cursor: 'pointer',
                      textAlign: 'left',
                      transition: 'background 0.15s ease, color 0.15s ease',
                    }}
                    onMouseEnter={e => {
                      if (!isSelected) {
                        e.currentTarget.style.background = 'rgba(229, 169, 59, 0.1)';
                        e.currentTarget.style.color = 'var(--amber-light)';
                      }
                    }}
                    onMouseLeave={e => {
                      if (!isSelected) {
                        e.currentTarget.style.background = 'transparent';
                        e.currentTarget.style.color = 'var(--text-on-dark)';
                      }
                    }}
                  >
                    <span>Week {w.week_number}</span>
                    {isSelected && <Check size={13} style={{ color: 'var(--amber)' }} />}
                  </button>
                );
              })}
            </div>
          )}
        </div>
      </div>

      {/* View participants button */}
      <button
        className="btn-ghost"
        onClick={onViewParticipants}
        id="btn-view-participants"
        aria-label="View participant list"
        style={{ whiteSpace: 'nowrap' }}
      >
        <Users size={13} />
        View Participants
      </button>
    </div>
  );
}
