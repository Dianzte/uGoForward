const canvas = document.getElementById('ocean');
const ctx    = canvas.getContext('2d');
const hint   = document.getElementById('hint');
 
let W, H, time = 0;
let mouse = { x: -9999, y: -9999, vx: 0, vy: 0, px: -9999, py: -9999 };
 
/* ── day/night state ── */
let isDay       = false;
let transition  = 0;   // 0 = full night, 1 = full day
let transDir    = 0;   // -1 going night, 1 going day
 
function resize() {
  W = canvas.width  = window.innerWidth;
  H = canvas.height = window.innerHeight;
}
resize();
window.addEventListener('resize', resize);
 
window.addEventListener('mousemove', e => {
  mouse.vx = e.clientX - mouse.px;
  mouse.vy = e.clientY - mouse.py;
  mouse.px = mouse.x; mouse.py = mouse.y;
  mouse.x  = e.clientX; mouse.y = e.clientY;
 
  /* change cursor when near celestial body */
  const cx = getCelestialX(), cy = getCelestialY(), cr = 34;
  const d  = Math.hypot(e.clientX - cx, e.clientY - cy);
  canvas.style.cursor = d < cr + 14 ? 'pointer' : 'default';
});
window.addEventListener('mouseleave', () => {
  mouse.x = -9999; mouse.y = -9999; mouse.vx = 0; mouse.vy = 0;
});
 
canvas.addEventListener('click', e => {
  const cx = getCelestialX(), cy = getCelestialY();
  if (Math.hypot(e.clientX - cx, e.clientY - cy) < 48) {
    isDay    = !isDay;
    transDir = isDay ? 1 : -1;
    hint.style.opacity = '0';
  }
});
 
/* fade hint after 5s */
setTimeout(() => hint.style.opacity = '0', 5000);
 
function getCelestialX() { return W * 0.78; }
function getCelestialY() { return H * 0.16; }
 
/* ══════════════════════════════
   STARS  (moving parallax)
══════════════════════════════ */
const STARS = Array.from({ length: 110 }, (_, i) => ({
  x:      Math.random(),          // 0-1 normalised
  y:      Math.random() * 0.48,
  r:      0.4 + Math.random() * 1.2,
  speed:  0.00004 + Math.random() * 0.00012,
  phase:  Math.random() * Math.PI * 2,
  twinkle:Math.random() * Math.PI * 2,
}));
 
function drawStars(nightAlpha) {
  if (nightAlpha < 0.01) return;
  STARS.forEach(s => {
    s.x += s.speed;
    if (s.x > 1) s.x -= 1;
    const alpha = nightAlpha * (0.15 + 0.6 * Math.abs(Math.sin(s.twinkle + time * (0.4 + s.speed * 200))));
    ctx.beginPath();
    ctx.arc(s.x * W, s.y * H, s.r, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(220,230,255,${alpha})`;
    ctx.fill();
  });
}
 
/* ══════════════════════════════
   CLOUDS  (moving)
══════════════════════════════ */
const CLOUDS = Array.from({ length: 9 }, (_, i) => ({
  x:     Math.random(),           // 0-1
  y:     0.04 + Math.random() * 0.35,
  w:     90  + Math.random() * 160,
  h:     28  + Math.random() * 40,
  speed: 0.00006 + Math.random() * 0.00014,
  alpha: 0.55 + Math.random() * 0.35,
  puffs: Array.from({ length: 4 + Math.floor(Math.random()*3) }, () => ({
    ox: (Math.random() - 0.3),
    oy: (Math.random() - 0.5) * 0.6,
    r:  0.3 + Math.random() * 0.55,
  })),
}));
 
function drawCloud(c, dayAlpha) {
  if (dayAlpha < 0.01) return;
  const cx = c.x * W, cy = c.y * H;
  const rx = c.w / 2, ry = c.h / 2;
  ctx.save();
  ctx.globalAlpha = c.alpha * dayAlpha;
  c.puffs.forEach(p => {
    ctx.beginPath();
    ctx.ellipse(cx + p.ox * rx, cy + p.oy * ry, p.r * rx, p.r * ry * 0.9, 0, 0, Math.PI*2);
    ctx.fillStyle = 'rgba(255,255,255,0.82)';
    ctx.fill();
  });
  ctx.restore();
}
 
function drawClouds(dayAlpha) {
  if (dayAlpha < 0.01) return;
  CLOUDS.forEach(c => {
    c.x += c.speed;
    if (c.x > 1.3) c.x = -0.3;
    drawCloud(c, dayAlpha);
  });
}
 
/* ══════════════════════════════
   CELESTIAL BODY (moon / sun)
══════════════════════════════ */
function drawCelestial() {
  const cx = getCelestialX(), cy = getCelestialY(), r = 34;
  const t  = transition;
 
  /* glow */
  const glowR  = ctx.createRadialGradient(cx, cy, r * 0.5, cx, cy, r * 4.5);
  if (t < 0.5) {
    /* moon glow — gold */
    const a = 1 - t * 2;
    glowR.addColorStop(0,   `rgba(212,170,40,${0.14 * a})`);
    glowR.addColorStop(0.4, `rgba(200,160,30,${0.06 * a})`);
    glowR.addColorStop(1,   'rgba(0,0,0,0)');
  } else {
    /* sun glow — orange/yellow */
    const a = (t - 0.5) * 2;
    glowR.addColorStop(0,   `rgba(255,210,60,${0.30 * a})`);
    glowR.addColorStop(0.3, `rgba(255,160,20,${0.15 * a})`);
    glowR.addColorStop(1,   'rgba(0,0,0,0)');
  }
  ctx.fillStyle = glowR;
  ctx.fillRect(cx - r*6, cy - r*6, r*12, r*12);
 
  /* body */
  ctx.beginPath();
  ctx.arc(cx, cy, r, 0, Math.PI * 2);
 
  if (t < 0.5) {
    /* moon */
    const a = 1 - t * 2;
    ctx.fillStyle = `rgba(${lerp(255,240,t)},${lerp(215,190,t)},${lerp(120,80,t)},${a + (1-a)*0.0})`;
    ctx.fill();
    /* crescent shadow */
    ctx.save();
    ctx.clip();
    ctx.beginPath();
    ctx.arc(cx + 10, cy - 6, r * 0.88, 0, Math.PI*2);
    ctx.fillStyle = getSkyColor(0);
    ctx.fill();
    ctx.restore();
  } else {
    /* sun */
    const a = (t - 0.5) * 2;
    const sunGrad = ctx.createRadialGradient(cx, cy, 0, cx, cy, r);
    sunGrad.addColorStop(0,   `rgba(255,255,200,${a})`);
    sunGrad.addColorStop(0.5, `rgba(255,220,60,${a})`);
    sunGrad.addColorStop(1,   `rgba(255,160,20,${a})`);
    ctx.fillStyle = sunGrad;
    ctx.fill();
 
    /* sun rays */
    ctx.save();
    ctx.globalAlpha = a * 0.7;
    const rays = 12;
    for (let i = 0; i < rays; i++) {
      const angle = (i / rays) * Math.PI * 2 + time * 0.4;
      const x1 = cx + Math.cos(angle) * (r + 6);
      const y1 = cy + Math.sin(angle) * (r + 6);
      const x2 = cx + Math.cos(angle) * (r + 18 + Math.sin(time * 2 + i) * 4);
      const y2 = cy + Math.sin(angle) * (r + 18 + Math.sin(time * 2 + i) * 4);
      ctx.beginPath();
      ctx.moveTo(x1, y1); ctx.lineTo(x2, y2);
      ctx.strokeStyle = 'rgba(255,220,80,0.8)';
      ctx.lineWidth = 2.5;
      ctx.lineCap = 'round';
      ctx.stroke();
    }
    ctx.restore();
  }
 
  /* moon reflection (night only) */
  if (t < 0.8) {
    const refAlpha = (1 - t / 0.8);
    for (let y = H * 0.56; y < H; y += 4) {
      const waver = Math.sin(y * 0.05 + time * 0.8) * 18;
      const alpha = refAlpha * (0.04 + 0.04 * Math.sin(y * 0.03 + time));
      ctx.beginPath();
      ctx.ellipse(cx + waver, y, 28, 1.5, 0, 0, Math.PI*2);
      ctx.fillStyle = `rgba(220,185,70,${alpha})`; ctx.fill();
    }
  }
  /* sun reflection (day only) */
  if (t > 0.2) {
    const refAlpha = Math.min((t - 0.2) / 0.5, 1);
    for (let y = H * 0.50; y < H; y += 3) {
      const waver = Math.sin(y * 0.04 + time * 1.0) * 22;
      const alpha = refAlpha * (0.06 + 0.05 * Math.sin(y * 0.025 + time * 1.2));
      ctx.beginPath();
      ctx.ellipse(cx + waver, y, 22, 1.2, 0, 0, Math.PI*2);
      ctx.fillStyle = `rgba(255,200,60,${alpha})`; ctx.fill();
    }
  }
}
 
/* ══════════════════════════════
   SKY
══════════════════════════════ */
function lerp(a, b, t) { return a + (b - a) * t; }
 
function getSkyColor(stop) {
  const nightColors = [
    [2,  8, 18],
    [4, 22, 40],
    [7, 29, 56],
    [10, 37, 72],
  ];
  const dayColors = [
    [10, 100, 200],
    [30, 140, 215],
    [80, 175, 225],
    [140, 210, 235],
  ];
  const stops = [0, 0.4, 0.7, 1];
  const idx   = Math.min(Math.floor(stop * 3), 2);
  const n = nightColors[idx], d = dayColors[idx];
  const t = transition;
  return `rgb(${Math.round(lerp(n[0],d[0],t))},${Math.round(lerp(n[1],d[1],t))},${Math.round(lerp(n[2],d[2],t))})`;
}
 
function drawSky() {
  const g = ctx.createLinearGradient(0, 0, 0, H * 0.65);
  const stops = [
    { s: 0,   night: [2,8,18],   day: [10,100,200]  },
    { s: 0.4, night: [4,22,40],  day: [30,140,215]  },
    { s: 0.7, night: [7,29,56],  day: [80,175,225]  },
    { s: 1,   night: [10,37,72], day: [140,210,235] },
  ];
  stops.forEach(({ s, night, day }) => {
    const t = transition;
    const r = Math.round(lerp(night[0], day[0], t));
    const gv= Math.round(lerp(night[1], day[1], t));
    const b = Math.round(lerp(night[2], day[2], t));
    g.addColorStop(s, `rgb(${r},${gv},${b})`);
  });
  ctx.fillStyle = g; ctx.fillRect(0, 0, W, H);
}
 
/* ══════════════════════════════
   OCEAN LAYERS
══════════════════════════════ */
const LAYERS_NIGHT = [
  { amp: 28, freq: 0.008, speed: 0.35, opacity: 0.55, color: [10,  60,120], yOff: 0.55 },
  { amp: 22, freq: 0.012, speed: 0.50, opacity: 0.50, color: [8,   80,150], yOff: 0.60 },
  { amp: 18, freq: 0.018, speed: 0.65, opacity: 0.48, color: [12,  90,160], yOff: 0.65 },
  { amp: 14, freq: 0.024, speed: 0.80, opacity: 0.45, color: [15, 100,170], yOff: 0.70 },
  { amp: 10, freq: 0.030, speed: 1.00, opacity: 0.42, color: [20, 110,180], yOff: 0.74 },
  { amp:  7, freq: 0.040, speed: 1.20, opacity: 0.38, color: [30, 130,190], yOff: 0.78 },
  { amp:  5, freq: 0.055, speed: 1.50, opacity: 0.32, color: [50, 150,200], yOff: 0.81 },
  { amp:  3, freq: 0.070, speed: 1.80, opacity: 0.25, color: [90, 175,215], yOff: 0.84 },
];
const LAYERS_DAY = [
  { amp: 28, freq: 0.008, speed: 0.35, opacity: 0.60, color: [0,  100,180], yOff: 0.55 },
  { amp: 22, freq: 0.012, speed: 0.50, opacity: 0.56, color: [0,  120,200], yOff: 0.60 },
  { amp: 18, freq: 0.018, speed: 0.65, opacity: 0.52, color: [10, 140,210], yOff: 0.65 },
  { amp: 14, freq: 0.024, speed: 0.80, opacity: 0.50, color: [20, 155,220], yOff: 0.70 },
  { amp: 10, freq: 0.030, speed: 1.00, opacity: 0.46, color: [40, 170,225], yOff: 0.74 },
  { amp:  7, freq: 0.040, speed: 1.20, opacity: 0.42, color: [70, 185,230], yOff: 0.78 },
  { amp:  5, freq: 0.055, speed: 1.50, opacity: 0.36, color: [110,200,235], yOff: 0.81 },
  { amp:  3, freq: 0.070, speed: 1.80, opacity: 0.28, color: [160,220,240], yOff: 0.84 },
];
 
function mouseInfluence(x, baseY, idx) {
  if (mouse.x < 0) return 0;
  const dist = Math.hypot(x - mouse.x, baseY - mouse.y);
  if (dist > 220) return 0;
  const s = 1 - dist / 220;
  const spd = Math.hypot(mouse.vx, mouse.vy);
  return -s * s * Math.min(spd, 30) * 1.2 * (1 - idx * 0.08);
}
 
function blendColor(n, d, t) {
  return [
    Math.round(lerp(n[0], d[0], t)),
    Math.round(lerp(n[1], d[1], t)),
    Math.round(lerp(n[2], d[2], t)),
  ];
}
 
function drawLayer(idx) {
  const ln = LAYERS_NIGHT[idx], ld = LAYERS_DAY[idx];
  const t  = transition;
  const amp     = lerp(ln.amp, ld.amp, t);
  const freq    = lerp(ln.freq, ld.freq, t);
  const speed   = lerp(ln.speed, ld.speed, t);
  const opacity = lerp(ln.opacity, ld.opacity, t);
  const color   = blendColor(ln.color, ld.color, t);
  const yOff    = ln.yOff;
  const baseY   = H * yOff;
  const [r, g, b] = color;
 
  ctx.beginPath();
  for (let x = 0; x <= W; x += 3) {
    const y = baseY
      + Math.sin(x * freq + time * speed) * amp
      + Math.sin(x * freq * 1.7 + time * speed * 0.8 + 1.2) * (amp * 0.4)
      + Math.sin(x * freq * 0.5 + time * speed * 1.3 + 2.5) * (amp * 0.25)
      + mouseInfluence(x, baseY, idx);
    x === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
  }
  ctx.lineTo(W, H); ctx.lineTo(0, H); ctx.closePath();
  const g2 = ctx.createLinearGradient(0, baseY - amp * 2, 0, H);
  g2.addColorStop(0, `rgba(${r},${g},${b},${opacity})`);
  g2.addColorStop(1, `rgba(${Math.max(r-10,0)},${Math.max(g-20,0)},${Math.max(b-30,0)},${opacity+0.08})`);
  ctx.fillStyle = g2; ctx.fill();
}
 
function drawFoam() {
  if (mouse.x < 0) return;
  const spd = Math.hypot(mouse.vx, mouse.vy);
  if (spd < 3) return;
  const top   = LAYERS_NIGHT[LAYERS_NIGHT.length - 1];
  const baseY = H * top.yOff;
  for (let i = 0; i < 6; i++) {
    const ox = mouse.x + (Math.random() - 0.5) * 180;
    const oy = baseY + Math.sin(ox * top.freq + time * top.speed) * top.amp
             + mouseInfluence(ox, baseY, LAYERS_NIGHT.length - 1) - 4;
    ctx.beginPath();
    ctx.arc(ox, oy, Math.random() * 3 + 1, 0, Math.PI*2);
    ctx.fillStyle = `rgba(200,240,255,${Math.random() * 0.4 * Math.min(spd/15, 1)})`;
    ctx.fill();
  }
}
 
/* ══════════════════════════════
   MAIN LOOP
══════════════════════════════ */
function frame() {
  time += 0.016;
 
  /* smooth transition */
  if (transDir !== 0) {
    transition = Math.max(0, Math.min(1, transition + transDir * 0.012));
    if (transition <= 0 || transition >= 1) transDir = 0;
  }
 
  ctx.clearRect(0, 0, W, H);
  drawSky();
  drawStars(1 - transition);
  drawClouds(transition);
  drawCelestial();
  for (let i = 0; i < LAYERS_NIGHT.length; i++) drawLayer(i);
  drawFoam();
 
  requestAnimationFrame(frame);
}
 
frame();