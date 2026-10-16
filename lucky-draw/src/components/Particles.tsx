import { useMemo } from "react";

interface Props {
  celebrate?: boolean;
}

interface Spec {
  left: number;
  size: number;
  duration: number;
  delay: number;
  dx: number;
  opacity: number;
}

function makeSpecs(count: number, speed: number, minSize: number, maxSize: number): Spec[] {
  return Array.from({ length: count }, () => {
    const duration = (14 + Math.random() * 16) / speed;
    return {
      left: Math.random() * 100,
      size: minSize + Math.random() * (maxSize - minSize),
      duration,
      delay: -Math.random() * duration,
      dx: (Math.random() - 0.5) * 140,
      opacity: 0.35 + Math.random() * 0.5,
    };
  });
}

/** Subtle floating gold dust. A second, brighter layer fades in while celebrating. */
export function Particles({ celebrate = false }: Props) {
  const calm = useMemo(() => makeSpecs(36, 1, 2, 6), []);
  const festive = useMemo(() => makeSpecs(28, 1.7, 3, 9), []);

  return (
    <div aria-hidden className="pointer-events-none absolute inset-0 overflow-hidden">
      {calm.map((p, i) => (
        <span
          key={`c${i}`}
          className="particle"
          style={
            {
              left: `${p.left}%`,
              width: p.size,
              height: p.size,
              animationDuration: `${p.duration}s`,
              animationDelay: `${p.delay}s`,
              "--p-dx": `${p.dx}px`,
              "--p-opacity": p.opacity,
            } as React.CSSProperties
          }
        />
      ))}
      <div
        className="absolute inset-0 transition-opacity duration-1000"
        style={{ opacity: celebrate ? 1 : 0 }}
      >
        {festive.map((p, i) => (
          <span
            key={`f${i}`}
            className="particle"
            style={
              {
                left: `${p.left}%`,
                width: p.size,
                height: p.size,
                animationDuration: `${p.duration}s`,
                animationDelay: `${p.delay}s`,
                "--p-dx": `${p.dx}px`,
                "--p-opacity": Math.min(1, p.opacity + 0.2),
              } as React.CSSProperties
            }
          />
        ))}
      </div>
    </div>
  );
}
