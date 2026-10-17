import { useEffect, useRef } from 'react';

interface Particle {
  x: number;
  y: number;
  vx: number;
  vy: number;
  size: number;
  opacity: number;
  life: number;
  maxLife: number;
  color: string;
}

const COLORS = [
  'rgba(229, 169, 59, 0.85)',
  'rgba(240, 190, 92, 0.7)',
  'rgba(200, 90, 50, 0.65)',
  'rgba(229, 169, 59, 0.5)',
  'rgba(245, 237, 224, 0.35)',
];

export default function CelebrationEffects({ active }: { active: boolean }) {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const particlesRef = useRef<Particle[]>([]);
  const rafRef = useRef<number>(0);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const resize = () => {
      canvas.width = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;
    };
    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(canvas);

    const spawnParticle = () => {
      const x = canvas.width * (0.3 + Math.random() * 0.4);
      const y = canvas.height * (0.5 + Math.random() * 0.2);
      particlesRef.current.push({
        x,
        y,
        vx: (Math.random() - 0.5) * 1.2,
        vy: -1 - Math.random() * 2,
        size: 1.5 + Math.random() * 3,
        opacity: 0,
        life: 0,
        maxLife: 80 + Math.random() * 60,
        color: COLORS[Math.floor(Math.random() * COLORS.length)],
      });
    };

    let frame = 0;
    const tick = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      if (active && frame % 3 === 0) {
        for (let i = 0; i < 2; i++) spawnParticle();
      }

      particlesRef.current = particlesRef.current.filter(p => p.life < p.maxLife);

      for (const p of particlesRef.current) {
        p.life++;
        p.x += p.vx;
        p.y += p.vy;
        p.vy += 0.015; // gentle gravity
        p.opacity = p.life < 10 ? p.life / 10 : Math.max(0, 1 - (p.life - 10) / (p.maxLife - 10));

        ctx.save();
        ctx.globalAlpha = p.opacity * 0.7;
        ctx.fillStyle = p.color;
        ctx.shadowColor = p.color;
        ctx.shadowBlur = 6;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
      }

      frame++;
      rafRef.current = requestAnimationFrame(tick);
    };

    rafRef.current = requestAnimationFrame(tick);
    return () => {
      cancelAnimationFrame(rafRef.current);
      ro.disconnect();
    };
  }, [active]);

  return (
    <canvas
      ref={canvasRef}
      className="particles-overlay"
      aria-hidden="true"
      style={{ width: '100%', height: '100%' }}
    />
  );
}
