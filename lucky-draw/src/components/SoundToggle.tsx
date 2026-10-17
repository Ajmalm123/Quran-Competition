import { Volume2, VolumeX } from 'lucide-react';

interface SoundToggleProps {
  soundEnabled: boolean;
  onToggle: () => void;
}

export default function SoundToggle({ soundEnabled, onToggle }: SoundToggleProps) {
  return (
    <button
      className="btn-icon"
      onClick={onToggle}
      aria-label={soundEnabled ? 'Mute sound' : 'Enable sound'}
      title={soundEnabled ? 'Mute sound' : 'Enable sound'}
      id="btn-sound-toggle"
    >
      {soundEnabled ? <Volume2 size={16} /> : <VolumeX size={16} />}
    </button>
  );
}
