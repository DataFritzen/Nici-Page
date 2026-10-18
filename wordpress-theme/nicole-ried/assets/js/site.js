// Hero: Alles hängt an der Videozeit – Regen, Aufklaren, Sonnenglühen, Lichtweg und Begriffe.
// Das Video startet nach dem Wolkensturm, läuft in halber Geschwindigkeit einmal durch und bleibt am Ende stehen.
(function () {
  const media = document.querySelector('.hero-media');
  if (!media) return;   // Unterseiten ohne Hero
  const video = media.querySelector('.hero-video');
  const canvas = media.querySelector('.hero-rain');
  const ctx = canvas.getContext('2d');
  const overcast = media.querySelector('.hero-overcast');
  const sunglow = media.querySelector('.hero-sunglow');
  const path = media.querySelector('.hero-path path');
  const claims = [...media.querySelectorAll('.hero-claims li')];
  const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Zeitplan in Sekunden Videozeit
  const T = {
    start: 4.0,                 // vorher ziehen die wilden Wolken über den Berg
    rate: .5,                   // halbe Geschwindigkeit: ruhiger, kein Zeitraffer-Gefühl
    rainEnd: 5.0, clearEnd: 6.6, // bis 5,0 s Regen, bis 6,6 s klart es auf
    pathStart: 6.6, pathEnd: 12.4
  };
  // Punkte in Video-Koordinaten, von unten nach oben: Konflikte klären, Innerlich wachsen, Ziele erreichen
  const VIDEO = { w: 1280, h: 720, posX: .3, posY: .5 };   // muss zu object-position: 30% 50% passen
  const dots = [[400, 600], [360, 500], [330, 400]];
  const sun = [680, 190];
  const svg = media.querySelector('.hero-path'), group = svg.querySelector('g');

  // Anteil der Linienlänge, an dem jeder Punkt liegt – so leuchtet er genau auf, wenn die Linie ihn erreicht
  const L = path.getTotalLength();
  const claimAt = dots.map(([x, y]) => {
    let best = 0, bd = Infinity;
    for (let i = 0; i <= 400; i++) {
      const pt = path.getPointAtLength(L * i / 400), d = Math.hypot(pt.x - x, pt.y - y);
      if (d < bd) { bd = d; best = i / 400; }
    }
    return best;
  });

  // Video-Koordinaten auf die sichtbare Fläche abbilden (wie object-fit: cover)
  function layout() {
    const r = media.getBoundingClientRect();
    const k = Math.max(r.width / VIDEO.w, r.height / VIDEO.h);
    const ox = (r.width - VIDEO.w * k) * VIDEO.posX, oy = (r.height - VIDEO.h * k) * VIDEO.posY;
    svg.setAttribute('viewBox', `0 0 ${r.width} ${r.height}`);
    group.setAttribute('transform', `translate(${ox} ${oy}) scale(${k})`);
    claims.forEach((li, i) => {
      li.style.setProperty('--x', (ox + dots[i][0] * k) + 'px');
      li.style.setProperty('--y', (oy + dots[i][1] * k) + 'px');
    });
    media.style.setProperty('--sun-x', (ox + sun[0] * k) + 'px');
    media.style.setProperty('--sun-y', (oy + sun[1] * k) + 'px');
  }

  const clamp = x => Math.min(1, Math.max(0, x));
  const ease = x => x * x * (3 - 2 * x);

  // Regen als Canvas
  let drops = [], w = 0, h = 0;
  function resize() {
    const r = media.getBoundingClientRect(), dpr = Math.min(devicePixelRatio || 1, 2);
    w = r.width; h = r.height;
    canvas.width = w * dpr; canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    drops = Array.from({ length: Math.round(w * h / 2600) }, () => newDrop(true));
  }
  function newDrop(anywhere) {
    return { x: Math.random() * (w + 100), y: anywhere ? Math.random() * h : -30,
             len: 12 + Math.random() * 20, speed: 9 + Math.random() * 8, a: .15 + Math.random() * .35 };
  }
  function drawRain(intensity) {
    ctx.clearRect(0, 0, w, h);
    if (intensity <= 0) return;
    ctx.lineWidth = 1.1; ctx.lineCap = 'round';
    for (const d of drops) {
      ctx.strokeStyle = 'rgba(210,222,235,' + (d.a * intensity) + ')';
      ctx.beginPath(); ctx.moveTo(d.x, d.y); ctx.lineTo(d.x - d.len * .22, d.y + d.len); ctx.stroke();
      d.x -= d.speed * .22; d.y += d.speed;
      if (d.y > h) Object.assign(d, newDrop(false));
    }
  }

  function render(t) {
    const storm = 1 - ease(clamp((t - T.rainEnd) / (T.clearEnd - T.rainEnd)));   // 1 = Regen, 0 = Sonne
    video.style.filter = storm > 0
      ? `grayscale(${.7 * storm}) saturate(${1 - .55 * storm}) brightness(${1 - .45 * storm}) contrast(${1 - .08 * storm})`
      : '';
    overcast.style.opacity = storm;
    sunglow.style.opacity = ease(clamp((t - T.rainEnd - .4) / 1.6));
    drawRain(storm);
    const p = clamp((t - T.pathStart) / (T.pathEnd - T.pathStart));
    path.style.strokeDashoffset = 1 - p;
    claims.forEach((li, i) => li.classList.toggle('on', p >= claimAt[i]));
  }

  let raf = 0;
  function loop() {
    render(video.currentTime);
    if (!video.paused && !video.ended) raf = requestAnimationFrame(loop);
  }

  function start() {
    resize();
    if (reduceMotion) { video.currentTime = video.duration - .1; render(T.pathEnd); return; }
    video.currentTime = T.start;
    video.playbackRate = T.rate;
    render(T.start);
    video.play().then(() => { raf = requestAnimationFrame(loop); }).catch(() => render(T.pathEnd));
  }
  video.addEventListener('ended', () => render(T.pathEnd));   // letztes Bild bleibt stehen
  addEventListener('resize', () => { resize(); layout(); });
  layout();
  if (video.readyState >= 1) start(); else video.addEventListener('loadedmetadata', start, { once: true });
})();

// Seitenbreite ohne Scrollbalken, damit vollbreite Abschnitte exakt am Raster ausrichten
const setPageWidth = () => document.documentElement.style.setProperty('--page-w', document.documentElement.clientWidth + 'px');
setPageWidth();
addEventListener('resize', setPageWidth);

// Sanftes Einblenden
const reveal = new IntersectionObserver(entries => {
  entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('in'); reveal.unobserve(en.target); } });
}, { threshold: .15 });
document.querySelectorAll('.reveal').forEach(el => reveal.observe(el));
  
