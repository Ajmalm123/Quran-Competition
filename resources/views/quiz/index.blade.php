<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ $csrfToken }}">
<title>Friday Night Quiz</title>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&display=swap" rel="stylesheet">
<style>
:root{--bg:#EEF2F7;--card:#fff;--ink:#10233F;--mute:#5B6B82;--line:#D9E1EC;--navy:#10233F;--gold:#F2B31B;--live:#E4572E;--ok:#177E6F;--okbg:#DDF3EE;--bad:#B3261E;--badbg:#FBE4E2;--hi:#FFF4D6;box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
@media (prefers-color-scheme:dark){:root:not([data-theme="light"]){--bg:#0B1524;--card:#14233A;--ink:#EAF0F8;--mute:#9BADC5;--line:#26374F;--okbg:#0F3A35;--badbg:#4A1E1B;--hi:#3A3216}}
:root[data-theme="dark"]{--bg:#0B1524;--card:#14233A;--ink:#EAF0F8;--mute:#9BADC5;--line:#26374F;--okbg:#0F3A35;--badbg:#4A1E1B;--hi:#3A3216}
*{box-sizing:border-box}html{scroll-padding-top:calc(64px + env(safe-area-inset-top,0px));scroll-behavior:smooth}
body{margin:0;background:var(--bg);color:var(--ink);font:16px/1.5 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif}
h1,h2,h3,.disp{font-family:"Bricolage Grotesque",system-ui,sans-serif;font-weight:800;letter-spacing:-.02em;margin:0}
button,input,select{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--gold);outline-offset:2px}
.wrap{max-width:1040px;margin:0 auto;padding:0 16px}
header{position:sticky;top:0;z-index:5;background:var(--navy);color:#fff}
.bar{display:flex;align-items:center;justify-content:space-between;height:60px}
.logo{display:flex;gap:10px;align-items:center;font-family:"Bricolage Grotesque",sans-serif;font-weight:800;font-size:19px;background:none;border:0;color:#fff;cursor:pointer;padding:0}
.logo i{width:32px;height:32px;border-radius:10px;background:var(--gold);color:var(--navy);display:grid;place-items:center;font-style:normal}
nav{display:flex;gap:4px;align-items:center}nav a,nav button{color:#fff;text-decoration:none;padding:10px 12px;border-radius:10px;background:none;border:0;cursor:pointer;font-size:15px}
nav a:hover,nav button:hover{background:rgba(255,255,255,.12)}
.burger{display:none}
@media(max-width:640px){.burger{display:block}nav{display:none;position:absolute;top:60px;left:0;right:0;flex-direction:column;align-items:stretch;background:var(--navy);padding:8px 16px 16px}nav.open{display:flex}nav a,nav button{padding:14px;text-align:left}}
.hero{background:radial-gradient(circle at 85% 20%,rgba(242,179,27,.35) 0 90px,transparent 91px),radial-gradient(circle at 85% 20%,transparent 0 130px,rgba(255,255,255,.09) 131px 133px,transparent 134px),radial-gradient(circle at 85% 20%,transparent 0 190px,rgba(255,255,255,.06) 191px 193px,transparent 194px),linear-gradient(160deg,#10233F,#1B3A66);color:#fff;padding:36px 0 44px}
.hero h1{font-size:clamp(34px,8vw,60px);line-height:1.02;max-width:11em}
.hero p{max-width:32em;color:#C9D6EA;margin:14px 0 22px}
.status{display:inline-flex;gap:8px;align-items:center;font-weight:700;font-size:14px;padding:6px 12px;border-radius:99px;background:rgba(255,255,255,.14);margin-bottom:14px}
.dot{width:10px;height:10px;border-radius:50%;background:var(--live);animation:pulse 1.4s infinite}
@keyframes pulse{50%{opacity:.35}}@media(prefers-reduced-motion:reduce){.dot{animation:none}html{scroll-behavior:auto}}
.cd{display:inline-block;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:18px;padding:14px 20px;margin-bottom:22px}
.cd small{display:block;color:#C9D6EA}.cd b{font:800 34px "Bricolage Grotesque",sans-serif;font-variant-numeric:tabular-nums}
.row{display:flex;gap:10px;flex-wrap:wrap}
.btn{min-height:52px;padding:0 24px;border-radius:14px;border:2px solid transparent;font-weight:700;cursor:pointer;background:var(--gold);color:#10233F;transition:transform .1s}
.btn:active{transform:scale(.97)}.btn.ghost{background:transparent;border-color:rgba(255,255,255,.5);color:#fff}.btn.line{background:transparent;border-color:var(--line);color:var(--ink)}.btn:disabled{opacity:.55;cursor:not-allowed}.btn.full{width:100%}
section{padding:36px 0}section h2{font-size:28px;margin-bottom:6px}.sub{color:var(--mute);margin:0 0 18px}
.card{background:var(--card);border:1px solid var(--line);border-radius:20px;padding:18px}
.win{display:flex;gap:16px;align-items:center;background:linear-gradient(120deg,var(--hi),var(--card));border-color:var(--gold)}
.win .tr{font-size:44px}.win h3{font-size:24px}
.grid{display:grid;gap:12px;grid-template-columns:repeat(auto-fill,minmax(210px,1fr))}
.wk{display:flex;flex-direction:column;gap:6px}.wk .n{font:800 22px "Bricolage Grotesque"}.wk.live{border:2px solid var(--live)}
.badge{align-self:flex-start;font-size:13px;font-weight:700;padding:3px 10px;border-radius:99px}
.b-up{background:var(--line);color:var(--mute)}.b-live{background:var(--live);color:#fff}.b-done{background:var(--okbg);color:var(--ok)}
.podium{display:grid;grid-template-columns:1fr 1.15fr 1fr;gap:8px;align-items:end;margin-bottom:14px}
.pd{text-align:center;padding:14px 6px;border-radius:18px 18px 8px 8px;color:#10233F}
.pd .r{font:800 30px "Bricolage Grotesque"}.pd .nm{font-weight:700;overflow-wrap:anywhere}.pd small{display:block;opacity:.75}.pd .pt{font:800 22px "Bricolage Grotesque"}
.p1{background:var(--gold);padding-bottom:34px}.p2{background:#C9D3E0;padding-bottom:20px}.p3{background:#E2B58A;padding-bottom:10px}
.tbl{width:100%;border-collapse:collapse}.tbl th{text-align:left;color:var(--mute);font-size:13px;padding:8px 10px}.tbl td{padding:12px 10px;border-top:1px solid var(--line)}.tbl .num{text-align:right;font-weight:700}
.tbl tr.me td{background:var(--hi);font-weight:700}.tbl td small{display:block;color:var(--mute);font-weight:400}
.me-card{margin-top:14px;border:2px solid var(--gold);background:var(--hi);display:flex;gap:16px;align-items:center}.me-card .big{font:800 46px "Bricolage Grotesque"}
.rules{list-style:none;padding:0;margin:0;display:grid;gap:8px;counter-reset:r}.rules li{counter-increment:r;display:flex;gap:12px;background:var(--card);border:1px solid var(--line);border-radius:14px;padding:12px 14px}
.rules li:before{content:counter(r);flex:none;width:28px;height:28px;border-radius:50%;background:var(--navy);color:#fff;display:grid;place-items:center;font-weight:700;font-size:14px}
.pane{max-width:480px;margin:28px auto}.pane h2{font-size:28px}
.tabs{display:flex;background:var(--line);border-radius:14px;padding:4px;margin:16px 0}.tabs button{flex:1;min-height:44px;border:0;border-radius:10px;background:none;font-weight:700;cursor:pointer;color:var(--mute)}.tabs button.on{background:var(--card);color:var(--ink)}
label{display:block;font-weight:600;font-size:14px;margin:12px 0 4px}
input,select{width:100%;min-height:52px;border-radius:12px;border:2px solid var(--line);background:var(--card);padding:0 14px}input:focus,select:focus{border-color:var(--navy)}
.otp{letter-spacing:.6em;text-align:center;font:800 26px "Bricolage Grotesque"}
.alert{border-radius:12px;padding:12px 14px;margin:12px 0;font-size:15px}.al-bad{background:var(--badbg);color:var(--bad)}.al-ok{background:var(--okbg);color:var(--ok)}.al-info{background:var(--line);color:var(--ink)}
.prog{height:8px;background:var(--line);border-radius:9px;overflow:hidden;margin:10px 0 22px}.prog i{display:block;height:100%;background:var(--live);transition:width .3s}
.opt{display:block;width:100%;text-align:left;min-height:56px;padding:12px 16px;margin-bottom:10px;border-radius:14px;border:2px solid var(--line);background:var(--card);cursor:pointer;font-weight:600}
.opt.sel{border-color:var(--navy);background:var(--hi)}
.score{font:800 88px/1 "Bricolage Grotesque";text-align:center}
.center{text-align:center}.empty{text-align:center;color:var(--mute);padding:26px}
.sk{height:64px;border-radius:14px;background:linear-gradient(90deg,var(--line),var(--card),var(--line));background-size:200% 100%;animation:sh 1.2s infinite;margin-bottom:8px}@keyframes sh{to{background-position:-200% 0}}
.link-btn{background:none;border:0;color:var(--ink);text-decoration:underline;cursor:pointer;padding:8px 0;font-size:14px;font-weight:600;opacity:0.85}
.link-btn:hover{opacity:1;color:var(--gold)}
footer{padding:26px 0;color:var(--mute);text-align:center;font-size:14px}
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
const {useState,useEffect,useCallback}=React;
const html=htm.bind(React.createElement);

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
      headers: { 'Accept': 'application/json' }
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
      headers: { 'Accept': 'application/json' }
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
      body: JSON.stringify(body)
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return {
      id: data.id,
      name: data.name,
      territory: data.territory || data.locality,
      phone: data.phone,
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
      body: JSON.stringify({ email, token, password })
    });
    const data = await res.json();
    if (!res.ok) throw { code: data.code || 'NETWORK', message: data.message };
    return data;
  },

  async startQuiz(uid) {
    const res = await fetch('/quiz/api/start', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
      }
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
        }
      });
    } catch(e) {}
  }
};

const ERR = {
  NETWORK: "Can't reach the server. Check your connection and try again.",
  BADPHONE: "Enter a valid 10-digit mobile number.",
  EXISTS: "This mobile number or email is already registered. Use Log in.",
  NOTFOUND: "No account found. Use the Register tab to create an account.",
  EXPIRED: "This verification code or reset link has expired. Request a new one.",
  INVALID: "That code is not correct. Check WhatsApp and try again.",
  INVALID_PASSWORD: "Incorrect password. Please try again.",
  NO_PASSWORD: "No password set for this account. Please use WhatsApp OTP to log in.",
  NO_EMAIL: "No email address linked to this account for password reset.",
  CLOSED: "The quiz is closed right now.",
  DONE: "You have already played this week's quiz."
};

const COUNTRIES = [
  { code: "+91", label: "🇮🇳 India (+91)", name: "India" },
  { code: "+971", label: "🇦🇪 UAE (+971)", name: "United Arab Emirates" },
  { code: "+966", label: "🇸🇦 Saudi Arabia (+966)", name: "Saudi Arabia" },
  { code: "+974", label: "🇶🇦 Qatar (+974)", name: "Qatar" },
  { code: "+968", label: "🇴🇲 Oman (+968)", name: "Oman" },
  { code: "+965", label: "🇰🇼 Kuwait (+965)", name: "Kuwait" },
  { code: "+973", label: "🇧🇭 Bahrain (+973)", name: "Bahrain" },
  { code: "+44", label: "🇬🇧 United Kingdom (+44)", name: "United Kingdom" },
  { code: "+1", label: "🇺🇸 US / Canada (+1)", name: "USA / Canada" },
  { code: "other", label: "🌐 Other Country", name: "International" }
];

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

const Err = ({code, retry}) => html`<div className="alert al-bad" role="alert">${ERR[code] || code || ERR.NETWORK} ${retry && html`<button className="btn line" style=${{minHeight:36,padding:"0 12px",marginLeft:8}} onClick=${retry}>Try again</button>`}</div>`;
const Skel = () => html`<div><div className="sk"/><div className="sk"/><div className="sk"/></div>`;

/* ============ SECTIONS ============ */
function Hero({weeks, onStart, now}) {
  const live = weeks.find(w => wkStatus(w, now) === "live");
  const next = weeks.find(w => wkStatus(w, now) === "upcoming");
  const state = live ? "live" : next ? "upcoming" : "completed";

  return html`<div className="hero"><div className="wrap">
    <div className="status">${live ? html`<span className="dot"/>` : null}${live ? "Quiz is live" : state === "upcoming" ? "Quiz upcoming" : "Season completed"}</div>
    <h1>Answer three. Climb the table.</h1>
    <p>One quiz every week. Get all three questions right to enter the lucky draw, and keep your points for the season leaderboard.</p>
    ${live && html`<div className="cd"><small>Week ${live.id} · closes in</small><b>${hms(live.end - now)}</b></div>`}
    ${!live && next && html`<div className="cd"><small>Next quiz · ${fmt(next.start, {weekday:"long", hour:"numeric", minute:"2-digit"})}</small><b>Starts in ${hms(next.start - now)}</b></div>`}
    <div className="row">
      <button className="btn" onClick=${onStart} disabled=${state === "completed"}>${live ? "Start quiz" : "Register / Start quiz"}</button>
      <a href="#rules"><button className="btn ghost">Quiz rules</button></a>
    </div>
  </div></div>`;
}

function Winner({weeks}) {
  const w = [...weeks].reverse().find(w => w.winner);
  return html`<section style=${{paddingBottom:0}}><div className="wrap"><div className="card win">
    <div className="tr" aria-hidden="true">🏆</div>
    <div>${w ? html`<div className="sub" style=${{margin:0}}>This week's winner · Week ${w.id}</div><h3>${w.winner.name}</h3><div>${w.winner.territory} · Lucky draw, 30/30</div>`
      : html`<h3>No winner yet</h3><div className="sub" style=${{margin:0}}>The first lucky draw winner will appear here.</div>`}</div>
  </div></div></section>`;
}

function Schedule({weeks, now}) {
  return html`<section id="schedule"><div className="wrap"><h2>Weekly quiz schedule</h2><p className="sub">Ten weeks. One quiz each Friday at 8 PM.</p>
    <div className="grid">${weeks.map(w => {
      const s = wkStatus(w, now);
      return html`<div key=${w.id} className=${"card wk " + (s === "live" ? "live" : "")}>
        <span className="n">Week ${w.id}</span><span>${fmt(w.start, {weekday:"long"})}, ${fmt(w.start, {day:"numeric", month:"short"})}</span>
        <span className=${"badge " + (s === "live" ? "b-live" : s === "completed" ? "b-done" : "b-up")}>${s === "live" ? "Live now" : s === "completed" ? "Completed" : "Upcoming"}</span>
        ${s === "completed" ? html`<div><small className="sub">Weekly winner · 1st place</small><div><b>${w.winner ? w.winner.name : "To be announced"}</b></div></div>`
          : s === "upcoming" ? html`<small className="sub">Coming soon</small>`
          : html`<small className="sub">Open until ${fmt(w.end, {hour:"numeric", minute:"2-digit"})}</small>`}
      </div>`;
    })}</div>
  </div></section>`;
}

function Points({user, tick}) {
  const {data, err, loading, retry} = useLoad(() => api.leaderboard(user && user.id), [user && user.id, tick]);
  const top = data && data.top;
  const me = data && data.me;
  const inTop = me && me.rank <= 10;

  return html`<section id="points"><div className="wrap"><h2>Points table</h2><p className="sub">Total points across all completed weeks.</p>
    ${loading ? html`<${Skel}/>` : err ? html`<${Err} code=${err} retry=${retry}/>` : !top || !top.length ? html`<div className="card empty">No scores yet. Play this week's quiz to be first on the table.</div>` : html`<div>
      <div className="podium">${[1, 0, 2].map(i => top[i] && html`<div key=${i} className=${"pd p" + (i + 1)}><div className="r">${i + 1}</div><div className="nm">${top[i].name}</div><small>${top[i].territory}</small><div className="pt">${top[i].points}</div></div>`)}</div>
      <div className="card" style=${{padding:6, overflowX:"auto"}}><table className="tbl"><thead><tr><th>Rank</th><th>Contestant</th><th className="num">Points</th></tr></thead><tbody>
        ${top.slice(3).map(u => html`<tr key=${u.id} className=${user && u.id === user.id ? "me" : ""}><td>${u.rank}</td><td>${u.name}${user && u.id === user.id ? " (you)" : ""}<small>${u.territory}</small></td><td className="num">${u.points}</td></tr>`)}</tbody></table></div>
      ${user && me && !inTop && html`<div className="card me-card"><div className="big">${me.rank}</div><div><small className="sub">Your position</small><div><b>${me.name}</b> · ${me.territory}</div><div>${me.points} points</div></div></div>`}
      ${user && !me && html`<div className="alert al-info">You are not ranked yet. Play a quiz to get on the table.</div>`}
    </div>`}
  </div></section>`;
}

const RULES = [
  "The quiz is held once every week.",
  "Each weekly quiz has 6 questions in the pool.",
  "Each participant gets 3 randomly selected questions.",
  "Each correct answer is worth 10 points.",
  "You can earn up to 30 points in a week.",
  "Weekly points are added to your total score.",
  "You can play once per week.",
  "Indian participants verify via WhatsApp OTP; international participants use password.",
  "Score 30 out of 30 to enter the weekly lucky draw.",
  "The lucky draw winner is picked by the organiser's system.",
  "The leaderboard ranks total points from completed quizzes.",
  "The organiser may change schedules or rules when needed."
];

const Rules = () => html`<section id="rules"><div className="wrap"><h2>Quiz rules</h2><p className="sub">Short and simple.</p><ol className="rules">${RULES.map((r, i) => html`<li key=${i}>${r}</li>`)}</ol></div></section>`;

/* ============ AUTH & PASSWORD RESET ============ */
function Auth({onDone, onCancel, resetInfo}) {
  const [mode, setMode] = useState("register");
  const [step, setStep] = useState(resetInfo?.token ? "reset" : "form");
  const [f, setF] = useState({
    name: "",
    age: "",
    locality: "",
    territory: "Kerala",
    country: "+91",
    phone: "",
    email: resetInfo?.email || "",
    password: "",
    confirmPassword: ""
  });
  const [code, setCode] = useState(resetInfo?.token || "");
  const [busy, setBusy] = useState(false);
  const [err, setErr] = useState("");
  const [info, setInfo] = useState("");
  const [wait, setWait] = useState(0);

  const isIndia = f.country === "+91";

  useEffect(() => {
    if (wait <= 0) return;
    const t = setTimeout(() => setWait(wait - 1), 1000);
    return () => clearTimeout(t);
  }, [wait]);

  const set = k => e => setF({ ...f, [k]: e.target.value });

  // Update territory suggestion when country changes
  const onCountryChange = e => {
    const c = e.target.value;
    const cObj = COUNTRIES.find(x => x.code === c);
    setF({
      ...f,
      country: c,
      territory: c === "+91" ? "Kerala" : (cObj ? cObj.name : "International")
    });
    setErr("");
  };

  // Indian OTP send
  const send = async () => {
    setErr("");
    if (mode === "register" && (!f.name.trim() || !f.age || !f.locality.trim() || !f.territory.trim())) {
      return setErr("Fill in all fields to continue.");
    }
    setBusy(true);
    try {
      await api.sendOtp(f.phone.trim(), mode === "register");
      setStep("otp");
      setWait(30);
      setCode("");
    } catch (e) {
      setErr(e.message || ERR[e.code] || "NETWORK");
    }
    setBusy(false);
  };

  // Indian OTP verify
  const verify = async () => {
    setErr("");
    setBusy(true);
    try {
      const u = await api.verifyOtp(
        f.phone.trim(),
        code,
        mode === "register" ? { name: f.name.trim(), age: +f.age, locality: f.locality.trim(), territory: f.territory.trim() } : null
      );
      onDone(u);
    } catch (e) {
      setErr(e.message || ERR[e.code] || "NETWORK");
      setBusy(false);
    }
  };

  // International Password Register
  const registerWithPassword = async () => {
    setErr("");
    if (!f.name.trim() || !f.age || !f.locality.trim() || !f.territory.trim() || !f.phone.trim() || !f.email.trim() || !f.password) {
      return setErr("Fill in all fields including email and password.");
    }
    if (f.password.length < 6) {
      return setErr("Password must be at least 6 characters.");
    }
    if (f.password !== f.confirmPassword) {
      return setErr("Passwords do not match.");
    }
    setBusy(true);
    try {
      const u = await api.registerPassword({
        name: f.name.trim(),
        age: +f.age,
        locality: f.locality.trim(),
        territory: f.territory.trim(),
        country_code: f.country === "other" ? "+0" : f.country,
        phone: f.phone.trim(),
        email: f.email.trim(),
        password: f.password
      });
      onDone(u);
    } catch (e) {
      setErr(e.message || ERR[e.code] || "NETWORK");
      setBusy(false);
    }
  };

  // Password Login (International or Indian with password)
  const loginWithPassword = async () => {
    setErr("");
    if (!f.phone.trim() || !f.password) {
      return setErr("Enter your mobile number/email and password.");
    }
    setBusy(true);
    try {
      const u = await api.loginPassword(f.phone.trim(), f.password, f.country === "other" ? "" : f.country);
      onDone(u);
    } catch (e) {
      setErr(e.message || ERR[e.code] || "NETWORK");
      setBusy(false);
    }
  };

  // Forgot password request
  const submitForgot = async () => {
    setErr("");
    setInfo("");
    if (!f.email.trim()) {
      return setErr("Please enter your registered email address.");
    }
    setBusy(true);
    try {
      const res = await api.forgotPassword(f.email.trim());
      setInfo(res.message || "Password reset link sent to your email!");
    } catch (e) {
      setErr(e.message || ERR[e.code] || "NETWORK");
    }
    setBusy(false);
  };

  // Reset password submission from email link
  const submitReset = async () => {
    setErr("");
    if (!f.password || f.password.length < 6) {
      return setErr("Password must be at least 6 characters.");
    }
    if (f.password !== f.confirmPassword) {
      return setErr("Passwords do not match.");
    }
    setBusy(true);
    try {
      const u = await api.resetPassword(f.email.trim(), code.trim(), f.password);
      if (window.history.replaceState) {
        window.history.replaceState({}, '', window.location.pathname);
      }
      onDone(u);
    } catch (e) {
      setErr(e.message || ERR[e.code] || "NETWORK");
      setBusy(false);
    }
  };

  const fld = (k, l, p = {}) => html`<label htmlFor=${k}>${l}</label><input id=${k} value=${f[k]} onChange=${set(k)} ...${p}/>`;
  const demoCode = window.INITIAL_DATA?.demoCode;

  return html`<div className="wrap"><div className="pane card">
    ${step === "form" ? html`
      <h2>${mode === "register" ? "Join the quiz" : "Welcome back"}</h2>
      <div className="tabs" role="tablist">
        <button className=${mode === "register" ? "on" : ""} onClick=${() => { setMode("register"); setErr(""); setInfo(""); }}>Register</button>
        <button className=${mode === "login" ? "on" : ""} onClick=${() => { setMode("login"); setErr(""); setInfo(""); }}>Log in</button>
      </div>

      <label htmlFor="countrySelect">Country / Region</label>
      <select id="countrySelect" value=${f.country} onChange=${onCountryChange}>
        ${COUNTRIES.map(c => html`<option key=${c.code} value=${c.code}>${c.label}</option>`)}
      </select>

      ${mode === "register" ? html`
        <div>
          ${fld("name", "Full name", { autoComplete: "name", placeholder: "Your name" })}
          ${fld("age", "Age", { type: "number", inputMode: "numeric", min: 5, max: 110, placeholder: "e.g. 24" })}
          ${fld("locality", isIndia ? "Locality (Town / Village)" : "City / Locality", { placeholder: isIndia ? "e.g. Kuttichira" : "e.g. Dubai / Riyadh" })}
          ${fld("territory", isIndia ? "District / State" : "Country / Territory", { placeholder: isIndia ? "e.g. Kozhikode" : "e.g. UAE / Saudi Arabia" })}
          ${fld("phone", isIndia ? "WhatsApp number" : "Mobile / WhatsApp number", {
            type: "tel",
            inputMode: "numeric",
            placeholder: isIndia ? "10 digits (without +91)" : "Mobile number without country code",
            autoComplete: "tel"
          })}

          ${!isIndia && html`
            <div>
              ${fld("email", "Email address (for password recovery)", { type: "email", autoComplete: "email", placeholder: "yourname@gmail.com" })}
              ${fld("password", "Create Password (min 6 characters)", { type: "password", autoComplete: "new-password" })}
              ${fld("confirmPassword", "Confirm Password", { type: "password", autoComplete: "new-password" })}
            </div>
          `}
        </div>
      ` : html`
        <div>
          ${fld("phone", isIndia ? "WhatsApp number" : "Mobile number or Email", {
            type: isIndia ? "tel" : "text",
            placeholder: isIndia ? "10 digits" : "Registered mobile number or email",
            autoComplete: "username"
          })}

          ${!isIndia && html`
            <div>
              ${fld("password", "Password", { type: "password", autoComplete: "current-password" })}
              <div style=${{textAlign:"right", marginTop:6}}>
                <button type="button" className="link-btn" onClick=${() => { setStep("forgot"); setErr(""); setInfo(""); }}>Forgot password?</button>
              </div>
            </div>
          `}
        </div>
      `}

      ${isIndia && demoCode ? html`<div className="alert al-info">Demo: Verification code is <b>${demoCode}</b>.</div>` : null}
      ${err && html`<${Err} code=${err}/>`}
      ${info && html`<div className="alert al-ok">${info}</div>`}

      ${isIndia ? html`
        <button className="btn full" style=${{marginTop:12}} onClick=${send} disabled=${busy}>${busy ? "Sending code…" : "Send WhatsApp code"}</button>
      ` : html`
        <button className="btn full" style=${{marginTop:12}} onClick=${mode === "register" ? registerWithPassword : loginWithPassword} disabled=${busy}>
          ${busy ? (mode === "register" ? "Creating account…" : "Logging in…") : (mode === "register" ? "Register & Start Quiz" : "Log in with Password")}
        </button>
      `}
    ` : step === "otp" ? html`
      <h2>Enter your code</h2>
      <p className="sub">We sent a 6-digit code to WhatsApp +91 ${f.phone}.${demoCode ? " Demo code: " + demoCode : ""}</p>
      <input className="otp" inputMode="numeric" maxLength="6" value=${code} onChange=${e => setCode(e.target.value.replace(/\D/g, ""))} aria-label="6-digit code"/>
      ${err && html`<${Err} code=${err}/>`}
      <button className="btn full" style=${{marginTop:12}} onClick=${verify} disabled=${busy || code.length !== 6}>${busy ? "Verifying…" : "Verify and continue"}</button>
      <button className="btn line full" style=${{marginTop:10}} onClick=${send} disabled=${wait > 0 || busy}>${wait > 0 ? "Resend code in " + wait + "s" : "Resend code"}</button>
      <button className="btn line full" style=${{marginTop:10}} onClick=${() => { setStep("form"); setErr(""); }}>Change number</button>
    ` : step === "forgot" ? html`
      <h2>Reset Password</h2>
      <p className="sub">Enter your registered email address. We'll send you a secure link to reset your password.</p>
      ${fld("email", "Email address", { type: "email", placeholder: "yourname@example.com" })}
      ${err && html`<${Err} code=${err}/>`}
      ${info && html`<div className="alert al-ok">${info}</div>`}
      <button className="btn full" style=${{marginTop:12}} onClick=${submitForgot} disabled=${busy}>${busy ? "Sending link…" : "Send Reset Link"}</button>
      <button className="btn line full" style=${{marginTop:10}} onClick=${() => { setStep("form"); setErr(""); setInfo(""); }}>Back to log in</button>
    ` : step === "reset" ? html`
      <h2>Set New Password</h2>
      <p className="sub">Choose a new password for account: <b>${f.email}</b></p>
      ${fld("password", "New Password (min 6 characters)", { type: "password", placeholder: "••••••••" })}
      ${fld("confirmPassword", "Confirm New Password", { type: "password", placeholder: "••••••••" })}
      ${err && html`<${Err} code=${err}/>`}
      <button className="btn full" style=${{marginTop:12}} onClick=${submitReset} disabled=${busy}>${busy ? "Saving password…" : "Save Password & Log in"}</button>
    ` : null}

    <button className="btn line full" style=${{marginTop:10}} onClick=${onCancel}>Back to home</button>
  </div></div>`;
}

/* ============ QUIZ ============ */
function Quiz({user, onFinish, onExit}) {
  const {data, err, loading, retry} = useLoad(() => api.startQuiz(user.id), []);
  const [i, setI] = useState(0);
  const [ans, setAns] = useState([]);
  const [busy, setBusy] = useState(false);
  const [e2, setE2] = useState("");

  if (loading) return html`<div className="wrap pane"><${Skel}/></div>`;
  if (err) return html`<div className="wrap"><div className="pane card">
    <h2>${err === "DONE" ? "Already completed" : err === "CLOSED" ? "Quiz closed" : "Something went wrong"}</h2>
    <${Err} code=${err} retry=${["NETWORK"].includes(err) ? retry : null}/>
    <button className="btn full" onClick=${onExit}>Back to home</button>
  </div></div>`;

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

  return html`<div className="wrap"><div className="pane">
    <div className="row" style=${{justifyContent:"space-between"}}>
      <b>Question ${i + 1} of ${data.questions.length}</b>
      <span className="sub" style=${{margin:0}}>Week ${data.week}</span>
    </div>
    <div className="prog" role="progressbar" aria-valuenow=${i + 1} aria-valuemin="1" aria-valuemax=${data.questions.length}>
      <i style=${{width: ((i + 1) / data.questions.length * 100) + "%"}}/>
    </div>
    <h2 style=${{fontSize:26, marginBottom:18}}>${q.text}</h2>
    ${q.options.map((o, k) => html`<button key=${k} className=${"opt " + (sel === k ? "sel" : "")} onClick=${() => { const a = [...ans]; a[i] = k; setAns(a); }} aria-pressed=${sel === k}>${o}</button>`)}
    ${e2 && html`<${Err} code=${e2}/>`}
    <button className="btn full" onClick=${next} disabled=${sel === undefined || busy}>${busy ? "Submitting…" : last ? "Submit answers" : "Next question"}</button>
  </div></div>`;
}

function Result({r, onHome}) {
  return html`<div className="wrap"><div className="pane card center">
    <small className="sub">Your score this week</small>
    <div className="score">${r.score}<small style=${{fontSize:24, color:"var(--mute)"}}>/30</small></div>
    <p>${r.correct.filter(Boolean).length} of 3 correct. Your total is now <b>${r.total}</b> points.</p>
    ${r.eligible ? html`<div className="alert al-ok">Perfect score. You are in this week's lucky draw.</div>` : html`<div className="alert al-info">Only a perfect 30 enters the lucky draw. Try again next week.</div>`}
    <div className="row" style=${{justifyContent:"center"}}>${r.correct.map((c, k) => html`<span key=${k} className=${"badge " + (c ? "b-done" : "b-up")}>Q${k + 1} ${c ? "correct" : "wrong"}</span>`)}</div>
    <button className="btn full" style=${{marginTop:18}} onClick=${onHome}>See leaderboard</button>
  </div></div>`;
}

/* ============ MAIN APP ============ */
function App() {
  const [view, setView] = useState("home");
  const [user, setUser] = useState(window.INITIAL_DATA?.user || null);
  const [res, setRes] = useState(null);
  const [tick, setTick] = useState(0);
  const [menu, setMenu] = useState(false);
  const [resetInfo, setResetInfo] = useState(null);
  const now = useNow();

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

  const go = (id) => {
    setView("home");
    setMenu(false);
    setTimeout(() => {
      const el = document.getElementById(id);
      el && el.scrollIntoView();
    }, 50);
  };

  const start = () => {
    setView(user ? "quiz" : "auth");
  };

  const handleLogout = async () => {
    setMenu(false);
    await api.logout();
    setUser(null);
    setTick(t => t + 1);
    setView("home");
  };

  return html`<div>
    <header><div className="wrap bar">
      <button className="logo" onClick=${() => go("top")} aria-label="Friday Night Quiz home"><i>?</i>Friday Night Quiz</button>
      <button className="burger btn ghost" style=${{minHeight:40, padding:"0 12px"}} onClick=${() => setMenu(!menu)} aria-expanded=${menu} aria-label="Menu">${menu ? "Close" : "Menu"}</button>
      <nav className=${menu ? "open" : ""}>
        <a href="#top" onClick=${() => { setView("home"); setMenu(false); }}>Home</a>
        <button onClick=${() => go("schedule")}>Schedule</button>
        <button onClick=${() => go("points")}>Leaderboard</button>
        <button onClick=${() => go("rules")}>Rules</button>
        ${user ? html`
          <button onClick=${() => go("points")} aria-label="Your profile">👤 ${user.name.split(" ")[0]}</button>
          <button onClick=${handleLogout} style=${{color:"#ff8888"}} aria-label="Log out">Log out</button>
        ` : null}
      </nav>
    </div></header>

    <main id="top">
      ${view === "auth" && html`<${Auth} onCancel=${() => setView("home")} resetInfo=${resetInfo} onDone=${u => { setUser(u); setView("quiz"); }}/>`}
      ${view === "quiz" && html`<${Quiz} user=${user} onExit=${() => setView("home")} onFinish=${r => { setRes(r); setTick(t => t + 1); setView("result"); }}/>`}
      ${view === "result" && html`<${Result} r=${res} onHome=${() => go("points")}/>`}
      ${view === "home" && html`<div>
        ${sch.loading ? html`<div className="hero"><div className="wrap"><${Skel}/></div></div>` : sch.err ? html`<div className="wrap" style=${{paddingTop:24}}><${Err} code=${sch.err} retry=${sch.retry}/></div>` : html`<div>
          <${Hero} weeks=${sch.data} now=${now} onStart=${start}/>
          <${Winner} weeks=${sch.data}/>
          <${Schedule} weeks=${sch.data} now=${now}/>
        </div>`}
        <${Points} user=${user} tick=${tick}/>
        <${Rules}/>
      </div>`}
    </main>

    <footer>Friday Night Quiz · Quran Competition Online Award</footer>
  </div>`;
}

ReactDOM.createRoot(document.getElementById("root")).render(html`<${App}/>`);
</script>
@endverbatim
</body>
</html>
