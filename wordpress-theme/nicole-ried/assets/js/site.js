// Hero: Alles hängt an der Videozeit – Morgengrauen, Sonnenaufgang, Lichtweg und Begriffe.
// Nach rund 4 Sekunden steht das Bild komplett; das Video läuft danach ruhig weiter und bleibt am Ende stehen.
(function () {
  const media = document.querySelector('.hero-media');
  if (!media) return;   // Unterseiten ohne Hero
  const video = media.querySelector('.hero-video');
  const dawn = media.querySelector('.hero-dawn');
  const sunglow = media.querySelector('.hero-sunglow');
  const path = media.querySelector('.hero-path path');
  const claims = [...media.querySelectorAll('.hero-claims li')];
  const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Zeitplan in Sekunden Videozeit
  const T = {
    start: 4.4,                   // vorher ziehen die wilden Wolken über den Berg
    rate: .6,                     // etwas verlangsamt: ruhig, aber kein Warten
    dawnEnd: 4.7, sunrise: 5.6,   // kurz Morgengrauen, dann wärmt das Licht bis zum Sonnenaufgang auf
    pathStart: 5.0, pathEnd: 6.8  // Lichtweg ab ca. 1 s, nach ca. 4 s stehen alle drei Begriffe
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

  function render(t) {
    const night = 1 - ease(clamp((t - T.dawnEnd) / (T.sunrise - T.dawnEnd)));   // 1 = Morgengrauen, 0 = Sonne
    video.style.filter = night > 0
      ? `saturate(${1 - .65 * night}) brightness(${1 - .5 * night}) contrast(${1 + .05 * night})`
      : '';
    dawn.style.opacity = night;
    sunglow.style.opacity = ease(clamp((t - T.pathStart) / 1.2));
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
    if (reduceMotion) { video.currentTime = video.duration - .1; render(T.pathEnd); return; }
    video.currentTime = T.start;
    video.playbackRate = T.rate;
    render(T.start);
    video.play().then(() => { raf = requestAnimationFrame(loop); }).catch(() => render(T.pathEnd));
  }
  video.addEventListener('ended', () => render(T.pathEnd));   // letztes Bild bleibt stehen
  addEventListener('resize', layout);
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
  
