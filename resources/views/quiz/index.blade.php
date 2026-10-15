<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ $csrfToken }}">
<title>തിബ്‌യാൻ</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600..800&family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&family=DM+Serif+Display&family=Figtree:wght@400;500;600;700&family=Noto+Sans+Malayalam:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --bg-header: #F6EEE1;
  --bg-hero: #854D3A;
  --bg-tan: #F7F0E3;
  --bg-schedule: #F3DFC3;
  --bg-leaderboard: #4B332B;
  --bg-card: #FBF7EE;
  --bg-card-light: #FEF8EF;
  --border-tan: #D9C8AC;
  --border-card: #BDA585;
  --ink-dark: #17130F;
  --ink-brown: #593320;
  --ink-mute: #6F5C44;
  --ink-sub: #474747;
  --gold-btn: #F6AC38;
  --gold-btn-text: #593320;
  --live-red: #C71414;
  --coral-grad: linear-gradient(139deg, rgba(239, 150, 106, 1) 0%, rgba(196, 53, 55, 1) 100%);
  --podium-gold: linear-gradient(180deg, rgba(229, 181, 65, 1) 0%, rgba(186, 114, 26, 1) 100%);
  --podium-silver: linear-gradient(180deg, rgba(177, 185, 194, 1) 0%, rgba(100, 106, 114, 1) 100%);
  --podium-bronze: linear-gradient(180deg, rgba(230, 166, 104, 1) 0%, rgba(157, 94, 34, 1) 100%);
  --me-grad: linear-gradient(172deg, rgba(255, 208, 170, 1) 0%, rgba(235, 173, 123, 1) 100%);
  --badge-done-bg: #DDEBDD;
  --badge-done-text: #14594E;
  --badge-up-bg: #F0DEB0;
  --badge-up-text: #C37124;
}

* {
  box-sizing: border-box;
  -webkit-tap-highlight-color: transparent;
}
html, body {
  max-width: 100vw;
  overflow-x: hidden;
}
html {
  scroll-padding-top: 68px;
  scroll-behavior: smooth;
}
body {
  margin: 0;
  background: var(--bg-tan);
  color: var(--ink-dark);
  font-family: 'DM Sans', 'Noto Sans Malayalam', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  -webkit-font-smoothing: antialiased;
}

h1, h2, h3, h4 {
  margin: 0;
}
button, input, select {
  font: inherit;
  color: inherit;
}
:focus-visible {
  outline: 3px solid var(--gold-btn);
  outline-offset: 2px;
}

.wrap {
  max-width: 1040px;
  margin: 0 auto;
  padding: 0 16px;
}

/* ================= HEADER ================= */
header {
  position: sticky;
  top: 0;
  z-index: 50;
  background: var(--bg-header);
  border-bottom: 1px solid rgba(217, 200, 172, 0.4);
}
.header-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 60px;
}
.logo-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  background: none;
  border: 0;
  cursor: pointer;
  padding: 0;
  text-decoration: none;
}
.logo-btn img {
  display: block;
}
.logo-divider {
  width: 1px;
  height: 28px;
  background-color: #CFCFCF;
}
nav {
  display: flex;
  align-items: center;
  gap: 4px;
}
nav a, nav button {
  color: #4F4F4F;
  text-decoration: none;
  padding: 8px 14px;
  border-radius: 10px;
  background: none;
  border: 0;
  cursor: pointer;
  font-family: 'DM Sans', sans-serif;
  font-size: 15px;
  font-weight: 500;
  transition: background 0.15s, color 0.15s;
}
nav a:hover, nav button:hover {
  background: rgba(133, 77, 58, 0.08);
  color: var(--ink-brown);
}
.burger {
  display: none;
  background: none;
  border: 1px solid var(--border-tan);
  border-radius: 10px;
  padding: 8px 12px;
  cursor: pointer;
  color: var(--ink-dark);
  font-weight: 600;
}

@media(max-width: 768px) {
  .burger {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
  }
  nav {
    display: none;
    position: absolute;
    top: 60px;
    left: 0;
    right: 0;
    flex-direction: column;
    align-items: stretch;
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-tan);
    padding: 12px 16px 20px;
    box-shadow: 0 14px 28px rgba(0,0,0,0.08);
    max-height: calc(100vh - 60px);
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }
  nav.open { display: flex; }
  nav a, nav button {
    padding: 12px 14px;
    text-align: left;
    width: 100%;
    font-size: 16px;
  }
}

/* ================= HERO ================= */
.hero-section {
  background: var(--bg-hero);
  color: #FFFFFF;
  padding: 56px 0 64px;
  position: relative;
  overflow: hidden;
}
.hero-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.hero-status-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--live-red);
  color: #FFFFFF;
  border-radius: 99px;
  padding: 6px 14px;
  font-family: 'Figtree', sans-serif;
  font-size: 14px;
  font-weight: 600;
  align-self: flex-start;
}
.hero-status-pill.upcoming {
  background: rgba(255, 255, 255, 0.18);
}
.dot-pulse {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #FFFFFF;
  animation: pulse-dot 1.4s infinite;
}
@keyframes pulse-dot {
  0% { transform: scale(0.9); opacity: 1; }
  50% { transform: scale(1.3); opacity: 0.4; }
  100% { transform: scale(0.9); opacity: 1; }
}

.hero-title {
  font-family: 'DM Serif Display', 'Noto Sans Malayalam', serif;
  font-size: clamp(42px, 7vw, 68px);
  line-height: 1.15;
  font-weight: 700;
  letter-spacing: -0.02em;
  background: linear-gradient(90deg, #FFEEDC 0%, #F5F4D8 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin: 0;
}

.hero-features {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 6px 0 12px;
}
.hero-feat-row {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
  font-family: 'Noto Sans Malayalam', 'DM Sans', sans-serif;
  font-size: 16px;
  color: #FFFFFF;
  line-height: 1.6;
}
.hero-diamond {
  display: inline-block;
  width: 8px;
  height: 8px;
  background: #F2B31B;
  transform: rotate(45deg);
  flex-shrink: 0;
}

.hero-countdown-box {
  background: var(--bg-tan);
  border: 0.8px solid rgba(255, 255, 255, 0.25);
  border-radius: 18px;
  padding: 12px 20px;
  max-width: 440px;
  margin: 8px 0 16px;
}
.hero-countdown-sub {
  font-family: 'Figtree', sans-serif;
  font-size: 13.5px;
  font-weight: 500;
  color: var(--ink-mute);
  margin-bottom: 4px;
}
.hero-countdown-main {
  font-family: 'Figtree', sans-serif;
  font-size: clamp(24px, 4vw, 32px);
  font-weight: 700;
  color: var(--ink-dark);
  font-variant-numeric: tabular-nums;
}

.hero-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  align-items: center;
}
.btn-gold {
  min-height: 52px;
  padding: 0 26px;
  border-radius: 14px;
  background: var(--gold-btn);
  color: var(--gold-btn-text);
  border: 1.6px solid transparent;
  font-family: 'Figtree', sans-serif;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  transition: transform 0.12s, filter 0.15s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
}
.btn-gold:hover {
  filter: brightness(1.05);
}
.btn-gold:active {
  transform: scale(0.98);
}
.btn-outline-white {
  min-height: 52px;
  padding: 0 24px;
  border-radius: 14px;
  background: transparent;
  color: #FFFFFF;
  border: 1.6px solid rgba(255, 255, 255, 0.55);
  font-family: 'Figtree', sans-serif;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.15s, border-color 0.15s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
}
.btn-outline-white:hover {
  background: rgba(255, 255, 255, 0.12);
  border-color: #FFFFFF;
}

/* ================= WINNER HIGHLIGHT SECTION ================= */
.winner-section {
  background: var(--bg-tan);
  padding: 36px 0;
}
.winner-card {
  max-width: 618px;
  margin: 0 auto;
  background: var(--coral-grad);
  border: 1px solid rgba(239, 150, 106, 0.5);
  border-radius: 28px;
  padding: 22px 26px;
  display: flex;
  align-items: center;
  gap: 22px;
  box-shadow: 0 12px 30px rgba(196, 53, 55, 0.15);
}
.winner-trophy {
  font-size: 44px;
  line-height: 1;
}
.winner-info {
  display: flex;
  flex-direction: column;
}
.winner-sub {
  color: #F3DFC4;
  font-family: 'Figtree', sans-serif;
  font-size: 15px;
  margin-bottom: 2px;
}
.winner-name {
  color: var(--bg-tan);
  font-family: 'Figtree', 'DM Serif Display', sans-serif;
  font-size: 24px;
  font-weight: 700;
  letter-spacing: 0.02em;
}
.winner-place {
  color: var(--bg-tan);
  font-family: 'Figtree', sans-serif;
  font-size: 15px;
  opacity: 0.9;
}

/* ================= WEEKLY QUIZ SCHEDULE SECTION ================= */
.schedule-section {
  background: var(--bg-schedule);
  padding: 48px 0 56px;
}
.sec-title {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: clamp(26px, 4vw, 32px);
  font-weight: 800;
  letter-spacing: -0.02em;
  color: #373737;
  margin-bottom: 4px;
}
.sec-subtitle {
  font-family: 'DM Sans', sans-serif;
  font-size: 16px;
  color: #474747;
  margin: 0 0 28px;
}
.schedule-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
}
@media(max-width: 960px) {
  .schedule-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media(max-width: 520px) {
  .schedule-grid {
    grid-template-columns: 1fr;
  }
}

.wk-card {
  background: var(--bg-card-light);
  border: 0.8px solid var(--border-card);
  border-radius: 20px;
  padding: 18px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  min-height: 168px;
  position: relative;
  transition: transform 0.15s, box-shadow 0.15s;
}
.wk-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}
.wk-card.is-live {
  background: var(--coral-grad);
  border-color: rgba(239, 150, 106, 0.6);
  color: var(--bg-tan);
  box-shadow: 0 10px 24px rgba(196, 53, 55, 0.22);
}
.wk-card-title {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: 22px;
  font-weight: 800;
  color: #3D3D3D;
}
.wk-card.is-live .wk-card-title {
  color: var(--bg-tan);
}
.wk-card-date {
  font-family: 'DM Sans', sans-serif;
  font-size: 15px;
  color: #4F4F4F;
}
.wk-card.is-live .wk-card-date {
  color: rgba(247, 240, 227, 0.9);
}

.wk-badge {
  align-self: flex-start;
  padding: 3px 12px;
  border-radius: 99px;
  font-family: 'Figtree', sans-serif;
  font-size: 13px;
  font-weight: 600;
  margin: 4px 0 6px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.wk-badge.completed {
  background: var(--badge-done-bg);
  color: var(--badge-done-text);
}
.wk-badge.upcoming {
  background: var(--badge-up-bg);
  color: var(--badge-up-text);
}
.wk-badge.live {
  background: #FFFFFF;
  color: var(--live-red);
}
.wk-badge.live .badge-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--live-red);
}

.wk-bottom-info {
  margin-top: auto;
  font-size: 13.5px;
}
.wk-bottom-label {
  color: #404040;
  font-size: 13px;
  margin-bottom: 2px;
}
.wk-winner-name {
  color: #CE4B43;
  font-weight: 700;
  font-size: 15px;
}
.wk-bottom-mute {
  color: #404040;
  font-size: 13px;
}
.wk-card.is-live .wk-bottom-mute {
  color: #F0DEB0;
}

/* ================= LEADERBOARD SECTION ================= */
.leaderboard-section {
  background: var(--bg-leaderboard);
  color: #FFFFFF;
  padding: 48px 0 56px;
}
.lb-title {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: clamp(28px, 4vw, 34px);
  font-weight: 800;
  color: #EAF0F8;
  margin-bottom: 4px;
}
.lb-subtitle {
  font-family: 'DM Sans', sans-serif;
  font-size: 16px;
  color: var(--bg-tan);
  margin: 0 0 28px;
}

.podium-container {
  display: grid;
  grid-template-columns: 1fr 1.15fr 1fr;
  gap: 10px;
  align-items: end;
  max-width: 1008px;
  margin: 0 auto 20px;
}
.podium-card {
  text-align: center;
  padding: 14px 10px;
  border-radius: 18px 18px 8px 8px;
  color: #FFFFFF;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}
.podium-card.p2 {
  background: var(--podium-silver);
  min-height: 140px;
  padding-bottom: 20px;
}
.podium-card.p1 {
  background: var(--podium-gold);
  min-height: 154px;
  padding-bottom: 28px;
}
.podium-card.p3 {
  background: var(--podium-bronze);
  min-height: 130px;
  padding-bottom: 14px;
}
.podium-rank-num {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: 32px;
  font-weight: 800;
  line-height: 1;
}
.podium-medal {
  font-size: 40px;
  line-height: 1;
}
.podium-name {
  font-family: 'SF Pro', 'DM Sans', sans-serif;
  font-weight: 700;
  font-size: 16px;
  margin: 6px 0 2px;
  overflow-wrap: anywhere;
}
.podium-place {
  font-size: 13px;
  opacity: 0.75;
}
.podium-pts {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: 22px;
  font-weight: 800;
  margin-top: 6px;
}

.table-card {
  background: var(--bg-card);
  border: 1px solid var(--border-tan);
  border-radius: 28px;
  padding: 8px 12px;
  max-width: 1008px;
  margin: 0 auto;
  overflow-x: auto;
}
.lb-table {
  width: 100%;
  border-collapse: collapse;
}
.lb-table th {
  padding: 10px 14px;
  text-align: left;
  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  font-weight: 700;
  color: var(--ink-mute);
}
.lb-table th.num {
  text-align: right;
}
.lb-table td {
  padding: 14px;
  border-top: 1px solid var(--border-tan);
  color: var(--ink-dark);
  font-family: 'DM Sans', sans-serif;
}
.lb-table td.rank-col {
  font-size: 16px;
  font-weight: 700;
  width: 90px;
}
.lb-table td.contestant-col {
  font-size: 16px;
  font-weight: 500;
}
.lb-table td.contestant-col small {
  display: block;
  font-size: 13px;
  color: var(--ink-mute);
  margin-top: 2px;
}
.lb-table td.pts-col {
  text-align: right;
  font-weight: 700;
  font-size: 16px;
  width: 120px;
}
.lb-table tr.is-me td {
  background: rgba(246, 172, 56, 0.15);
  font-weight: 700;
}

.me-rank-bar {
  background: var(--me-grad);
  border-radius: 24px;
  padding: 14px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  margin-top: 16px;
  max-width: 1008px;
  margin-left: auto;
  margin-right: auto;
  color: var(--ink-dark);
}
.me-rank-badge {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: var(--coral-grad);
  color: #FFFFFF;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Figtree', sans-serif;
  font-size: 16px;
  font-weight: 700;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(196, 53, 55, 0.25);
}
.me-info {
  flex: 1;
}
.me-info-title {
  font-family: 'DM Sans', sans-serif;
  font-size: 16px;
  font-weight: 700;
}
.me-info-sub {
  font-size: 13px;
  color: var(--ink-mute);
}
.me-points-val {
  font-family: 'DM Sans', sans-serif;
  font-size: 18px;
  font-weight: 700;
}

/* ================= QUIZ RULES SECTION ================= */
.rules-section {
  background: var(--bg-tan);
  padding: 56px 0 64px;
}
.rules-title {
  font-family: 'DM Serif Display', serif;
  font-size: 44px;
  color: var(--ink-dark);
  margin-bottom: 4px;
}
.rules-sub {
  font-family: 'DM Sans', sans-serif;
  font-size: 16px;
  color: var(--ink-mute);
  margin: 0 0 28px;
}
.rules-category-title {
  font-family: 'DM Sans', 'Noto Sans Malayalam', sans-serif;
  font-size: 19px;
  font-weight: 700;
  color: var(--ink-brown);
  margin: 26px 0 12px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.rules-category-title:first-child {
  margin-top: 0;
}
.rules-category-title::before {
  content: "";
  display: inline-block;
  width: 4px;
  height: 20px;
  background: var(--bg-hero);
  border-radius: 4px;
}
.rules-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.rule-item-card {
  background: var(--bg-card);
  border: 1px solid var(--border-tan);
  border-radius: 24px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
}
.rule-bullet {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #57230F;
  color: #FFFFFF;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  font-weight: 700;
  flex-shrink: 0;
}
.rule-text {
  font-family: 'Noto Sans Malayalam', 'DM Sans', sans-serif;
  font-size: 15.5px;
  line-height: 1.6;
  color: var(--ink-dark);
}

/* ================= FOOTER ================= */
footer {
  background: var(--bg-hero);
  color: #FFFFFF;
  padding: 26px 16px;
  text-align: center;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
}

/* ================= REGISTER / LOGIN MODAL & CONTAINER (NODES 1:340 & 1:386) ================= */
.auth-wrapper {
  padding: 40px 16px 60px;
  display: flex;
  justify-content: center;
}
.auth-card {
  width: 100%;
  max-width: 480px;
  background: var(--bg-card);
  border: 1px solid var(--border-tan);
  border-radius: 28px;
  padding: 24px 26px;
  box-shadow: 0 16px 36px rgba(0,0,0,0.06);
}
.auth-card-title {
  font-family: 'DM Serif Display', serif;
  font-size: 28px;
  color: var(--ink-dark);
  margin-bottom: 20px;
}

/* Segmented tab switcher */
.auth-tab-list {
  display: flex;
  background: var(--border-tan);
  border-radius: 14px;
  padding: 4px;
  height: 52px;
  margin-bottom: 16px;
}
.auth-tab-btn {
  flex: 1;
  border: 0;
  border-radius: 10px;
  background: none;
  font-family: 'DM Sans', sans-serif;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  color: var(--ink-mute);
  transition: all 0.15s;
}
.auth-tab-btn.active {
  background: var(--bg-card);
  color: var(--ink-dark);
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.auth-hint-text {
  font-family: 'Noto Sans Malayalam', sans-serif;
  font-size: 14px;
  line-height: 1.6;
  color: #000000;
  margin-bottom: 18px;
  text-align: left;
}
.auth-hint-link {
  color: var(--bg-hero);
  text-decoration: underline;
  cursor: pointer;
  font-weight: 600;
}

.form-group {
  margin-bottom: 16px;
}
.form-label {
  display: block;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 600;
  color: var(--ink-dark);
  margin-bottom: 6px;
}
.form-input {
  width: 100%;
  min-height: 52px;
  border-radius: 12px;
  border: 2px solid var(--border-tan);
  background: var(--bg-card);
  padding: 0 16px;
  font-family: 'DM Sans', sans-serif;
  font-size: 15px;
  color: var(--ink-dark);
  transition: border-color 0.15s, box-shadow 0.15s;
}
.form-input:focus {
  border-color: var(--bg-hero);
  outline: none;
  box-shadow: 0 0 0 3px rgba(133, 77, 58, 0.12);
}
.form-subtext {
  font-family: 'Noto Sans Malayalam', sans-serif;
  font-size: 13.5px;
  line-height: 1.5;
  color: var(--ink-sub);
  margin-top: 6px;
}
.form-subtext.highlight {
  color: var(--bg-hero);
}

.auth-btn-pill {
  width: 100%;
  min-height: 52px;
  border-radius: 99px;
  border: 2px solid transparent;
  font-family: 'DM Sans', sans-serif;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.12s, filter 0.15s;
}
.auth-btn-pill.primary {
  background: var(--gold-btn);
  color: var(--gold-btn-text);
  margin-top: 14px;
}
.auth-btn-pill.primary:hover {
  filter: brightness(1.05);
}
.auth-btn-pill.primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
.auth-btn-pill.secondary {
  background: transparent;
  border-color: var(--border-tan);
  color: var(--ink-dark);
  font-weight: 500;
  margin-top: 12px;
}
.auth-btn-pill.secondary:hover {
  background: rgba(217, 200, 172, 0.2);
}

/* ================= QUIZ TAKING & RESULTS ================= */
.quiz-card {
  max-width: 600px;
  margin: 36px auto;
  background: var(--bg-card);
  border: 1px solid var(--border-tan);
  border-radius: 28px;
  padding: 28px 24px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.06);
}
.quiz-progress-bar {
  height: 8px;
  background: var(--border-tan);
  border-radius: 99px;
  overflow: hidden;
  margin: 12px 0 24px;
}
.quiz-progress-fill {
  height: 100%;
  background: var(--coral-grad);
  transition: width 0.3s ease;
}
.quiz-question-title {
  font-family: 'DM Serif Display', 'Bricolage Grotesque', serif;
  font-size: 24px;
  line-height: 1.4;
  color: var(--ink-dark);
  margin-bottom: 22px;
}
.quiz-opt-btn {
  display: block;
  width: 100%;
  text-align: left;
  min-height: 56px;
  padding: 14px 18px;
  margin-bottom: 12px;
  border-radius: 14px;
  border: 2px solid var(--border-tan);
  background: var(--bg-card-light);
  cursor: pointer;
  font-family: 'DM Sans', sans-serif;
  font-weight: 600;
  font-size: 16px;
  color: var(--ink-dark);
  transition: all 0.15s;
}
.quiz-opt-btn:hover {
  background: #F3DFC3;
}
.quiz-opt-btn.selected {
  border-color: var(--bg-hero);
  background: rgba(246, 172, 56, 0.22);
}

.score-display {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: 80px;
  font-weight: 800;
  color: var(--bg-hero);
  line-height: 1;
  text-align: center;
  margin: 16px 0;
}

.alert-box {
  border-radius: 14px;
  padding: 12px 16px;
  font-size: 14.5px;
  margin: 14px 0;
}
.alert-box.bad {
  background: #FBE4E2;
  color: #B3261E;
}
.alert-box.ok {
  background: #DDF3EE;
  color: #14594E;
}
.alert-box.info {
  background: #F0DEB0;
  color: #6F5C44;
}

.sk-bar {
  height: 54px;
  border-radius: 14px;
  background: linear-gradient(90deg, #EADBC5, #FBF7EE, #EADBC5);
  background-size: 200% 100%;
  animation: sk-sh 1.2s infinite;
  margin-bottom: 10px;
}
@keyframes sk-sh {
  to { background-position: -200% 0; }
}

/* ================= COMPREHENSIVE MOBILE RESPONSIVE STYLES ================= */
@media (max-width: 640px) {
  .wrap {
    padding: 0 14px;
  }

  /* Header */
  .header-bar {
    height: 56px;
  }
  nav {
    top: 56px !important;
    max-height: calc(100vh - 56px) !important;
  }
  .logo-btn img:first-child {
    width: 24px;
    height: 28px;
  }
  .logo-divider {
    height: 24px;
  }
  .logo-btn img:last-child {
    width: 54px;
    height: 28px;
  }

  /* Hero Section */
  .hero-section {
    padding: 30px 0 40px;
  }
  .hero-container {
    gap: 12px;
  }
  .hero-status-pill {
    padding: 5px 12px;
    font-size: 13px;
  }
  .hero-title {
    font-size: clamp(36px, 12vw, 56px);
  }
  .hero-features {
    gap: 6px;
    padding: 4px 0 6px;
  }
  .hero-feat-row {
    font-size: 14px;
    gap: 8px;
    line-height: 1.5;
  }
  .hero-diamond {
    width: 6px;
    height: 6px;
  }
  .hero-countdown-box {
    padding: 12px 14px;
    border-radius: 16px;
    max-width: 100%;
    margin: 4px 0 12px;
  }
  .hero-countdown-sub {
    font-size: 12px;
  }
  .hero-countdown-main {
    font-size: clamp(18px, 5.5vw, 26px);
  }
  .hero-actions {
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
    width: 100%;
  }
  .hero-actions .btn-gold,
  .hero-actions .btn-outline-white {
    width: 100%;
    min-height: 48px;
    font-size: 15.5px;
    padding: 0 18px;
  }

  /* Winner Banner */
  .winner-section {
    padding: 22px 0;
  }
  .winner-card {
    padding: 14px 16px;
    border-radius: 18px;
    gap: 14px;
  }
  .winner-trophy {
    font-size: 32px;
  }
  .winner-sub {
    font-size: 12.5px;
  }
  .winner-name {
    font-size: 18px;
    line-height: 1.25;
  }
  .winner-place {
    font-size: 12.5px;
  }

  /* Schedule */
  .schedule-section {
    padding: 34px 0 42px;
  }
  .sec-title {
    font-size: clamp(23px, 6vw, 28px);
  }
  .sec-subtitle {
    font-size: 14px;
    margin: 0 0 18px;
  }
  .wk-card {
    padding: 15px 14px;
    border-radius: 16px;
    min-height: auto;
  }
  .wk-card-title {
    font-size: 19px;
  }
  .wk-card-date {
    font-size: 13.5px;
  }

  /* Leaderboard */
  .leaderboard-section {
    padding: 34px 0 42px;
  }
  .lb-title {
    font-size: clamp(24px, 6vw, 28px);
  }
  .lb-subtitle {
    font-size: 14px;
    margin: 0 0 18px;
  }

  /* 3-Column Olympic Podium Mobile Scaling */
  .podium-container {
    gap: 6px;
    margin: 0 auto 14px;
  }
  .podium-card {
    padding: 10px 4px 12px;
    border-radius: 14px 14px 6px 6px;
  }
  .podium-card.p1 {
    min-height: 134px;
    padding-bottom: 16px;
  }
  .podium-card.p2 {
    min-height: 120px;
    padding-bottom: 12px;
  }
  .podium-card.p3 {
    min-height: 110px;
    padding-bottom: 8px;
  }
  .podium-medal {
    font-size: 26px;
  }
  .podium-rank-num {
    font-size: 20px;
  }
  .podium-name {
    font-size: 12px;
    margin: 4px 0 1px;
    line-height: 1.2;
    word-break: break-word;
    overflow-wrap: anywhere;
  }
  .podium-place {
    font-size: 11px;
    line-height: 1.2;
    word-break: break-word;
    opacity: 0.85;
  }
  .podium-pts {
    font-size: 15.5px;
    margin-top: 4px;
  }

  /* Leaderboard Table */
  .table-card {
    border-radius: 18px;
    padding: 4px 2px;
  }
  .lb-table th {
    padding: 8px 6px;
    font-size: 12px;
  }
  .lb-table td {
    padding: 10px 6px;
  }
  .lb-table td.rank-col {
    width: 44px;
    font-size: 13.5px;
    padding-left: 8px;
  }
  .lb-table td.contestant-col {
    font-size: 13.5px;
  }
  .lb-table td.contestant-col small {
    font-size: 11.5px;
    margin-top: 1px;
  }
  .lb-table td.pts-col {
    width: 70px;
    font-size: 14px;
    padding-right: 8px;
  }

  /* My Rank Bar */
  .me-rank-bar {
    padding: 12px 14px;
    border-radius: 18px;
    gap: 12px;
    margin-top: 14px;
  }
  .me-rank-badge {
    width: 38px;
    height: 38px;
    font-size: 14px;
  }
  .me-info-title {
    font-size: 13.5px;
  }
  .me-info-sub {
    font-size: 11.5px;
  }
  .me-points-val {
    font-size: 15.5px;
  }

  /* Rules Section */
  .rules-section {
    padding: 34px 0 42px;
  }
  .rules-title {
    font-size: clamp(28px, 7.5vw, 36px);
  }
  .rules-sub {
    font-size: 14px;
    margin: 0 0 18px;
  }
  .rules-category-title {
    font-size: 16px;
    margin: 18px 0 10px;
    gap: 8px;
  }
  .rules-category-title::before {
    width: 3.5px;
    height: 16px;
  }
  .rules-list {
    gap: 9px;
  }
  .rule-item-card {
    padding: 11px 13px;
    border-radius: 16px;
    gap: 11px;
    align-items: flex-start;
  }
  .rule-bullet {
    width: 24px;
    height: 24px;
    font-size: 12px;
    margin-top: 2px;
    flex-shrink: 0;
  }
  .rule-text {
    font-size: 13.5px;
    line-height: 1.55;
    word-break: break-word;
    overflow-wrap: break-word;
  }

  /* Auth Modals (Register / Login / Reset) */
  .auth-wrapper {
    padding: 16px 8px 32px;
  }
  .auth-card {
    padding: 18px 14px;
    border-radius: 18px;
  }
  .auth-card-title {
    font-size: 21px;
    margin-bottom: 14px;
  }
  .auth-tab-list {
    height: 46px;
    border-radius: 12px;
    margin-bottom: 12px;
  }
  .auth-tab-btn {
    font-size: 14.5px;
  }
  .auth-hint-text {
    font-size: 13px;
    margin-bottom: 14px;
  }
  .form-group {
    margin-bottom: 12px;
  }
  .form-label {
    font-size: 13px;
    margin-bottom: 4px;
  }
  .form-input {
    font-size: 16px; /* Crucial for iOS Safari: prevents viewport zooming */
    min-height: 48px;
    padding: 0 12px;
    border-radius: 10px;
  }
  .form-subtext {
    font-size: 12px;
    margin-top: 4px;
  }
  .auth-btn-pill {
    min-height: 48px;
    font-size: 15px;
  }

  /* Quiz Taking & Results */
  .quiz-card {
    margin: 14px auto;
    padding: 18px 14px;
    border-radius: 18px;
  }
  .quiz-question-title {
    font-size: 18px;
    margin-bottom: 16px;
  }
  .quiz-opt-btn {
    min-height: 48px;
    padding: 11px 13px;
    font-size: 14.5px;
    border-radius: 12px;
    margin-bottom: 9px;
  }
  .score-display {
    font-size: 52px;
    margin: 8px 0;
  }

  /* Footer */
  footer {
    padding: 20px 14px calc(20px + env(safe-area-inset-bottom, 0px));
    font-size: 12.5px;
  }
}

/* Extra small devices (<= 360px width) */
@media (max-width: 360px) {
  .wrap {
    padding: 0 8px;
  }
  .hero-feat-row {
    font-size: 12.5px;
    gap: 6px;
  }
  .podium-container {
    gap: 4px;
  }
  .podium-card {
    padding: 8px 2px 10px;
  }
  .podium-medal {
    font-size: 22px;
  }
  .podium-rank-num {
    font-size: 17px;
  }
  .podium-name {
    font-size: 11px;
  }
  .podium-pts {
    font-size: 14px;
  }
  .lb-table td.rank-col {
    width: 36px;
    font-size: 12.5px;
    padding-left: 4px;
  }
  .lb-table td.pts-col {
    width: 62px;
    font-size: 12.5px;
    padding-right: 4px;
  }
  .rule-item-card {
    padding: 10px 10px;
    gap: 8px;
  }
  .rule-text {
    font-size: 12.5px;
  }
}
</style>
</head>
<body>
<div id="root"></div>

<script>
window.INITIAL_DATA = {
    weeks: @json($weeks),
    leaderboard: @json($leaderboard),
    user: @json($currentUser),
    csrfToken: @json($csrfToken),
    demoCode: @json($demoCode)
};
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.production.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/18.2.0/umd/react-dom.production.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/htm@3.1.1/dist/htm.umd.js"></script>
@verbatim
<script>
const { useState, useEffect, useCallback } = React;
const html = htm.bind(React.createElement);

/* ============ CSRF & API LAYER ============ */
const CSRF_TOKEN = window.INITIAL_DATA?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

let initialScheduleLoaded = false;
let initialLeaderboardLoaded = false;

const api = {
  async schedule() {
    if (!initialScheduleLoaded && window.INITIAL_DATA?.weeks?.length) {
      initialScheduleLoaded = true;
      return window.INITIAL_DATA.weeks;
    }
    const res = await fetch('/quiz/api/schedule', {
      headers: { 'Accept': 'application/json' },
      credentials: 'same-origin'
    });
    if (!res.ok) throw { code: 'NETWORK' };
    return await res.json();
  },

  async leaderboard(uid) {
    if (!initialLeaderboardLoaded && window.INITIAL_DATA?.leaderboard?.top) {
      initialLeaderboardLoaded = true;
      return window.INITIAL_DATA.leaderboard;
    }
    const res = await fetch('/quiz/api/leaderboard', {
      headers: { 'Accept': 'application/json' },
      credentials: 'same-origin'
    });
    if (!res.ok) throw { code: 'NETWORK' };
    return await res.json();
  },

  async sendOtp(phone, reg) {
    if (!/^\d{10}$/.test(phone)) throw { code: 'BADPHONE' };
    const res = await fetch('/quiz/api/auth/send-otp', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin',
      body: JSON.stringify({ phone, register: reg ? 1 : 0 })
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return true;
  },

  async verifyOtp(phone, code, reg) {
    const body = {
      phone,
      code,
      register: reg ? 1 : 0,
    };
    if (reg) {
      body.name = reg.name;
      body.age = reg.age;
      body.locality = reg.locality;
      body.territory = reg.territory;
    }
    const res = await fetch('/quiz/api/auth/verify-otp', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin',
      body: JSON.stringify(body)
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return {
      id: data.id,
      name: data.name,
      territory: data.territory || data.locality,
      locality: data.locality,
      phone: data.phone,
      email: data.email,
    };
  },

  async registerPassword(payload) {
    const res = await fetch('/quiz/api/auth/register-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin',
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return data;
  },

  async loginPassword(identifier, password, countryCode) {
    const res = await fetch('/quiz/api/auth/login-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin',
      body: JSON.stringify({ identifier, password, country_code: countryCode })
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return data;
  },

  async forgotPassword(identifier) {
    const res = await fetch('/quiz/api/auth/forgot-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin',
      body: JSON.stringify({ identifier })
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return data;
  },

  async resetPassword(email, token, password) {
    const res = await fetch('/quiz/api/auth/reset-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin',
      body: JSON.stringify({ email, token, password })
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return data;
  },

  async me() {
    try {
      const res = await fetch('/quiz/api/auth/me', {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
      });
      if (!res.ok) return null;
      const data = await res.json();
      return data.user || null;
    } catch(e) {
      return null;
    }
  },

  async startQuiz(uid) {
    const res = await fetch('/quiz/api/start', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin'
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return data;
  },

  async submit(uid, answers) {
    const res = await fetch('/quiz/api/submit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      },
      credentials: 'same-origin',
      body: JSON.stringify({ answers })
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return data;
  },

  async logout() {
    try {
      await fetch('/quiz/api/auth/logout', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        credentials: 'same-origin'
      });
    } catch(e) {}
  }
};

const ERR = {
  NETWORK: "കണക്ഷൻ പരിശോധിച്ച ശേഷം വീണ്ടും ശ്രമിക്കുക.",
  BADPHONE: "ദയവായി സാധുവായ 10 അക്ക മൊബൈൽ നമ്പർ നൽകുക.",
  EXISTS: "ഈ മൊബൈൽ നമ്പർ അല്ലെങ്കിൽ ഇമെയിൽ ഇതിനകം രജിസ്റ്റർ ചെയ്തതാണ്. ലോഗിൻ ചെയ്യുക.",
  NOTFOUND: "ഈ നമ്പറിൽ അക്കൗണ്ട് കണ്ടെത്തിയില്ല. രജിസ്റ്റർ ചെയ്യുക.",
  EXPIRED: "കോഡിന്റെ കാലാവധി കഴിഞ്ഞു. പുതിയ ഒരെണ്ണം ആവശ്യപ്പെടുക.",
  INVALID: "നൽകിയ കോഡ് തെറ്റാണ്. വീണ്ടും പരിശോധിച്ച് നൽകുക.",
  INVALID_PASSWORD: "പാസ്‌വേഡ് തെറ്റാണ്. വീണ്ടും ശ്രമിക്കുക.",
  NO_PASSWORD: "ഈ അക്കൗണ്ടിൽ പാസ്‌വേഡ് സെറ്റ് ചെയ്തിട്ടില്ല.",
  NO_EMAIL: "പാസ്‌വേഡ് റീസെറ്റ് ചെയ്യാൻ ഇമെയിൽ ലഭ്യമല്ല.",
  CLOSED: "ഈ ആഴ്ചയിലെ ക്വിസ് ഇപ്പോൾ ക്ലോസ് ചെയ്തിരിക്കുന്നു.",
  DONE: "നിങ്ങൾ ഈ ആഴ്ചയിലെ ക്വിസ് ഇതിനകം പൂർത്തിയാക്കിയിട്ടുണ്ട്."
};

/* ============ HELPERS ============ */
const useNow = () => {
  const [n, s] = useState(Date.now());
  useEffect(() => {
    const t = setInterval(() => s(Date.now()), 1000);
    return () => clearInterval(t);
  }, []);
  return n;
};

const useLoad = (fn, deps) => {
  const [st, set] = useState({ loading: true });
  const [k, setK] = useState(0);
  useEffect(() => {
    let on = 1;
    set({ loading: true });
    fn().then(data => on && set({ data }), e => on && set({ err: e.message || ERR[e.code] || ERR.NETWORK }));
    return () => { on = 0; };
  }, [...deps, k]);
  return { ...st, retry: () => setK(k + 1) };
};

const hms = ms => {
  const s = Math.max(0, Math.floor(ms / 1e3)), d = Math.floor(s / 86400), p = n => String(n).padStart(2, "0");
  return (d ? d + "d " : "") + [Math.floor(s % 86400 / 3600), Math.floor(s % 3600 / 60), s % 60].map(p).join(" : ");
};

const fmt = (t, o) => new Intl.DateTimeFormat(undefined, o).format(t);
const wkStatus = (w, now = Date.now()) => now < w.start ? "upcoming" : now <= w.end ? "live" : "completed";

const Err = ({ code, retry }) => html`
  <div className="alert-box bad" role="alert">
    ${ERR[code] || code || ERR.NETWORK}
    ${retry && html`<button className="btn-gold" style=${{ minHeight: 32, padding: "0 10px", marginLeft: 8, fontSize: 13 }} onClick=${retry}>Try again</button>`}
  </div>
`;

const Skel = () => html`
  <div>
    <div className="sk-bar"/>
    <div className="sk-bar"/>
    <div className="sk-bar"/>
  </div>
`;

/* ============ COMPONENTS ============ */

/* Top Navigation Bar */
function Header({ onGo, user, onLogout }) {
  const [open, setOpen] = useState(false);

  return html`
    <header>
      <div className="wrap header-bar">
        <button className="logo-btn" onClick=${() => onGo("top")} aria-label="Aslam Quran Award Home">
          <img src="/images/quiz/logo_icon.svg" alt="Award Logo Icon" width="28" height="32"/>
          <div className="logo-divider"></div>
          <img src="/images/quiz/logo_text.svg" alt="Award Logo Text" width="62" height="32"/>
        </button>

        <button className="burger" onClick=${() => setOpen(!open)} aria-expanded=${open} aria-label="Toggle navigation">
          ${open ? "✕ Close" : "☰ Menu"}
        </button>

        <nav className=${open ? "open" : ""}>
          <a href="#top" onClick=${(e) => { e.preventDefault(); setOpen(false); onGo("top"); }}>Home</a>
          <button onClick=${() => { setOpen(false); onGo("schedule"); }}>Schedule</button>
          <button onClick=${() => { setOpen(false); onGo("leaderboard"); }}>Leaderboard</button>
          <button onClick=${() => { setOpen(false); onGo("rules"); }}>Rules</button>
          ${user ? html`
            <button onClick=${() => { setOpen(false); onGo("leaderboard"); }} style=${{ color: "var(--bg-hero)", fontWeight: 700 }}>
              👤 ${user.name ? user.name.split(" ")[0] : "Profile"}
            </button>
            <button onClick=${() => { setOpen(false); onLogout(); }} style=${{ color: "#C71414" }}>
              Log out
            </button>
          ` : null}
        </nav>
      </div>
    </header>
  `;
}

/* Hero Section (Figma Node 1:3) */
function Hero({ weeks, onStart, now, user, onGoRules }) {
  const live = weeks.find(w => wkStatus(w, now) === "live");
  const next = weeks.find(w => wkStatus(w, now) === "upcoming");
  const isCompleted = !live && !next;

  const btnText = live 
    ? "Start quiz" 
    : user 
      ? "✓ Registered · View schedule" 
      : "Register / Start quiz";

  return html`
    <section className="hero-section" id="top">
      <div className="wrap hero-container">
        <div className=${"hero-status-pill " + (!live ? "upcoming" : "")}>
          ${live ? html`<div className="dot-pulse"></div>` : null}
          ${live ? "Quiz is live" : next ? "Quiz upcoming" : "Season completed"}
        </div>

        <h1 className="hero-title">തിബ്‌യാൻ</h1>

        <div className="hero-features">
          <div className="hero-feat-row">
            <span>വീക്കിലി ഓൺലൈൻ ഖുർആൻ ക്വിസ്</span>
            <span className="hero-diamond"></span>
            <span>എല്ലാ വെള്ളിയാഴ്ചകളിലും</span>
            <span className="hero-diamond"></span>
            <span>5 ചോദ്യങ്ങൾ</span>
          </div>
          <div className="hero-feat-row">
            <span>1 ലക്ഷം രൂപയുടെ ക്യാഷ് പ്രൈസ്</span>
            <span className="hero-diamond"></span>
            <span>എല്ലാ ആഴ്ചയും സമ്മാനങ്ങൾ</span>
          </div>
        </div>

        <div className="hero-countdown-box">
          <div className="hero-countdown-sub">
            ${live 
              ? `Week ${live.id} · Closes in` 
              : next 
                ? `Next quiz · ${fmt(next.start, { day: "numeric", month: "short", weekday: "short" })} 05:00 IST` 
                : "Season Finale"}
          </div>
          <div className="hero-countdown-main">
            ${live 
              ? `Closes in ${hms(live.end - now)}` 
              : next 
                ? `Starts in ${hms(next.start - now)}` 
                : "All quizzes completed"}
          </div>
        </div>

        <div className="hero-actions">
          <button className="btn-gold" onClick=${onStart} disabled=${isCompleted}>
            ${btnText}
          </button>
          <button className="btn-outline-white" onClick=${onGoRules}>
            View Quiz rules
          </button>
        </div>
      </div>
    </section>
  `;
}

/* Winner Banner Section (Figma Node 1:71) */
function WinnerBanner({ weeks }) {
  const winnerWeek = [...weeks].reverse().find(w => w.winner);

  return html`
    <section className="winner-section">
      <div className="wrap">
        <div className="winner-card">
          <div className="winner-trophy" aria-hidden="true">🏆</div>
          <div className="winner-info">
            <div className="winner-sub">
              ${winnerWeek 
                ? `${fmt(winnerWeek.start, { day: "2-digit", month: "2-digit", year: "2-digit" })} · Week ${winnerWeek.id} Winner` 
                : "Week 1 Winner"}
            </div>
            <div className="winner-name">
              ${winnerWeek ? winnerWeek.winner.name : "Fathima Beevi"}
            </div>
            <div className="winner-place">
              ${winnerWeek ? (winnerWeek.winner.territory || winnerWeek.winner.locality || "Kerala") : "Kozhikode"}
            </div>
          </div>
        </div>
      </div>
    </section>
  `;
}

/* Weekly Quiz Schedule Section (Figma Node 1:84) */
function WeeklySchedule({ weeks, now }) {
  // Ensure exactly 10 weeks are displayed as in Figma
  const totalWeeks = Array.from({ length: 10 }, (_, index) => {
    const w = weeks[index];
    if (w) return w;
    return {
      id: index + 1,
      start: Date.now() + index * 7 * 86400000,
      end: Date.now() + index * 7 * 86400000 + 86400000,
      winner: null
    };
  });

  return html`
    <section className="schedule-section" id="schedule">
      <div className="wrap">
        <h2 className="sec-title">Weekly quiz schedule</h2>
        <p className="sec-subtitle">Ten weeks. One quiz each Friday</p>

        <div className="schedule-grid">
          ${totalWeeks.map(w => {
            const s = wkStatus(w, now);
            const isLive = s === "live";
            return html`
              <div key=${w.id} className=${"wk-card " + (isLive ? "is-live" : "")}>
                <div className="wk-card-title">Week ${w.id}</div>
                <div className="wk-card-date">
                  ${fmt(w.start, { weekday: "long" })}, ${fmt(w.start, { day: "numeric", month: "short" })}
                </div>

                <div className=${"wk-badge " + (isLive ? "live" : s === "completed" ? "completed" : "upcoming")}>
                  ${isLive ? html`<span className="badge-dot"></span>` : null}
                  ${isLive ? "Live now" : s === "completed" ? "Completed" : "Upcoming"}
                </div>

                <div className="wk-bottom-info">
                  ${s === "completed" ? html`
                    <div>
                      <div className="wk-bottom-label">Weekly winner</div>
                      <div className="wk-winner-name">${w.winner ? w.winner.name : "To be announced"}</div>
                    </div>
                  ` : isLive ? html`
                    <div className="wk-bottom-mute">Open until ${fmt(w.end, { hour: "2-digit", minute: "2-digit" })}</div>
                  ` : html`
                    <div className="wk-bottom-mute">Coming soon</div>
                  `}
                </div>
              </div>
            `;
          })}
        </div>
      </div>
    </section>
  `;
}

/* Leaderboard Section with Top 3 Podium & Table (Figma Node 1:189) */
function Leaderboard({ user, tick }) {
  const { data, err, loading, retry } = useLoad(() => api.leaderboard(user && user.id), [user && user.id, tick]);
  const top = data?.top || [];
  const me = data?.me;

  // Podium order: 2 (Silver), 1 (Gold), 3 (Bronze)
  const rank1 = top.find(u => u.rank === 1) || top[0];
  const rank2 = top.find(u => u.rank === 2) || top[1];
  const rank3 = top.find(u => u.rank === 3) || top[2];

  const remaining = top.slice(3);

  return html`
    <section className="leaderboard-section" id="leaderboard">
      <div className="wrap">
        <h2 className="lb-title">Leaderboard</h2>
        <p className="lb-subtitle">Total points across all completed weeks.</p>

        ${loading ? html`<${Skel}/>` : err ? html`<${Err} code=${err} retry=${retry}/>` : html`
          <div>
            ${top.length > 0 ? html`
              <div className="podium-container">
                <!-- Rank 2 -->
                <div className="podium-card p2">
                  <div className="podium-rank-num">2</div>
                  <div className="podium-name">${rank2 ? rank2.name : "Rayan Abdullah"}</div>
                  <div className="podium-place">${rank2 ? (rank2.territory || rank2.locality) : "Kochi"}</div>
                  <div className="podium-pts">${rank2 ? rank2.points : "230"}</div>
                </div>

                <!-- Rank 1 -->
                <div className="podium-card p1">
                  <div className="podium-medal">🥇</div>
                  <div className="podium-name">${rank1 ? rank1.name : "Fathima Beevi"}</div>
                  <div className="podium-place">${rank1 ? (rank1.territory || rank1.locality) : "Kozhikode"}</div>
                  <div className="podium-pts">${rank1 ? rank1.points : "260"}</div>
                </div>

                <!-- Rank 3 -->
                <div className="podium-card p3">
                  <div className="podium-rank-num">3</div>
                  <div className="podium-name">${rank3 ? rank3.name : "Amina Zahra"}</div>
                  <div className="podium-place">${rank3 ? (rank3.territory || rank3.locality) : "Thrissur"}</div>
                  <div className="podium-pts">${rank3 ? rank3.points : "220"}</div>
                </div>
              </div>

              <!-- Points Table -->
              <div className="table-card">
                <table className="lb-table">
                  <thead>
                    <tr>
                      <th style=${{ width: "90px" }}>Rank</th>
                      <th>Contestant</th>
                      <th className="num" style=${{ width: "120px" }}>Points</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${remaining.map(u => html`
                      <tr key=${u.id} className=${user && u.id === user.id ? "is-me" : ""}>
                        <td className="rank-col">${u.rank}</td>
                        <td className="contestant-col">
                          <div>${u.name}</div>
                          <small>${u.territory || u.locality || ""}</small>
                        </td>
                        <td className="pts-col">${u.points}</td>
                      </tr>
                    `)}
                  </tbody>
                </table>
              </div>
            ` : html`
              <div className="table-card" style=${{ padding: 28, textAlign: "center", color: "var(--ink-mute)" }}>
                No points recorded yet. Be the first to play and climb the table!
              </div>
            `}

            <!-- User Position Card (Figma Me-Card) -->
            ${user && me ? html`
              <div className="me-rank-bar">
                <div className="me-rank-badge">#${me.rank}</div>
                <div className="me-info">
                  <div className="me-info-title">${me.name} (You)</div>
                  <div className="me-info-sub">${me.territory || me.locality || "Kerala"}</div>
                </div>
                <div className="me-points-val">${me.points}</div>
              </div>
            ` : null}
          </div>
        `}
      </div>
    </section>
  `;
}

/* Quiz Rules Section */
const QUIZ_RULE_CATEGORIES = [
  {
    category: "രജിസ്ട്രേഷൻ",
    rules: [
      { num: 1, text: "www.aslamquranaward.com/thibyan എന്ന ലിങ്കിൽ പ്രവേശിച്ച് പേരും മൊബൈൽ നമ്പറും നൽകി വളരെ എളുപ്പത്തിൽ രജിസ്റ്റർ ചെയ്യാം. ഒരുതവണ രജിസ്റ്റർ ചെയ്താൽ പിന്നീടിത് മൊബൈൽ നമ്പർ നൽകി ലോഗിൻ ചെയ്താൽ മതിയാകും." },
      { num: 2, text: "ഇന്ത്യക്കു പുറത്തുള്ളവർക്ക് ഇമെയിൽ ഐഡിയും പാസ്വേഡും നൽകി രജിസ്റ്റർ ചെയ്യാം." },
      { num: 3, text: "പ്രായപരിധിയില്ല. ആർക്കും പങ്കെടുക്കാം." }
    ]
  },
  {
    category: "ക്വിസ് സമയക്രമം",
    rules: [
      { num: 4, text: "2026 ഒക്ടോബർ 16 മുതൽ ഡിസംബർ 18 വരെ, എല്ലാ വെള്ളിയാഴ്ചയും ക്വിസ് നടക്കും (ആകെ 10 ആഴ്ച)." },
      { num: 5, text: "വെള്ളിയാഴ്ച രാവിലെ 5 മണി (ഇന്ത്യൻ സമയം) മുതൽ പിറ്റേന്ന് രാവിലെ 5 മണി വരെ, 24 മണിക്കൂറിനുള്ളിൽ പങ്കെടുക്കാം." },
      { num: 6, text: "ഒരാൾക്ക് ആഴ്ചയിൽ ഒരു തവണ മാത്രമേ മത്സരിക്കാൻ കഴിയൂ." }
    ]
  },
  {
    category: "ചോദ്യങ്ങളും പോയിന്റുകളും",
    rules: [
      { num: 7, text: "ഓരോ ക്വിസിലും 5 ചോദ്യങ്ങളുണ്ടാകും. ഓരോ ശരിയുത്തരത്തിനും 10 പോയിന്റ് ലഭിക്കും. ഒരു ആഴ്ചയിൽ പരമാവധി 50 പോയിന്റ് നേടാം." },
      { num: 8, text: "എല്ലാ ആഴ്ചകളിലെയും പോയിന്റുകൾ കൂട്ടിച്ചേർത്താണ് ആകെ പോയിന്റ് കണക്കാക്കുന്നത്." },
      { num: 9, text: "മത്സരാർത്ഥികൾക്ക് സ്വന്തം പോയിന്റുകളും ലീഡർബോർഡും വെബ്സൈറ്റിൽ കാണാം. ക്വിസുമായി ബന്ധപ്പെട്ട അറിയിപ്പുകളും അപ്ഡേറ്റുകളും വെബ്സൈറ്റിലൂടെ ലഭിക്കും." }
    ]
  },
  {
    category: "സമ്മാനങ്ങൾ",
    rules: [
      { num: 10, text: "ഡിസംബർ 18-ലെ അവസാന ക്വിസ് പൂർത്തിയാക്കുമ്പോൾ, ഏറ്റവും കൂടുതൽ ആകെ പോയിന്റ് നേടുന്ന മത്സരാർത്ഥിക്ക് ₹1,00,000 ക്യാഷ് പ്രൈസ് ലഭിക്കും." },
      { num: 11, text: "ഓരോ ആഴ്ചയും അഞ്ച് ചോദ്യങ്ങൾക്കും ശരിയുത്തരം നൽകിയവരിൽ നിന്ന് നറുക്കെടുപ്പിലൂടെ ഒരാളെ തിരഞ്ഞെടുത്ത് പ്രത്യേക സമ്മാനം നൽകും. ഈ നറുക്കെടുപ്പിൽ ക്വിസ് പൂർത്തിയാക്കാൻ എടുത്ത സമയം പരിഗണിക്കില്ല." }
    ]
  },
  {
    category: "ഗ്രാൻഡ് പ്രൈസിലെ സമനില പരിഹരിക്കുന്ന രീതി",
    rules: [
      { num: 12, text: "ഒന്നിലധികം മത്സരാർത്ഥികൾക്ക് ഒരേ ആകെ പോയിന്റ് ലഭിച്ചാൽ, പത്ത് ആഴ്ചകളിലെയും ക്വിസുകൾ പൂർത്തിയാക്കാൻ എടുത്ത ആകെ സമയം ഏറ്റവും കുറവുള്ള മത്സരാർത്ഥികളെ വിജയികളായി തിരഞ്ഞെടുക്കും." },
      { num: 13, text: "ഓരോ ക്വിസും ആരംഭിക്കുന്ന നിമിഷം മുതൽ അവസാന ഉത്തരം സമർപ്പിക്കുന്നതുവരെയുള്ള സമയം സിസ്റ്റം രേഖപ്പെടുത്തും." },
      { num: 14, text: "സമയം സമനില പരിഹരിക്കാൻ മാത്രമേ ഉപയോഗിക്കൂ. പോയിന്റ് കണക്കാക്കുന്നതിൽ സമയത്തിന് പങ്കുണ്ടാകില്ല." },
      { num: 15, text: "ലീഡർബോർഡിൽ ആദ്യം ആകെ പോയിന്റിന്റെ അടിസ്ഥാനത്തിലും പോയിന്റ് തുല്യമായാൽ ആകെ സമയത്തിന്റെ അടിസ്ഥാനത്തിലും ക്രമം നിശ്ചയിക്കും." },
      { num: 16, text: "സമയത്തിന്റെ അടിസ്ഥാനത്തിലുള്ള സമനില പരിഹാരം ഗ്രാൻഡ് പ്രൈസിന് മാത്രമേ ബാധകമാകൂ." }
    ]
  }
];

function QuizRules() {
  return html`
    <section className="rules-section" id="rules">
      <div className="wrap">
        <h2 className="rules-title">Quiz rules</h2>
        <p className="rules-sub"></p>

        <div>
          ${QUIZ_RULE_CATEGORIES.map((cat, catIdx) => html`
            <div key=${catIdx} style=${{ marginBottom: 28 }}>
              <div className="rules-category-title">${cat.category}</div>
              <div className="rules-list">
                ${cat.rules.map((rule) => html`
                  <div key=${rule.num} className="rule-item-card">
                    <div className="rule-bullet">${rule.num}</div>
                    <div className="rule-text">${rule.text}</div>
                  </div>
                `)}
              </div>
            </div>
          `)}
        </div>
      </div>
    </section>
  `;
}

/* Register / Login Modal (Figma Nodes 1:340 & 1:386) */
function AuthModal({ onDone, onCancel, resetInfo, onClearReset }) {
  const [tab, setTab] = useState("register"); // 'register' or 'login'
  const [step, setStep] = useState(resetInfo?.token ? "reset" : "form");

  const [form, setForm] = useState({
    name: "",
    dob: "",
    place: "",
    phone: "",
    email: resetInfo?.email || "",
    password: "",
    confirmPassword: ""
  });

  const [otpCode, setOtpCode] = useState("");
  const [busy, setBusy] = useState(false);
  const [err, setErr] = useState("");
  const [info, setInfo] = useState("");

  const update = field => e => setForm({ ...form, [field]: e.target.value });

  // Registration Submit
  const handleRegister = async e => {
    e?.preventDefault();
    setErr("");
    if (!form.phone.trim() || !form.name.trim() || !form.password) {
      return setErr("ദയവായി ആവശ്യമായ വിവരങ്ങൾ നൽകുക (ഫോൺ, പേര്, പാസ്‌വേഡ്).");
    }
    if (form.password.length < 6) {
      return setErr("പാസ്‌വേഡിന് കുറഞ്ഞത് 6 അക്ഷരങ്ങൾ വേണം.");
    }

    setBusy(true);
    try {
      const u = await api.registerPassword({
        name: form.name.trim(),
        dob: form.dob,
        place: form.place.trim(),
        phone: form.phone.trim(),
        email: form.email.trim(),
        password: form.password
      });
      onDone(u);
    } catch (e) {
      setErr(e.message || ERR[e.code] || ERR.NETWORK);
      setBusy(false);
    }
  };

  // Login Submit
  const handleLogin = async e => {
    e?.preventDefault();
    setErr("");
    const identifier = form.phone.trim() || form.email.trim();
    if (!identifier || !form.password) {
      return setErr("മൊബൈൽ നമ്പർ / ഇമെയിൽ, പാസ്‌വേഡ് എന്നിവ നൽകുക.");
    }

    setBusy(true);
    try {
      const u = await api.loginPassword(identifier, form.password);
      onDone(u);
    } catch (e) {
      setErr(e.message || ERR[e.code] || ERR.NETWORK);
      setBusy(false);
    }
  };

  // Forgot password request
  const handleForgot = async () => {
    setErr("");
    setInfo("");
    if (!form.email.trim()) {
      return setErr("ദയവായി രജിസ്റ്റർ ചെയ്ത ഇമെയിൽ ഐഡി നൽകുക.");
    }
    setBusy(true);
    try {
      const res = await api.forgotPassword(form.email.trim());
      setInfo(res.message || "പാസ്‌വേഡ് റീസെറ്റ് ലിങ്ക് ഇമെയിലിലേക്ക് അയച്ചിട്ടുണ്ട്!");
    } catch (e) {
      setErr(e.message || ERR[e.code] || ERR.NETWORK);
    }
    setBusy(false);
  };

  // Reset password submit
  const handleReset = async () => {
    setErr("");
    if (!form.password || form.password.length < 6) {
      return setErr("പുതിയ പാസ്‌വേഡിന് കുറഞ്ഞത് 6 അക്ഷരങ്ങൾ വേണം.");
    }
    if (form.password !== form.confirmPassword) {
      return setErr("പാസ്‌വേഡുകൾ തമ്മിൽ പൊരുത്തപ്പെടുന്നില്ല.");
    }
    setBusy(true);
    try {
      const u = await api.resetPassword(form.email.trim(), resetInfo.token.trim(), form.password);
      if (onClearReset) onClearReset();
      onDone(u);
    } catch (e) {
      setErr(e.message || ERR[e.code] || ERR.NETWORK);
      setBusy(false);
    }
  };

  return html`
    <div className="auth-wrapper">
      <div className="auth-card">
        <h2 className="auth-card-title">Register/Start Quiz</h2>

        ${step === "form" ? html`
          <div>
            <!-- Tab list -->
            <div className="auth-tab-list" role="tablist">
              <button 
                type="button" 
                className=${"auth-tab-btn " + (tab === "register" ? "active" : "")} 
                onClick=${() => { setTab("register"); setErr(""); setInfo(""); }}>
                Register
              </button>
              <button 
                type="button" 
                className=${"auth-tab-btn " + (tab === "login" ? "active" : "")} 
                onClick=${() => { setTab("login"); setErr(""); setInfo(""); }}>
                Log in
              </button>
            </div>

            <!-- Hint text -->
            <div className="auth-hint-text">
              ${tab === "register" ? html`
                <span>മുൻപ് ഒരു തവണ രജിസ്റ്റർ ചെയ്തതാണെങ്കിൽ </span>
                <span className="auth-hint-link" onClick=${() => { setTab("login"); setErr(""); }}>ലോഗിൻ</span>
                <span> ചെയ്യുക</span>
              ` : html`
                <span>മുൻപ് രജിസ്റ്റർ ചെയ്തിട്ടില്ലെങ്കിൽ നിങ്ങൾക്ക് </span>
                <span className="auth-hint-link" onClick=${() => { setTab("register"); setErr(""); }}>രജിസ്റ്റർ</span>
                <span> ചെയ്യാം</span>
              `}
            </div>

            <!-- Forms -->
            ${tab === "register" ? html`
              <form onSubmit=${handleRegister}>
                <div className="form-group">
                  <label className="form-label" htmlFor="reg_phone">Mobile Number</label>
                  <input 
                    id="reg_phone" 
                    className="form-input" 
                    type="tel" 
                    inputMode="tel"
                    placeholder="10-digit mobile number" 
                    value=${form.phone} 
                    onChange=${update("phone")} 
                    required/>
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="reg_name">Full name</label>
                  <input 
                    id="reg_name" 
                    className="form-input" 
                    type="text" 
                    placeholder="Your full name" 
                    value=${form.name} 
                    onChange=${update("name")} 
                    required/>
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="reg_dob">Date of birth</label>
                  <input 
                    id="reg_dob" 
                    className="form-input" 
                    type="date" 
                    value=${form.dob} 
                    onChange=${update("dob")}/>
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="reg_place">Place</label>
                  <input 
                    id="reg_place" 
                    className="form-input" 
                    type="text" 
                    placeholder="e.g. Kozhikode / Dubai" 
                    value=${form.place} 
                    onChange=${update("place")}/>
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="reg_email">Email</label>
                  <input 
                    id="reg_email" 
                    className="form-input" 
                    type="email" 
                    placeholder="yourname@gmail.com" 
                    value=${form.email} 
                    onChange=${update("email")}
                    required/>
                  <div className="form-subtext">
                    പാസ്സ്‌വേർഡ് മറന്നു പോയാൽ ഈ ഇമെയിലിലേക്കായിരിക്കും അയക്കുക
                  </div>
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="reg_pass">Password</label>
                  <input 
                    id="reg_pass" 
                    className="form-input" 
                    type="password" 
                    placeholder="Create a password (min 6 chars)" 
                    value=${form.password} 
                    onChange=${update("password")} 
                    required/>
                  <div className="form-subtext highlight">
                    പാസ്സ്‌വേർഡ് കുറിച്ച് വെക്കുമല്ലോ.... അടുത്ത ആഴ്ച ക്വിസിൽ പങ്കെടുക്കാൻ ലോഗിൻ ചെയ്‌യുമ്പോൾ ആവശ്യം വരും
                  </div>
                </div>

                ${err && html`<${Err} code=${err}/>`}
                ${info && html`<div className="alert-box ok">${info}</div>`}

                <button type="submit" className="auth-btn-pill primary" disabled=${busy}>
                  ${busy ? "Submitting…" : "Submit and Start Quiz"}
                </button>
              </form>
            ` : html`
              <form onSubmit=${handleLogin}>
                <div className="form-group">
                  <label className="form-label" htmlFor="login_ident">Mobile Number / Email</label>
                  <input 
                    id="login_ident" 
                    className="form-input" 
                    type="text" 
                    placeholder="Registered phone or email" 
                    value=${form.phone} 
                    onChange=${update("phone")} 
                    required/>
                </div>

                <div className="form-group">
                  <label className="form-label" htmlFor="login_pass">Password</label>
                  <input 
                    id="login_pass" 
                    className="form-input" 
                    type="password" 
                    placeholder="Your password" 
                    value=${form.password} 
                    onChange=${update("password")} 
                    required/>
                  <div className="form-subtext">
                    പാസ്സ്‌വേർഡ് മറന്നു പോയെങ്കിൽ <span className="auth-hint-link" style=${{ color: "#303030" }} onClick=${() => { setStep("forgot"); setErr(""); setInfo(""); }}>ഇവിടെ ക്ലിക്ക് ചെയ്യുക</span>
                  </div>
                </div>

                ${err && html`<${Err} code=${err}/>`}
                ${info && html`<div className="alert-box ok">${info}</div>`}

                <button type="submit" className="auth-btn-pill primary" disabled=${busy}>
                  ${busy ? "Logging in…" : "Submit and Start Quiz"}
                </button>
              </form>
            `}
          </div>
        ` : step === "forgot" ? html`
          <div>
            <h3 style=${{ fontSize: 20, marginBottom: 8 }}>Password Reset</h3>
            <p style=${{ fontSize: 14, color: "var(--ink-mute)", marginBottom: 16 }}>
              നിങ്ങളുടെ രജിസ്റ്റർ ചെയ്ത ഇമെയിൽ ഐഡി നൽകുക. പാസ്‌വേഡ് റീസെറ്റ് ചെയ്യാനുള്ള ലിങ്ക് അയക്കുന്നതാണ്.
            </p>
            <div className="form-group">
              <label className="form-label">Email</label>
              <input className="form-input" type="email" placeholder="yourname@gmail.com" value=${form.email} onChange=${update("email")}/>
            </div>
            ${err && html`<${Err} code=${err}/>`}
            ${info && html`<div className="alert-box ok">${info}</div>`}
            <button className="auth-btn-pill primary" onClick=${handleForgot} disabled=${busy}>
              ${busy ? "Sending…" : "Send Reset Link"}
            </button>
            <button className="auth-btn-pill secondary" onClick=${() => { setStep("form"); setErr(""); setInfo(""); }}>
              Back to Login
            </button>
          </div>
        ` : step === "reset" ? html`
          <div>
            <h3 style=${{ fontSize: 20, marginBottom: 8 }}>Set New Password</h3>
            <div className="form-group">
              <label className="form-label">New Password</label>
              <input className="form-input" type="password" value=${form.password} onChange=${update("password")}/>
            </div>
            <div className="form-group">
              <label className="form-label">Confirm Password</label>
              <input className="form-input" type="password" value=${form.confirmPassword} onChange=${update("confirmPassword")}/>
            </div>
            ${err && html`<${Err} code=${err}/>`}
            <button className="auth-btn-pill primary" onClick=${handleReset} disabled=${busy}>
              ${busy ? "Saving…" : "Save Password & Start"}
            </button>
            <button className="auth-btn-pill secondary" onClick=${() => { setStep("form"); onClearReset && onClearReset(); }}>
              Cancel
            </button>
          </div>
        ` : null}

        <button type="button" className="auth-btn-pill secondary" onClick=${onCancel}>
          Back to home
        </button>
      </div>
    </div>
  `;
}

/* Taking the Quiz screen */
function QuizTaking({ user, onFinish, onExit }) {
  const { data, err, loading, retry } = useLoad(() => api.startQuiz(user.id), []);
  const [i, setI] = useState(0);
  const [ans, setAns] = useState([]);
  const [busy, setBusy] = useState(false);
  const [e2, setE2] = useState("");

  if (loading) return html`<div className="wrap quiz-card"><${Skel}/></div>`;
  if (err) return html`
    <div className="wrap">
      <div className="quiz-card" style=${{ textAlign: "center" }}>
        <h2 style=${{ marginBottom: 12 }}>${err === "DONE" ? "ക്വിസ് പൂർത്തിയായി" : err === "CLOSED" ? "ക്വിസ് ക്ലോസ്ഡ് ആണ്" : "ക്ഷമിക്കുക"}</h2>
        <${Err} code=${err} retry=${["NETWORK"].includes(err) ? retry : null}/>
        <button className="auth-btn-pill secondary" style=${{ marginTop: 18 }} onClick=${onExit}>Back to home</button>
      </div>
    </div>
  `;

  const q = data.questions[i];
  const last = i === data.questions.length - 1;
  const sel = ans[i];

  const next = async () => {
    if (!last) return setI(i + 1);
    setBusy(true);
    setE2("");
    try {
      const res = await api.submit(user.id, ans);
      onFinish(res);
    } catch (e) {
      setE2(e.message || ERR[e.code] || "NETWORK");
      setBusy(false);
    }
  };

  return html`
    <div className="wrap">
      <div className="quiz-card">
        <div style=${{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
          <b style=${{ color: "var(--bg-hero)", fontFamily: "Figtree" }}>ചോദ്യം ${i + 1} / ${data.questions.length}</b>
          <span style=${{ color: "var(--ink-mute)", fontSize: 14 }}>Week ${data.week}</span>
        </div>

        <div className="quiz-progress-bar">
          <div className="quiz-progress-fill" style=${{ width: ((i + 1) / data.questions.length * 100) + "%" }}></div>
        </div>

        <h3 className="quiz-question-title">${q.text}</h3>

        <div>
          ${q.options.map((opt, optIdx) => html`
            <button 
              key=${optIdx} 
              className=${"quiz-opt-btn " + (sel === optIdx ? "selected" : "")} 
              onClick=${() => { const a = [...ans]; a[i] = optIdx; setAns(a); }}>
              ${opt}
            </button>
          `)}
        </div>

        ${e2 && html`<${Err} code=${e2}/>`}

        <button 
          className="auth-btn-pill primary" 
          onClick=${next} 
          disabled=${sel === undefined || busy}>
          ${busy ? "സബ്മിറ്റ് ചെയ്യുന്നു…" : last ? "സബ്മിറ്റ് ചെയ്യുക" : "അടുത്ത ചോദ്യം →"}
        </button>
      </div>
    </div>
  `;
}

/* Quiz Results Screen */
function QuizResultView({ r, onHome }) {
  return html`
    <div className="wrap">
      <div className="quiz-card" style=${{ textAlign: "center" }}>
        <div style=${{ color: "var(--ink-mute)", fontSize: 15, fontWeight: 600 }}>ഈ ആഴ്ചയിലെ നിങ്ങളുടെ സ്കോർ</div>
        <div className="score-display">
          ${r.score}<span style=${{ fontSize: 32, opacity: 0.6 }}>/50</span>
        </div>
        <p style=${{ fontSize: 16, color: "var(--ink-dark)", margin: "8px 0 16px" }}>
          5-ൽ <b>${r.correct.filter(Boolean).length}</b> ചോദ്യങ്ങൾക്ക് ശരിയുത്തരം നൽകി. നിങ്ങളുടെ ആകെ പോയിന്റ്: <b>${r.total}</b>.
        </p>

        ${r.eligible ? html`
          <div className="alert-box ok">
            🎉 അഭിനന്ദനങ്ങൾ! 50/50 നേടിയതിനാൽ നിങ്ങൾ ഈ ആഴ്ചയിലെ ലക്കി ഡ്രോയിലേക്ക് തെരഞ്ഞെടുക്കപ്പെട്ടിരിക്കുന്നു!
          </div>
        ` : html`
          <div className="alert-box info">
            50/50 നേടുന്നവർക്കാണ് ലക്കി ഡ്രോയിൽ അവസരം. അടുത്ത ആഴ്ച വീണ്ടും മത്സരിക്കൂ!
          </div>
        `}

        <div style=${{ display: "flex", gap: 8, justifyContent: "center", flexWrap: "wrap", margin: "16px 0" }}>
          ${r.correct.map((c, k) => html`
            <span key=${k} className=${"wk-badge " + (c ? "completed" : "upcoming")}>
              Q${k + 1}: ${c ? "ശരി ✓" : "തെറ്റ് ✕"}
            </span>
          `)}
        </div>

        <button className="auth-btn-pill primary" style=${{ marginTop: 14 }} onClick=${onHome}>
          Leaderboard കാണുക
        </button>
      </div>
    </div>
  `;
}

/* ============ MAIN APPLICATION ============ */
function App() {
  const [view, setView] = useState("home");
  const [user, setUser] = useState(() => {
    if (window.INITIAL_DATA?.user) {
      try { localStorage.setItem('fnq_user', JSON.stringify(window.INITIAL_DATA.user)); } catch(e){}
      return window.INITIAL_DATA.user;
    }
    try {
      const cached = localStorage.getItem('fnq_user');
      return cached ? JSON.parse(cached) : null;
    } catch (e) {
      return null;
    }
  });

  const [res, setRes] = useState(null);
  const [tick, setTick] = useState(0);
  const [resetInfo, setResetInfo] = useState(null);
  const [notice, setNotice] = useState("");
  const now = useNow();

  useEffect(() => {
    api.me().then(serverUser => {
      if (serverUser) {
        setUser(serverUser);
        try { localStorage.setItem('fnq_user', JSON.stringify(serverUser)); } catch(e){}
      } else if (!window.INITIAL_DATA?.user) {
        try { localStorage.removeItem('fnq_user'); } catch(e){}
        setUser(null);
      }
    });
  }, []);

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const token = params.get('reset_token');
    const em = params.get('email');
    if (token && em) {
      setResetInfo({ token, email: em });
      setView("auth");
    }
  }, []);

  const sch = useLoad(() => api.schedule(), [tick]);

  const clearResetParams = () => {
    setResetInfo(null);
    if (window.history.replaceState) {
      window.history.replaceState({}, '', window.location.pathname);
    }
  };

  const scrollTo = id => {
    setView("home");
    setTimeout(() => {
      const el = document.getElementById(id);
      el && el.scrollIntoView({ behavior: 'smooth' });
    }, 60);
  };

  const handleStart = () => {
    const isLive = sch.data && Array.isArray(sch.data) && sch.data.some(w => wkStatus(w, Date.now()) === "live");
    if (!user) {
      clearResetParams();
      setView("auth");
    } else if (isLive) {
      setView("quiz");
    } else {
      scrollTo("schedule");
    }
  };

  const handleAuthDone = u => {
    clearResetParams();
    try { localStorage.setItem('fnq_user', JSON.stringify(u)); } catch(e){}
    setUser(u);
    setTick(t => t + 1);
    const isLive = sch.data && Array.isArray(sch.data) && sch.data.some(w => wkStatus(w, Date.now()) === "live");
    if (isLive) {
      setView("quiz");
    } else {
      setNotice(`സ്വാഗതം, ${u.name ? u.name.split(" ")[0] : "മത്സരാർത്ഥി"}! നിങ്ങളുടെ രജിസ്ട്രേഷൻ വിജയകരമായി പൂർത്തിയായി.`);
      setView("home");
    }
  };

  const handleLogout = async () => {
    try { localStorage.removeItem('fnq_user'); } catch(e){}
    await api.logout();
    setUser(null);
    setNotice("");
    setTick(t => t + 1);
    setView("home");
  };

  return html`
    <div>
      <${Header} onGo=${scrollTo} user=${user} onLogout=${handleLogout}/>

      <main>
        ${notice && view === "home" && html`
          <div className="wrap" style=${{ paddingTop: 16 }}>
            <div className="alert-box ok" style=${{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
              <span>✓ ${notice}</span>
              <button style=${{ background: "none", border: 0, cursor: "pointer", fontSize: 16 }} onClick=${() => setNotice("")}>✕</button>
            </div>
          </div>
        `}

        ${view === "auth" && html`
          <${AuthModal} 
            onCancel=${() => { clearResetParams(); setView("home"); }} 
            resetInfo=${resetInfo} 
            onClearReset=${clearResetParams} 
            onDone=${handleAuthDone}/>
        `}

        ${view === "quiz" && html`
          <${QuizTaking} 
            user=${user} 
            onExit=${() => setView("home")} 
            onFinish=${r => { setRes(r); setTick(t => t + 1); setView("result"); }}/>
        `}

        ${view === "result" && html`
          <${QuizResultView} r=${res} onHome=${() => scrollTo("leaderboard")}/>
        `}

        ${view === "home" && html`
          <div>
            ${sch.loading ? html`
              <div className="hero-section"><div className="wrap"><${Skel}/></div></div>
            ` : sch.err ? html`
              <div className="wrap" style=${{ paddingTop: 24 }}><${Err} code=${sch.err} retry=${sch.retry}/></div>
            ` : html`
              <div>
                <${Hero} 
                  weeks=${sch.data} 
                  now=${now} 
                  onStart=${handleStart} 
                  user=${user} 
                  onGoRules=${() => scrollTo("rules")}/>

                <${WinnerBanner} weeks=${sch.data}/>

                <${WeeklySchedule} weeks=${sch.data} now=${now}/>
              </div>
            `}

            <${Leaderboard} user=${user} tick=${tick}/>
            <${QuizRules}/>
          </div>
        `}
      </main>

      <footer>Friday Night Quiz · Quran Competition Online Award</footer>
    </div>
  `;
}

ReactDOM.createRoot(document.getElementById("root")).render(html`<${App}/>`);
</script>
@endverbatim
</body>
</html>
