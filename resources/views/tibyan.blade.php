<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tibiyan — Coming Soon</title>
  <meta name="description" content="Tibiyan is a weekly Qur'an quiz by AP Aslam Holy Qur'an Award 2026. A new journey of Qur'anic learning is about to begin.">
  <meta name="theme-color" content="#55240F">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --terracotta: #AD4826;
      --chocolate: #55240F;
      --cream: #FAF6ED;
      --sand: #EECF9C;
      --burnt: #B95325;
      --ink: #17130F;
      --muted: #78634F;

      --radius-lg: 28px;
      --radius-md: 18px;
      --ease: cubic-bezier(.22, .8, .24, 1);
    }

    *, *::before, *::after { box-sizing: border-box; }
    html { -webkit-text-size-adjust: 100%; }

    body {
      margin: 0;
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      color: var(--ink);
      background: var(--cream);
      line-height: 1.6;
      overflow-x: hidden;
    }
    body.modal-open { overflow: hidden; }

    /* ---------- Background: geometric pattern + soft glow ---------- */
    .backdrop {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background:
        radial-gradient(60% 45% at 50% 0%, rgba(238, 207, 156, .55), transparent 70%),
        radial-gradient(45% 40% at 100% 100%, rgba(173, 72, 38, .12), transparent 70%),
        var(--cream);
    }
    .backdrop::before {
      content: "";
      position: absolute;
      inset: 0;
      opacity: .16;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='none' stroke='%23AD4826' stroke-width='1'%3E%3Crect x='20' y='20' width='40' height='40'/%3E%3Crect x='20' y='20' width='40' height='40' transform='rotate(45 40 40)'/%3E%3Ccircle cx='40' cy='40' r='6'/%3E%3Cpath d='M0 0L20 20M80 0L60 20M0 80L20 60M80 80L60 60'/%3E%3C/g%3E%3C/svg%3E");
      background-size: 80px 80px;
      -webkit-mask-image: radial-gradient(ellipse at center, #000 20%, transparent 78%);
              mask-image: radial-gradient(ellipse at center, #000 20%, transparent 78%);
      animation: drift 60s linear infinite;
    }
    @keyframes drift { to { background-position: 80px 80px; } }

    /* ---------- Layout ---------- */
    main {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      min-height: 100svh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: clamp(20px, 5vw, 56px) 16px 24px;
    }

    .card {
      width: 100%;
      max-width: 780px;
      text-align: center;
      padding: clamp(28px, 6vw, 60px) clamp(18px, 5vw, 56px);
      background: rgba(255, 252, 245, .78);
      -webkit-backdrop-filter: blur(8px);
              backdrop-filter: blur(8px);
      border: 1px solid rgba(173, 72, 38, .18);
      border-radius: var(--radius-lg);
      box-shadow: 0 30px 70px -30px rgba(85, 36, 15, .35), inset 0 0 0 6px rgba(238, 207, 156, .18);
      animation: rise .9s var(--ease) both;
    }
    @keyframes rise {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: none; }
    }

    /* ---------- Logo (3:1). Replace everything inside .logo to swap it. ---------- */
    .logo {
      width: min(330px, 78%);
      aspect-ratio: 3 / 1;
      margin: 0 auto 8px;
    }
    .logo svg, .logo img { display: block; width: 100%; height: 100%; }

    .eyebrow {
      margin: 14px 0 0;
      font-size: .78rem;
      font-weight: 600;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--burnt);
    }

    h1 {
      margin: 10px 0 0;
      font-family: "Cormorant Garamond", Georgia, serif;
      font-weight: 700;
      font-size: clamp(2.3rem, 7.5vw, 4.2rem);
      line-height: 1.05;
      color: var(--chocolate);
    }
    h1 .sep { color: var(--terracotta); margin: 0 .1em; }

    .tagline {
      margin: 14px auto 0;
      max-width: 34ch;
      font-size: clamp(1rem, 2.6vw, 1.2rem);
      color: var(--muted);
    }

    .divider {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      margin: clamp(22px, 4vw, 34px) auto;
      color: var(--terracotta);
    }
    .divider::before, .divider::after {
      content: "";
      height: 1px;
      width: min(120px, 22vw);
      background: linear-gradient(90deg, transparent, rgba(173, 72, 38, .6));
    }
    .divider::after { transform: scaleX(-1); }

    /* ---------- Countdown ---------- */
    .countdown {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: clamp(8px, 2vw, 18px);
      max-width: 560px;
      margin: 0 auto;
      padding: 0;
      list-style: none;
    }
    .tile {
      position: relative;
      padding: clamp(14px, 3.4vw, 26px) 4px clamp(10px, 2.4vw, 18px);
      border-radius: var(--radius-md);
      background: linear-gradient(160deg, var(--chocolate), #3d1909);
      color: var(--sand);
      box-shadow: 0 14px 26px -14px rgba(85, 36, 15, .7);
      overflow: hidden;
      transition: transform .35s var(--ease), box-shadow .35s var(--ease);
    }
    .tile::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(255,255,255,.08), transparent 45%);
      pointer-events: none;
    }
    .tile:hover { transform: translateY(-4px); box-shadow: 0 20px 30px -14px rgba(85, 36, 15, .75); }
    .num {
      display: block;
      font-family: "Cormorant Garamond", Georgia, serif;
      font-weight: 700;
      font-size: clamp(1.9rem, 8vw, 3.4rem);
      line-height: 1;
      font-variant-numeric: tabular-nums;
    }
    .num.tick { animation: tick .45s var(--ease); }
    @keyframes tick {
      from { opacity: .2; transform: translateY(-10px); }
      to   { opacity: 1;  transform: none; }
    }
    .unit {
      display: block;
      margin-top: 8px;
      font-size: clamp(.6rem, 2vw, .75rem);
      letter-spacing: .16em;
      text-transform: uppercase;
      color: rgba(238, 207, 156, .78);
    }

    .launch-note {
      margin: 18px 0 0;
      font-size: .92rem;
      color: var(--muted);
    }
    .launch-note strong { color: var(--chocolate); font-weight: 600; }

    /* ---------- Buttons ---------- */
    .btn {
      appearance: none;
      border: 0;
      cursor: pointer;
      font: inherit;
      font-weight: 600;
      letter-spacing: .02em;
      border-radius: 999px;
      padding: 15px 30px;
      min-height: 48px;
      transition: transform .25s var(--ease), box-shadow .25s var(--ease), background-color .25s, opacity .25s;
    }
    .btn:focus-visible { outline: 3px solid var(--burnt); outline-offset: 3px; }
    .btn:disabled { opacity: .45; cursor: not-allowed; }

    .btn-gold {
      margin-top: clamp(26px, 5vw, 38px);
      font-size: clamp(1rem, 2.8vw, 1.15rem);
      padding: 18px clamp(26px, 6vw, 44px);
      color: var(--chocolate);
      background: linear-gradient(180deg, #F4DBAE, var(--sand));
      box-shadow: 0 12px 26px -10px rgba(185, 83, 37, .65), inset 0 0 0 1.5px rgba(85, 36, 15, .18);
      position: relative;
      overflow: hidden;
    }
    .btn-gold::after {
      content: "";
      position: absolute;
      top: 0; left: -60%;
      width: 40%; height: 100%;
      background: linear-gradient(100deg, transparent, rgba(255,255,255,.55), transparent);
      transform: skewX(-20deg);
      transition: left .7s var(--ease);
    }
    .btn-gold:hover { transform: translateY(-3px); box-shadow: 0 18px 30px -10px rgba(185, 83, 37, .7), inset 0 0 0 1.5px rgba(85, 36, 15, .25); }
    .btn-gold:hover::after { left: 130%; }
    .btn-gold:active { transform: translateY(0) scale(.98); }

    .btn-primary { color: var(--cream); background: var(--terracotta); }
    .btn-primary:not(:disabled):hover { background: var(--burnt); transform: translateY(-2px); }
    .btn-ghost { color: var(--chocolate); background: transparent; box-shadow: inset 0 0 0 1.5px rgba(85, 36, 15, .35); }
    .btn-ghost:hover { background: rgba(238, 207, 156, .35); transform: translateY(-2px); }

    footer {
      position: relative;
      z-index: 1;
      margin-top: 26px;
      font-size: .8rem;
      color: var(--muted);
      text-align: center;
    }

    /* ---------- Modal ---------- */
    .overlay {
      position: fixed;
      inset: 0;
      z-index: 50;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 16px;
      background: rgba(23, 19, 15, .62);
      -webkit-backdrop-filter: blur(5px);
              backdrop-filter: blur(5px);
      opacity: 0;
      transition: opacity .3s var(--ease);
    }
    .overlay.show { display: flex; }
    .overlay.visible { opacity: 1; }

    .modal {
      position: relative;
      width: 100%;
      max-width: 560px;
      max-height: calc(100vh - 32px);
      max-height: calc(100dvh - 32px);
      overflow-y: auto;
      padding: clamp(22px, 5vw, 38px);
      background: var(--cream);
      border-radius: var(--radius-lg);
      border: 1px solid rgba(173, 72, 38, .25);
      box-shadow: 0 40px 80px -30px rgba(0, 0, 0, .6), inset 0 0 0 6px rgba(238, 207, 156, .22);
      transform: translateY(24px) scale(.97);
      transition: transform .4s var(--ease);
    }
    .overlay.visible .modal { transform: none; }

    .modal-close {
      position: absolute;
      top: 12px; right: 12px;
      width: 40px; height: 40px;
      padding: 0;
      display: grid; place-items: center;
      font-size: 1.4rem; line-height: 1;
      color: var(--chocolate);
      background: rgba(238, 207, 156, .4);
      border-radius: 50%;
    }
    .modal-close:hover { background: var(--sand); transform: rotate(90deg); }

    .q-label {
      margin: 0;
      font-size: .74rem;
      font-weight: 600;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--burnt);
    }
    .q-title {
      margin: 8px 40px 0 0;
      font-family: "Cormorant Garamond", Georgia, serif;
      font-size: clamp(1.45rem, 4.6vw, 1.9rem);
      line-height: 1.2;
      color: var(--chocolate);
    }

    fieldset { border: 0; margin: 18px 0 0; padding: 0; min-width: 0; }
    legend { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); }

    .options { display: grid; gap: 10px; }
    .option { position: relative; display: block; }
    .option input {
      position: absolute;
      opacity: 0;
      inset: 0;
      width: 100%; height: 100%;
      margin: 0;
      cursor: pointer;
    }
    .option .face {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      min-height: 54px;
      font-weight: 500;
      color: var(--ink);
      background: #fff;
      border: 1.5px solid rgba(120, 99, 79, .35);
      border-radius: 14px;
      transition: border-color .2s, background-color .2s, transform .2s var(--ease), box-shadow .2s;
    }
    .option .dot {
      flex: none;
      width: 20px; height: 20px;
      border-radius: 50%;
      border: 2px solid var(--muted);
      display: grid; place-items: center;
      font-size: .75rem; font-weight: 700; line-height: 1;
      color: transparent;
      transition: all .2s;
    }
    .option:hover .face { border-color: var(--terracotta); transform: translateX(3px); }
    .option input:checked + .face {
      border-color: var(--terracotta);
      background: rgba(238, 207, 156, .35);
      box-shadow: 0 0 0 3px rgba(173, 72, 38, .15);
    }
    .option input:checked + .face .dot { border-color: var(--terracotta); background: var(--terracotta); box-shadow: inset 0 0 0 3px #fff; }
    .option input:focus-visible + .face { outline: 3px solid var(--burnt); outline-offset: 2px; }
    .option .ar { font-family: "Amiri", serif; font-size: 1.15rem; color: var(--muted); margin-inline-start: auto; }

    /* locked / result states */
    .options.locked .option input { pointer-events: none; }
    .options.locked .option:hover .face { transform: none; border-color: rgba(120, 99, 79, .35); }
    .option.is-correct .face { border-color: var(--burnt); background: rgba(238, 207, 156, .6); }
    .option.is-correct .dot { background: var(--burnt); border-color: var(--burnt); color: #fff; box-shadow: none; }
    .option.is-wrong .face { border-style: dashed; border-color: var(--muted); background: rgba(120, 99, 79, .08); }
    .option.is-wrong .dot { border-color: var(--muted); color: var(--muted); background: transparent; box-shadow: none; }

    .modal-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
    .modal-actions .btn { flex: 1 1 auto; }

    .result {
      margin-top: 18px;
      padding: 18px;
      border-radius: var(--radius-md);
      background: linear-gradient(160deg, var(--chocolate), #3d1909);
      color: var(--cream);
      animation: rise .5s var(--ease) both;
    }
    .result[hidden] { display: none; }
    .result .headline {
      margin: 0;
      font-family: "Cormorant Garamond", Georgia, serif;
      font-weight: 700;
      font-size: clamp(1.4rem, 4.4vw, 1.75rem);
      color: var(--sand);
    }
    .result .detail { margin: 6px 0 0; color: rgba(250, 246, 237, .88); font-size: .95rem; }
    .result .detail strong { color: var(--sand); }
    .result .keep { margin: 12px 0 0; padding-top: 12px; border-top: 1px solid rgba(238, 207, 156, .3); font-weight: 600; }

    /* ---------- Optional stats panel (visible only with ?stats in the URL) ---------- */
    .stats {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 780px;
      margin-top: 22px;
      padding: 16px 18px;
      border-radius: var(--radius-md);
      background: rgba(255, 252, 245, .85);
      border: 1px dashed rgba(173, 72, 38, .45);
      font-size: .88rem;
    }
    .stats[hidden] { display: none; }
    .stats h2 { margin: 0 0 10px; font-size: .78rem; letter-spacing: .18em; text-transform: uppercase; color: var(--burnt); }
    .stats dl { margin: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px; }
    .stats dt { color: var(--muted); font-size: .78rem; }
    .stats dd { margin: 0; font-family: "Cormorant Garamond", serif; font-size: 1.7rem; font-weight: 700; color: var(--chocolate); }
    .stats p { margin: 10px 0 0; color: var(--muted); font-size: .78rem; }
    .stats .btn { padding: 8px 16px; min-height: 36px; font-size: .8rem; margin-top: 10px; }

    /* ---------- Small screens ---------- */
    @media (max-width: 380px) {
      .tile { border-radius: 14px; }
      .unit { letter-spacing: .08em; }
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { animation: none !important; transition: none !important; }
    }
  </style>
</head>
<body>
  <div class="backdrop" aria-hidden="true"></div>

  <main>
    <section class="card" aria-labelledby="page-title">

      <!-- ============================================================
           LOGO — sample only. To use the official logo, replace the whole
           <svg> below with:  <img src="your-logo.svg" alt="Tibiyan logo">
           Keep the 3:1 ratio (e.g. 600×200) and a transparent background.
           ============================================================ -->
      <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 200" role="img" aria-label="Tibiyan logo" width="600" height="200">
          <!-- Symbol: eight-pointed star over an open Qur'an -->
          <g transform="translate(0 12)">
            <g>
              <rect x="85" y="41" width="30" height="30" fill="#AD4826"/>
              <rect x="85" y="41" width="30" height="30" fill="#AD4826" transform="rotate(45 100 56)"/>
              <circle cx="100" cy="56" r="8" fill="#EECF9C"/>
              <circle cx="100" cy="56" r="3" fill="#55240F"/>
            </g>
            <g fill="none" stroke-linejoin="round" stroke-linecap="round">
              <path d="M100 132 C80 118 56 116 34 122 L34 88 C56 82 80 84 100 98 Z" fill="#EECF9C" stroke="#55240F" stroke-width="4"/>
              <path d="M100 132 C120 118 144 116 166 122 L166 88 C144 82 120 84 100 98 Z" fill="#EECF9C" stroke="#55240F" stroke-width="4"/>
              <path d="M100 98 V132" stroke="#55240F" stroke-width="4"/>
              <path d="M46 102 C60 99 74 100 88 106 M46 111 C60 108 74 109 88 115 M154 102 C140 99 126 100 112 106 M154 111 C140 108 126 109 112 115" stroke="#B95325" stroke-width="2.5"/>
            </g>
          </g>
          <!-- Wordmark -->
          <text x="392" y="108" text-anchor="middle" direction="rtl" font-family="Amiri, 'Noto Naskh Arabic', 'Traditional Arabic', serif" font-size="84" font-weight="700" fill="#55240F">&#1578;&#1576;&#1610;&#1575;&#1606;</text>
          <text x="392" y="152" text-anchor="middle" font-family="'Cormorant Garamond', Georgia, serif" font-size="28" font-weight="700" letter-spacing="11" fill="#AD4826">TIBIYAN</text>
        </svg>
      </div>

      <p class="eyebrow">Weekly Qur’an Quiz · AP Aslam Holy Qur’an Award 2026</p>
      <h1 id="page-title">Tibiyan <span class="sep">—</span> Coming Soon</h1>
      <p class="tagline">A new journey of Qur’anic learning is about to begin.</p>

      <div class="divider" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 22 22"><rect x="5" y="5" width="12" height="12" fill="currentColor"/><rect x="5" y="5" width="12" height="12" fill="currentColor" transform="rotate(45 11 11)"/><circle cx="11" cy="11" r="3" fill="#FAF6ED"/></svg>
      </div>

      <!-- Countdown -->
      <ul class="countdown" id="countdown" role="timer" aria-label="Time remaining until launch">
        <li class="tile"><span class="num" id="cd-days">00</span><span class="unit">Days</span></li>
        <li class="tile"><span class="num" id="cd-hours">00</span><span class="unit">Hours</span></li>
        <li class="tile"><span class="num" id="cd-minutes">00</span><span class="unit">Minutes</span></li>
        <li class="tile"><span class="num" id="cd-seconds">00</span><span class="unit">Seconds</span></li>
      </ul>
      <p class="launch-note">Launching <strong id="launch-label">Friday, 16 October 2026, 5:00 AM</strong> IST</p>

      <button type="button" class="btn btn-gold" id="demo-btn" aria-haspopup="dialog">Attend a Demo Quiz Now</button>
    </section>

    <!-- Optional stats (add ?stats to the URL to show) -->
    <aside class="stats" id="stats" hidden aria-label="Local statistics">
      <h2>Local statistics</h2>
      <dl>
        <div><dt>Demo button clicks</dt><dd id="st-buttonClicks">0</dd></div>
        <div><dt>Popup openings</dt><dd id="st-popupOpens">0</dd></div>
        <div><dt>Quiz attempts</dt><dd id="st-attempts">0</dd></div>
        <div><dt>Correct answers</dt><dd id="st-correct">0</dd></div>
        <div><dt>Incorrect answers</dt><dd id="st-incorrect">0</dd></div>
      </dl>
      <p>These counts are stored only in this browser (localStorage). They do not include other visitors or devices.</p>
      <button type="button" class="btn btn-ghost" id="stats-reset">Reset local counts</button>
    </aside>

    <footer>© 2026 AP Aslam Holy Qur’an Award</footer>
  </main>

  <!-- Demo quiz modal -->
  <div class="overlay" id="overlay">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="q-title" aria-describedby="q-label" id="modal">
      <button type="button" class="btn modal-close" id="modal-x" aria-label="Close demo quiz">&times;</button>

      <p class="q-label" id="q-label">Demo question</p>
      <h2 class="q-title" id="q-title">Which is the longest Surah in the Qur’an?</h2>

      <form id="quiz-form" novalidate>
        <fieldset>
          <legend>Choose one answer</legend>
          <div class="options" id="options">
            <label class="option"><input type="radio" name="answer" value="baqarah"><span class="face"><span class="dot" aria-hidden="true"></span>Al-Baqarah <span class="ar" lang="ar" dir="rtl">&#1575;&#1604;&#1576;&#1602;&#1585;&#1515;</span></span></label>
            <label class="option"><input type="radio" name="answer" value="imran"><span class="face"><span class="dot" aria-hidden="true"></span>Āl ʿImrān <span class="ar" lang="ar" dir="rtl">&#1570;&#1604; &#1593;&#1605;&#1585;&#1575;&#1606;</span></span></label>
            <label class="option"><input type="radio" name="answer" value="nisa"><span class="face"><span class="dot" aria-hidden="true"></span>An-Nisāʾ <span class="ar" lang="ar" dir="rtl">&#1575;&#1604;&#1606;&#1588;&#1575;&#1569;</span></span></label>
            <label class="option"><input type="radio" name="answer" value="maidah"><span class="face"><span class="dot" aria-hidden="true"></span>Al-Māʾidah <span class="ar" lang="ar" dir="rtl">&#1575;&#1604;&#1605;&#1575;&#1574;&#1583;&#1577;</span></span></label>
          </div>
        </fieldset>

        <div class="result" id="result" role="status" aria-live="polite" hidden>
          <p class="headline" id="result-headline"></p>
          <p class="detail" id="result-detail"></p>
          <p class="keep">Keep learning the Qur’an. Get ready for Tibiyan!</p>
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn btn-primary" id="submit-btn" disabled>Submit</button>
          <button type="button" class="btn btn-ghost" id="close-btn" hidden>Close &amp; Return to Page</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  (function () {
    "use strict";

    /* =========================================================
       CONFIG
       ========================================================= */
    // Friday, 16 October 2026, 05:00 AM in Asia/Kolkata (IST = UTC+05:30, no daylight saving).
    // 05:00 IST  ==  23:30 UTC on 15 October 2026.
    var LAUNCH_TIME = Date.UTC(2026, 9, 15, 23, 30, 0);
    var LAUNCH_TZ = "Asia/Kolkata";

    // Demo question — Surah Al-Baqarah (2) is the longest surah, with 286 verses.
    var CORRECT_VALUE = "baqarah";
    var CORRECT_TEXT = "Al-Baqarah (البقرة), the second Surah, with 286 verses";

    /* =========================================================
       LOCAL CLICK TRACKING (this browser only)
       ========================================================= */
    var STORAGE_KEY = "tibiyan_stats_v1";
    var FIELDS = ["buttonClicks", "popupOpens", "attempts", "correct", "incorrect"];
    var memory = null; // fallback if localStorage is blocked

    function emptyStats() {
      var o = {}; FIELDS.forEach(function (f) { o[f] = 0; }); return o;
    }
    function loadStats() {
      try {
        var raw = window.localStorage.getItem(STORAGE_KEY);
        if (raw) {
          var parsed = JSON.parse(raw), out = emptyStats();
          FIELDS.forEach(function (f) { out[f] = Number(parsed[f]) || 0; });
          return out;
        }
      } catch (e) { if (memory) return memory; }
      return memory || emptyStats();
    }
    function saveStats(s) {
      memory = s;
      try { window.localStorage.setItem(STORAGE_KEY, JSON.stringify(s)); } catch (e) { /* ignore */ }
    }
    function track(field) {
      var s = loadStats();
      s[field] += 1;
      saveStats(s);
      renderStats(s);
      if (window.console && console.table) {
        console.log("[Tibiyan] " + field + " +1 (local to this browser)");
        console.table(s);
      }
    }
    function renderStats(s) {
      s = s || loadStats();
      FIELDS.forEach(function (f) {
        var el = document.getElementById("st-" + f);
        if (el) el.textContent = s[f];
      });
    }
    // Handy from the console: tibiyanStats()
    window.tibiyanStats = function () {
      var s = loadStats(); console.table(s); return s;
    };

    var statsPanel = document.getElementById("stats");
    if (/(^|[?&])stats(=|&|$)/.test(window.location.search)) {
      statsPanel.hidden = false;
      renderStats();
    }
    document.getElementById("stats-reset").addEventListener("click", function () {
      saveStats(emptyStats()); renderStats();
    });

    /* =========================================================
       COUNTDOWN
       ========================================================= */
    var els = {
      d: document.getElementById("cd-days"),
      h: document.getElementById("cd-hours"),
      m: document.getElementById("cd-minutes"),
      s: document.getElementById("cd-seconds")
    };
    var last = { d: "", h: "", m: "", s: "" };
    var timerId = null;

    // Show the launch moment in IST, whatever the visitor's own timezone is.
    try {
      var label = new Intl.DateTimeFormat("en-IN", {
        timeZone: LAUNCH_TZ, weekday: "long", day: "numeric", month: "long",
        year: "numeric", hour: "numeric", minute: "2-digit", hour12: true
      }).format(new Date(LAUNCH_TIME));
      document.getElementById("launch-label").textContent = label.replace(" at ", ", ").replace(/\s?(am|pm)$/i, function (m, ap) { return " " + ap.toUpperCase(); });
    } catch (e) { /* keep the static fallback text */ }

    function pad(n) { return n < 10 ? "0" + n : String(n); }
    function setUnit(key, value) {
      if (last[key] === value) return;
      last[key] = value;
      var el = els[key];
      el.textContent = value;
      el.classList.remove("tick");
      void el.offsetWidth; // restart animation
      el.classList.add("tick");
    }

    function updateCountdown() {
      var diff = LAUNCH_TIME - Date.now();
      if (diff <= 0) {
        setUnit("d", "00"); setUnit("h", "00"); setUnit("m", "00"); setUnit("s", "00");
        document.querySelector(".launch-note").innerHTML = "<strong>Tibiyan is launching now.</strong> Stay tuned!";
        if (timerId) clearInterval(timerId);
        return;
      }
      var total = Math.floor(diff / 1000);
      setUnit("d", pad(Math.floor(total / 86400)));
      setUnit("h", pad(Math.floor((total % 86400) / 3600)));
      setUnit("m", pad(Math.floor((total % 3600) / 60)));
      setUnit("s", pad(total % 60));
    }
    updateCountdown();
    timerId = setInterval(updateCountdown, 1000);

    /* =========================================================
       DEMO QUIZ MODAL
       ========================================================= */
    var demoBtn = document.getElementById("demo-btn");
    var overlay = document.getElementById("overlay");
    var modal = document.getElementById("modal");
    var form = document.getElementById("quiz-form");
    var optionsBox = document.getElementById("options");
    var submitBtn = document.getElementById("submit-btn");
    var closeBtn = document.getElementById("close-btn");
    var xBtn = document.getElementById("modal-x");
    var result = document.getElementById("result");
    var headline = document.getElementById("result-headline");
    var detail = document.getElementById("result-detail");
    var radios = form.querySelectorAll('input[name="answer"]');

    var answered = false;
    var lastFocus = null;

    function resetQuiz() {
      answered = false;
      form.reset();
      optionsBox.classList.remove("locked");
      Array.prototype.forEach.call(optionsBox.querySelectorAll(".option"), function (o) {
        o.classList.remove("is-correct", "is-wrong");
        var dot = o.querySelector(".dot"); dot.textContent = "";
      });
      Array.prototype.forEach.call(radios, function (r) { r.disabled = false; });
      result.hidden = true;
      submitBtn.hidden = false;
      submitBtn.disabled = true;
      closeBtn.hidden = true;
    }

    function openModal() {
      lastFocus = document.activeElement;
      resetQuiz();
      overlay.classList.add("show");
      document.body.classList.add("modal-open");
      requestAnimationFrame(function () {
        overlay.classList.add("visible");
        var first = radios[0]; if (first) first.focus();
      });
      track("popupOpens");
    }

    function closeModal() {
      overlay.classList.remove("visible");
      document.body.classList.remove("modal-open");
      setTimeout(function () { overlay.classList.remove("show"); }, 300);
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    demoBtn.addEventListener("click", function () {
      track("buttonClicks");
      openModal();
    });

    Array.prototype.forEach.call(radios, function (r) {
      r.addEventListener("change", function () { if (!answered) submitBtn.disabled = false; });
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (answered) return; // one attempt per opening — blocks double submissions
      var chosen = form.querySelector('input[name="answer"]:checked');
      if (!chosen) return;
      answered = true;

      var isCorrect = chosen.value === CORRECT_VALUE;
      track("attempts");
      track(isCorrect ? "correct" : "incorrect");

      // Lock options and mark them (icons as well as colour)
      optionsBox.classList.add("locked");
      Array.prototype.forEach.call(radios, function (r) {
        r.disabled = true;
        var wrap = r.closest(".option"), dot = wrap.querySelector(".dot");
        if (r.value === CORRECT_VALUE) { wrap.classList.add("is-correct"); dot.textContent = "✓"; }
        else if (r === chosen) { wrap.classList.add("is-wrong"); dot.textContent = "✕"; }
      });

      if (isCorrect) {
        headline.textContent = "Correct answer! Well done 🎉";
        detail.innerHTML = "Surah Al-Baqarah is indeed the longest Surah, with <strong>286 verses</strong>.";
      } else {
        headline.textContent = "Not quite — and that’s perfectly okay.";
        detail.innerHTML = "Every attempt is a step in learning. The correct answer is <strong>" + CORRECT_TEXT + "</strong>.";
      }
      result.hidden = false;
      submitBtn.hidden = true;
      closeBtn.hidden = false;
      closeBtn.focus();
    });

    closeBtn.addEventListener("click", closeModal);
    xBtn.addEventListener("click", closeModal);
    overlay.addEventListener("mousedown", function (e) { if (e.target === overlay) closeModal(); });

    // Escape to close + keep keyboard focus inside the dialog
    document.addEventListener("keydown", function (e) {
      if (!overlay.classList.contains("show")) return;
      if (e.key === "Escape") { e.preventDefault(); closeModal(); return; }
      if (e.key !== "Tab") return;
      var focusable = modal.querySelectorAll('button:not([disabled]):not([hidden]), input:not([disabled])');
      var list = Array.prototype.filter.call(focusable, function (n) { return n.offsetParent !== null || n.type === "radio"; });
      if (!list.length) return;
      var first = list[0], lastEl = list[list.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); lastEl.focus(); }
      else if (!e.shiftKey && document.activeElement === lastEl) { e.preventDefault(); first.focus(); }
    });
  })();
  </script>
</body>
</html>
