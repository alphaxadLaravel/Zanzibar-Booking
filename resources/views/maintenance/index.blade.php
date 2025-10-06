<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>MANATINACE — We'll be back</title>
  <style>
    :root{
      --overlay: rgba(6,9,14,0.45);
      --accent: rgba(255,255,255,0.95);
      --glass: rgba(255,255,255,0.06);
      --radius: 14px;
      --shadow: 0 10px 30px rgba(2,6,23,0.6);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;
      color:var(--accent);
      background:#07101b url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat fixed;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      display:flex;align-items:center;justify-content:center;padding:32px;
    }

    /* dark overlay */
    .overlay{
      position:fixed;inset:0;background:linear-gradient(180deg, rgba(3,6,10,0.55), rgba(4,14,22,0.6));backdrop-filter: blur(2px);
      pointer-events:none;z-index:0;
    }

    .card{
      z-index:1;max-width:1100px;width:100%;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      border-radius:var(--radius);box-shadow:var(--shadow);padding:36px;display:grid;grid-template-columns:1fr 420px;gap:28px;align-items:center;
    }

    /* Responsive stack */
    @media (max-width:900px){
      .card{grid-template-columns:1fr; padding:26px}
    }

    .left{
      padding:18px 10px;
    }

    .title{
      font-weight:800;letter-spacing:6px;margin:0 0 8px 0;line-height:0.9;
      font-size: clamp(36px, 10vw, 84px);
      transform-origin:center;animation:pop 1000ms cubic-bezier(.2,.9,.3,1) 200ms both;
    }

    @keyframes pop{
      from{opacity:0; transform:translateY(18px) scale(.98) rotate(-1deg)}
      to{opacity:1; transform:translateY(0) scale(1) rotate(0)}
    }

    .subtitle{opacity:0.92;margin:0 0 18px 0;font-size:clamp(14px,2.2vw,18px)}

    .desc{color:rgba(255,255,255,0.85);margin-bottom:22px;line-height:1.5}

    /* countdown container */
    .countdown{
      display:flex;gap:12px;flex-wrap:wrap;align-items:center
    }

    .timecard{
      min-width:80px;background:var(--glass);padding:12px;border-radius:12px;text-align:center;backdrop-filter: blur(6px);
      box-shadow: 0 6px 18px rgba(2,6,23,0.45);transition:transform 350ms cubic-bezier(.2,.9,.3,1), box-shadow 350ms;
    }
    .timecard.pop{transform:translateY(-10px) scale(1.03);box-shadow:0 18px 40px rgba(2,6,23,0.6)}
    .timeval{font-weight:700;font-size:clamp(18px,3.6vw,32px);letter-spacing:0.6px}
    .timelabel{font-size:12px;opacity:0.9;margin-top:6px}

    /* right side small panel */
    .right{
      display:flex;flex-direction:column;gap:12px;align-items:center;padding:12px
    }
    .panel{
      width:100%;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:18px;border-radius:12px;text-align:center
    }

    .btn{
      display:inline-block;padding:10px 16px;border-radius:10px;background:rgba(255,255,255,0.06);cursor:pointer;border:1px solid rgba(255,255,255,0.06);
      font-weight:600;text-decoration:none;color:var(--accent);transition:transform 220ms, background 220ms
    }
    .btn:hover{transform:translateY(-4px)}

    /* small animated shimmer */
    .shimmer{position:relative;overflow:hidden}
    .shimmer::after{content:"";position:absolute;inset:0;transform:skewX(-18deg) translateX(-120%);background:linear-gradient(90deg, rgba(255,255,255,0.00) 0%, rgba(255,255,255,0.06) 50%, rgba(255,255,255,0.00) 100%);animation:shine 2.2s linear infinite}
    @keyframes shine{to{transform:skewX(-18deg) translateX(220%)}}

    /* footer small */
    .foot{font-size:12px;opacity:0.85;margin-top:8px}

    /* small responsive tweaks */
    @media (max-width:520px){
      .timecard{min-width:64px;padding:10px}
    }

    /* success screen */
    .done{display:none;align-items:center;justify-content:center;flex-direction:column;gap:12px}
    .done.show{display:flex}
  </style>
</head>
<body>
  <div class="overlay"></div>
  <main class="card" role="main" aria-labelledby="m-title">
    <section class="left">
      <h1 id="m-title" class="title">MANATINACE</h1>
      <p class="subtitle">We're doing a quick update. Thanks for your patience — we'll be back very soon.</p>
      <p class="desc">This page will automatically count down the remaining time. The timer is stored in your browser so it continues from the same moment even if you refresh the page.</p>

      <div class="countdown" aria-live="polite" aria-atomic="true" id="countdown">
        <div class="timecard shimmer" id="card-days"><div class="timeval" id="days">0</div><div class="timelabel">Days</div></div>
        <div class="timecard shimmer" id="card-hours"><div class="timeval" id="hours">00</div><div class="timelabel">Hours</div></div>
        <div class="timecard shimmer" id="card-mins"><div class="timeval" id="mins">00</div><div class="timelabel">Minutes</div></div>
        <div class="timecard shimmer" id="card-secs"><div class="timeval" id="secs">00</div><div class="timelabel">Seconds</div></div>
      </div>

      <div class="foot">If you need urgent access, contact <strong>support@example.com</strong></div>
    </section>

    <aside class="right">
      <div class="panel">
        <div style="font-weight:800;font-size:18px;margin-bottom:8px">Maintenance status</div>
        <div id="statusText">Preparing...</div>
        <div style="height:10px"></div>
        <div style="display:flex;gap:8px;justify-content:center">
          <button class="btn" id="refreshBtn">Synchronize</button>
          <a class="btn" id="contactBtn" href="mailto:support@example.com">Contact</a>
        </div>
      </div>

      <div class="panel shimmer">
        <div style="font-weight:700">Progress</div>
        <div id="prog" style="margin-top:10px; height:8px; background:rgba(255,255,255,0.04); border-radius:8px; overflow:hidden">
          <div id="progInner" style="height:100%; width:0%; background: linear-gradient(90deg, rgba(255,255,255,0.08), rgba(255,255,255,0.18)); transition:width 800ms cubic-bezier(.2,.9,.3,1)"></div>
        </div>
        <div class="foot">Auto-starts on first visit and keeps going across refreshes.</div>
      </div>

      <div class="panel done" id="donePanel">
        <div style="font-size:22px;font-weight:800">We're Back 🎉</div>
        <div>Thanks for waiting — the site should be live now.</div>
        <a class="btn" href="/">Go to home</a>
      </div>
    </aside>
  </main>

  <script>
    (function(){
      // key used in localStorage
      const KEY = 'maintenanceTarget_v1';
      const DURATION_MS = 4 * 24 * 60 * 60 * 1000; // 4 days

      function getNow(){ return new Date().getTime(); }

      // Initialize or reuse stored target timestamp so refresh won't restart the timer
      let target = localStorage.getItem(KEY);
      if(!target){
        target = getNow() + DURATION_MS;
        localStorage.setItem(KEY, String(target));
      } else {
        target = Number(target);
        // if stored target is already past, optionally reset to now + DURATION_MS (no, we keep ended state)
      }

      // DOM refs
      const daysEl = document.getElementById('days');
      const hoursEl = document.getElementById('hours');
      const minsEl = document.getElementById('mins');
      const secsEl = document.getElementById('secs');
      const statusText = document.getElementById('statusText');
      const progInner = document.getElementById('progInner');
      const donePanel = document.getElementById('donePanel');
      const cards = {
        days: document.getElementById('card-days'),
        hours: document.getElementById('card-hours'),
        mins: document.getElementById('card-mins'),
        secs: document.getElementById('card-secs')
      };

      // animate small pop whenever a value changes
      function pop(card){
        card.classList.add('pop');
        setTimeout(()=>card.classList.remove('pop'), 700);
      }

      // format helper
      function z(n){return String(n).padStart(2,'0');}

      // main tick
      function tick(){
        const now = getNow();
        let diff = target - now;
        if(diff <= 0){
          // done
          daysEl.textContent = '0'; hoursEl.textContent='00'; minsEl.textContent='00'; secsEl.textContent='00';
          statusText.textContent = 'Completed';
          progInner.style.width = '100%';
          donePanel.classList.add('show');
          return clearInterval(interval);
        }

        // compute time parts
        const sec = Math.floor(diff / 1000) % 60;
        const min = Math.floor(diff / (60*1000)) % 60;
        const hr = Math.floor(diff / (60*60*1000)) % 24;
        const day = Math.floor(diff / (24*60*60*1000));

        // update UI with animation only when values change
        if(daysEl.textContent !== String(day)) { daysEl.textContent = String(day); pop(cards.days)}
        if(hoursEl.textContent !== z(hr)) { hoursEl.textContent = z(hr); pop(cards.hours)}
        if(minsEl.textContent !== z(min)) { minsEl.textContent = z(min); pop(cards.mins)}
        if(secsEl.textContent !== z(sec)) { secsEl.textContent = z(sec); pop(cards.secs)}

        // progress
        const total = DURATION_MS;
        const elapsed = total - diff;
        const pct = Math.min(100, Math.round((elapsed/total)*100));
        progInner.style.width = pct + '%';

        // status line
        statusText.textContent = `Estimated remaining: ${day}d ${z(hr)}h ${z(min)}m ${z(sec)}s`;
      }

      // run first tick immediately
      tick();
      const interval = setInterval(tick, 1000);

      // A small synchronize button to re-read localStorage (useful if you opened in another tab)
      document.getElementById('refreshBtn').addEventListener('click', ()=>{
        const newTarget = localStorage.getItem(KEY);
        if(newTarget){ target = Number(newTarget); statusText.textContent = 'Synchronized with stored timer.'; }
      });

      // Optional: allow clearing storage (for testing) by double-clicking title
      document.getElementById('m-title').addEventListener('dblclick', ()=>{
        if(confirm('Reset the maintenance timer? (for testing)')){
          const newT = getNow() + DURATION_MS; localStorage.setItem(KEY, String(newT)); target = newT; statusText.textContent='Timer reset'; tick();
        }
      });

      // Accessibility: focus contact button if keyboard user
      document.getElementById('contactBtn').addEventListener('focus', ()=>document.getElementById('contactBtn').style.outline='2px solid rgba(255,255,255,0.12)');

      // if user closes the maintenance — remove storage so future visits start anew (not automatically done here).

    })();
  </script>
</body>
</html>
