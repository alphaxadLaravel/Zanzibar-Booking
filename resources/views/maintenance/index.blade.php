<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ZanzibarBookings — Maintenance</title>
  <style>
    :root {
      --overlay: rgba(6, 9, 14, 0.45);
      --accent: rgba(255, 255, 255, 0.95);
      --glass: rgba(255, 255, 255, 0.06);
      --radius: 14px;
      --shadow: 0 10px 30px rgba(2, 6, 23, 0.6);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color: var(--accent);
      background: #07101b url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat fixed;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 24px;
      overflow: hidden;
    }

    .overlay {
      position: fixed;
      inset: 0;
      background: linear-gradient(180deg, rgba(3, 6, 10, 0.55), rgba(4, 14, 22, 0.6));
      backdrop-filter: blur(3px);
      z-index: 0;
    }

    main {
      position: relative;
      z-index: 1;
      max-width: 650px;
      width: 100%;
      background: rgba(255, 255, 255, 0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 40px 24px;
      backdrop-filter: blur(6px);
      animation: fadeIn 1s ease both;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .title {
      font-weight: 800;
      letter-spacing: 2px;
      margin-bottom: 12px;
      line-height: 1;
      font-size: clamp(32px, 9vw, 64px);
      text-transform: uppercase;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    .subtitle {
      opacity: 0.92;
      font-size: clamp(14px, 2.5vw, 20px);
      margin-bottom: 28px;
      line-height: 1.4;
    }

    .countdown {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 12px;
    }

    .timecard {
      min-width: 80px;
      background: var(--glass);
      padding: 14px 10px;
      border-radius: 12px;
      text-align: center;
      backdrop-filter: blur(6px);
      box-shadow: 0 6px 18px rgba(2, 6, 23, 0.45);
      transition: transform 350ms cubic-bezier(.2, .9, .3, 1);
    }

    .timecard.pop {
      transform: translateY(-10px) scale(1.03);
    }

    .timeval {
      font-weight: 700;
      font-size: clamp(20px, 5vw, 36px);
    }

    .timelabel {
      font-size: 13px;
      opacity: 0.9;
      margin-top: 6px;
    }

    .foot {
      margin-top: 32px;
      font-size: 13px;
      opacity: 0.9;
      line-height: 1.4;
    }

    /* Small devices optimization */
    @media (max-width: 480px) {
      body {
        padding: 16px;
      }

      main {
        padding: 28px 18px;
      }

      .title {
        letter-spacing: 3px;
        font-size: clamp(28px, 10vw, 60px);
      }

      .countdown {
        gap: 8px;
      }

      .timecard {
        min-width: 64px;
        padding: 10px 6px;
      }

      .timeval {
        font-size: clamp(18px, 6vw, 28px);
      }

      .timelabel {
        font-size: 11px;
      }
    }
  </style>
</head>

<body>
  <div class="overlay"></div>

  <main>
    <h1 class="title">MAINTENANCE</h1>
    <p class="subtitle">
      We're upgrading <b>ZanzibarBookings</b> for a better experience.<br>
      We'll be back online very soon!
    </p>

    <div class="countdown" id="countdown">
      <div class="timecard" id="card-days">
        <div class="timeval" id="days">0</div>
        <div class="timelabel">Days</div>
      </div>
      <div class="timecard" id="card-hours">
        <div class="timeval" id="hours">00</div>
        <div class="timelabel">Hours</div>
      </div>
      <div class="timecard" id="card-mins">
        <div class="timeval" id="mins">00</div>
        <div class="timelabel">Minutes</div>
      </div>
      <div class="timecard" id="card-secs">
        <div class="timeval" id="secs">00</div>
        <div class="timelabel">Seconds</div>
      </div>
    </div>

    <div class="foot">
      For urgent matters, contact <strong>support@zanzibarbookings.com</strong>
    </div>
  </main>

  <script>
    (function(){
      const KEY = 'maintenanceTarget_v3';
      const DURATION_MS = 3 * 24 * 60 * 60 * 1000; // 3 days

      function now(){ return new Date().getTime(); }

      let target = localStorage.getItem(KEY);
      if(!target){
        target = now() + DURATION_MS;
        localStorage.setItem(KEY, String(target));
      } else target = Number(target);

      const daysEl = document.getElementById('days');
      const hoursEl = document.getElementById('hours');
      const minsEl = document.getElementById('mins');
      const secsEl = document.getElementById('secs');

      const cards = {
        days: document.getElementById('card-days'),
        hours: document.getElementById('card-hours'),
        mins: document.getElementById('card-mins'),
        secs: document.getElementById('card-secs')
      };

      function pop(card){
        card.classList.add('pop');
        setTimeout(()=>card.classList.remove('pop'), 600);
      }

      function z(n){return String(n).padStart(2,'0');}

      function tick(){
        const diff = target - now();
        if(diff <= 0){
          daysEl.textContent='0'; hoursEl.textContent='00'; minsEl.textContent='00'; secsEl.textContent='00';
          return clearInterval(interval);
        }

        const sec = Math.floor(diff / 1000) % 60;
        const min = Math.floor(diff / (60 * 1000)) % 60;
        const hr = Math.floor(diff / (60 * 60 * 1000)) % 24;
        const day = Math.floor(diff / (24 * 60 * 60 * 1000));

        if(daysEl.textContent !== String(day)) { daysEl.textContent = String(day); pop(cards.days); }
        if(hoursEl.textContent !== z(hr)) { hoursEl.textContent = z(hr); pop(cards.hours); }
        if(minsEl.textContent !== z(min)) { minsEl.textContent = z(min); pop(cards.mins); }
        if(secsEl.textContent !== z(sec)) { secsEl.textContent = z(sec); pop(cards.secs); }
      }

      tick();
      const interval = setInterval(tick, 1000);
    })();
  </script>
</body>

</html>