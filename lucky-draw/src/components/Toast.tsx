import { useEffect, useState } from 'react';
import { CheckCircle } from 'lucide-react';

interface ToastProps {
  message: string;
  visible: boolean;
  onDismiss: () => void;
}

export default function Toast({ message, visible, onDismiss }: ToastProps) {
  const [animClass, setAnimClass] = useState('');

  useEffect(() => {
    if (visible) {
      setAnimClass('toast--enter');
      const t = setTimeout(() => {
        setAnimClass('toast--exit');
        setTimeout(onDismiss, 300);
      }, 3500);
      return () => clearTimeout(t);
    }
  }, [visible, onDismiss]);

  if (!visible && animClass === '') return null;

  return (
    <div
      className={`toast toast--success ${animClass}`}
      role="status"
      aria-live="polite"
    >
      <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
        <CheckCircle size={20} style={{ flexShrink: 0, color: '#5FCEA8' }} />
        <div>
          <div style={{
            fontFamily: 'var(--font-display)',
            fontSize: 11,
            letterSpacing: '0.15em',
            textTransform: 'uppercase',
            marginBottom: 4,
            color: '#5FCEA8',
          }}>
            Success
          </div>
          <div style={{ fontSize: 13, color: 'var(--text-on-dark)', lineHeight: 1.5 }}>
            {message}
          </div>
        </div>
      </div>
    </div>
  );
}
