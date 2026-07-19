/* ── LOGICA DE GIRO CORREGIDA ── */
const flipper = document.getElementById('flipper');

function flipTo(tipo) {
  // Primero quitamos cualquier estado previo
  flipper.classList.remove('flip-left', 'flip-right');
  
  // Forzamos un pequeño "reflow" para que el navegador reinicie la animación si es necesario
  void flipper.offsetWidth; 

  // Aplicamos la clase según el tipo seleccionado
  if (tipo === 'estudiante') {
    flipper.classList.add('flip-left');
  } else if (tipo === 'padrino') {
    flipper.classList.add('flip-right');
  }
}

function flipBack() {
  // Simplemente volvemos al frente quitando las clases de giro
  flipper.classList.remove('flip-left', 'flip-right');
}
/* ════════════════════════════════
   OCEAN BACKGROUND
════════════════════════════════ */
const canvas = document.getElementById('ocean');
const ctx    = canvas.getContext('2d');
const hint   = document.getElementById('hint');

let W, H, time = 0;
let mouse = { x: -9999, y: -9999, vx: 0, vy: 0, px: -9999, py: -9999 };
let isDay = false, transition = 0, transDir = 0;

function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
resize();
window.addEventListener('resize', resize);

window.addEventListener('mousemove', e => {
  mouse.vx = e.clientX - mouse.px; mouse.vy = e.clientY - mouse.py;
  mouse.px = mouse.x; mouse.py = mouse.y;
  mouse.x = e.clientX; mouse.y = e.clientY;
  const cx = getCX(), cy = getCY();
  canvas.style.cursor = Math.hypot(e.clientX-cx, e.clientY-cy) < 48 ? 'pointer' : 'default';
});
window.addEventListener('mouseleave', () => { mouse.x = -9999; mouse.y = -9999; mouse.vx = 0; mouse.vy = 0; });
canvas.addEventListener('click', e => {
  if (Math.hypot(e.clientX-getCX(), e.clientY-getCY()) < 48) {
    isDay = !isDay; transDir = isDay ? 1 : -1;
    hint.style.opacity = '0';
  }
});
setTimeout(() => hint.style.opacity = '0', 5000);

function getCX() { return W * 0.78; }
function getCY() { return H * 0.16; }

const STARS = Array.from({ length: 110 }, () => ({
  x: Math.random(), y: Math.random() * 0.48,
  r: 0.4 + Math.random() * 1.2,
  speed: 0.00004 + Math.random() * 0.00012,
  twinkle: Math.random() * Math.PI * 2,
}));

const CLOUDS = Array.from({ length: 9 }, () => ({
  x: Math.random(), y: 0.04 + Math.random() * 0.35,
  w: 90 + Math.random() * 160, h: 28 + Math.random() * 40,
  speed: 0.00006 + Math.random() * 0.00014,
  alpha: 0.55 + Math.random() * 0.35,
  puffs: Array.from({ length: 4 + Math.floor(Math.random()*3) }, () => ({
    ox: (Math.random()-0.3), oy: (Math.random()-0.5)*0.6, r: 0.3 + Math.random()*0.55,
  })),
}));

function lerp(a, b, t) { return a + (b-a)*t; }

function drawStars(a) {
  if (a < 0.01) return;
  STARS.forEach(s => {
    s.x += s.speed; if (s.x > 1) s.x -= 1;
    const alpha = a * (0.15 + 0.6 * Math.abs(Math.sin(s.twinkle + time * 0.5)));
    ctx.beginPath();
    ctx.arc(s.x * W, s.y * H, s.r, 0, Math.PI*2);
    ctx.fillStyle = `rgba(220,230,255,${alpha})`; ctx.fill();
  });
}

function drawClouds(a) {
  if (a < 0.01) return;
  CLOUDS.forEach(c => {
    c.x += c.speed; if (c.x > 1.3) c.x = -0.3;
    const cx = c.x * W, cy = c.y * H, rx = c.w/2, ry = c.h/2;
    ctx.save(); ctx.globalAlpha = c.alpha * a;
    c.puffs.forEach(p => {
      ctx.beginPath();
      ctx.ellipse(cx + p.ox*rx, cy + p.oy*ry, p.r*rx, p.r*ry*0.9, 0, 0, Math.PI*2);
      ctx.fillStyle = 'rgba(255,255,255,0.82)'; ctx.fill();
    });
    ctx.restore();
  });
}

function drawSky() {
  const stops = [
    { s:0,   n:[2,8,18],   d:[10,100,200] },
    { s:0.4, n:[4,22,40],  d:[30,140,215] },
    { s:0.7, n:[7,29,56],  d:[80,175,225] },
    { s:1,   n:[10,37,72], d:[140,210,235] },
  ];
  const g = ctx.createLinearGradient(0, 0, 0, H * 0.65);
  stops.forEach(({ s, n, d }) => {
    const t = transition;
    g.addColorStop(s, `rgb(${Math.round(lerp(n[0],d[0],t))},${Math.round(lerp(n[1],d[1],t))},${Math.round(lerp(n[2],d[2],t))})`);
  });
  ctx.fillStyle = g; ctx.fillRect(0, 0, W, H);
}

function drawCelestial() {
  const cx = getCX(), cy = getCY(), r = 34, t = transition;
  const gl = ctx.createRadialGradient(cx, cy, r*0.5, cx, cy, r*4.5);
  if (t < 0.5) {
    const a = 1 - t*2;
    gl.addColorStop(0, `rgba(212,170,40,${0.14*a})`);
    gl.addColorStop(0.4, `rgba(200,160,30,${0.06*a})`);
    gl.addColorStop(1, 'rgba(0,0,0,0)');
  } else {
    const a = (t-0.5)*2;
    gl.addColorStop(0, `rgba(255,210,60,${0.30*a})`);
    gl.addColorStop(0.3, `rgba(255,160,20,${0.15*a})`);
    gl.addColorStop(1, 'rgba(0,0,0,0)');
  }
  ctx.fillStyle = gl; ctx.fillRect(cx-r*6, cy-r*6, r*12, r*12);
  ctx.beginPath(); ctx.arc(cx, cy, r, 0, Math.PI*2);
  if (t < 0.5) {
    ctx.fillStyle = '#f0d878'; ctx.fill();
    ctx.save(); ctx.clip();
    ctx.beginPath(); ctx.arc(cx+10, cy-6, r*0.88, 0, Math.PI*2);
    const skyN = `rgb(${Math.round(lerp(2,10,t))},${Math.round(lerp(8,37,t))},${Math.round(lerp(18,72,t))})`;
    ctx.fillStyle = skyN; ctx.fill(); ctx.restore();
  } else {
    const a = (t-0.5)*2;
    const sg = ctx.createRadialGradient(cx, cy, 0, cx, cy, r);
    sg.addColorStop(0, `rgba(255,255,200,${a})`);
    sg.addColorStop(0.5, `rgba(255,220,60,${a})`);
    sg.addColorStop(1, `rgba(255,160,20,${a})`);
    ctx.fillStyle = sg; ctx.fill();
    ctx.save(); ctx.globalAlpha = a * 0.7;
    for (let i = 0; i < 12; i++) {
      const angle = (i/12)*Math.PI*2 + time*0.4;
      ctx.beginPath();
      ctx.moveTo(cx + Math.cos(angle)*(r+6), cy + Math.sin(angle)*(r+6));
      ctx.lineTo(cx + Math.cos(angle)*(r+18+Math.sin(time*2+i)*4), cy + Math.sin(angle)*(r+18+Math.sin(time*2+i)*4));
      ctx.strokeStyle = 'rgba(255,220,80,0.8)'; ctx.lineWidth = 2.5; ctx.lineCap = 'round'; ctx.stroke();
    }
    ctx.restore();
  }
  if (t < 0.8) {
    const ra = 1 - t/0.8;
    for (let y = H*0.56; y < H; y += 4) {
      ctx.beginPath();
      ctx.ellipse(cx + Math.sin(y*0.05+time*0.8)*18, y, 28, 1.5, 0, 0, Math.PI*2);
      ctx.fillStyle = `rgba(220,185,70,${ra*(0.04+0.04*Math.sin(y*0.03+time))})`; ctx.fill();
    }
  }
  if (t > 0.2) {
    const ra = Math.min((t-0.2)/0.5, 1);
    for (let y = H*0.50; y < H; y += 3) {
      ctx.beginPath();
      ctx.ellipse(cx + Math.sin(y*0.04+time)*22, y, 22, 1.2, 0, 0, Math.PI*2);
      ctx.fillStyle = `rgba(255,200,60,${ra*(0.06+0.05*Math.sin(y*0.025+time*1.2))})`; ctx.fill();
    }
  }
}

const LN = [
  { amp:28,freq:0.008,speed:0.35,opacity:0.55,color:[10, 60,120],yOff:0.55 },
  { amp:22,freq:0.012,speed:0.50,opacity:0.50,color:[8,  80,150],yOff:0.60 },
  { amp:18,freq:0.018,speed:0.65,opacity:0.48,color:[12, 90,160],yOff:0.65 },
  { amp:14,freq:0.024,speed:0.80,opacity:0.45,color:[15,100,170],yOff:0.70 },
  { amp:10,freq:0.030,speed:1.00,opacity:0.42,color:[20,110,180],yOff:0.74 },
  { amp: 7,freq:0.040,speed:1.20,opacity:0.38,color:[30,130,190],yOff:0.78 },
  { amp: 5,freq:0.055,speed:1.50,opacity:0.32,color:[50,150,200],yOff:0.81 },
  { amp: 3,freq:0.070,speed:1.80,opacity:0.25,color:[90,175,215],yOff:0.84 },
];
const LD = [
  { amp:28,freq:0.008,speed:0.35,opacity:0.60,color:[0, 100,180],yOff:0.55 },
  { amp:22,freq:0.012,speed:0.50,opacity:0.56,color:[0, 120,200],yOff:0.60 },
  { amp:18,freq:0.018,speed:0.65,opacity:0.52,color:[10,140,210],yOff:0.65 },
  { amp:14,freq:0.024,speed:0.80,opacity:0.50,color:[20,155,220],yOff:0.70 },
  { amp:10,freq:0.030,speed:1.00,opacity:0.46,color:[40,170,225],yOff:0.74 },
  { amp: 7,freq:0.040,speed:1.20,opacity:0.42,color:[70,185,230],yOff:0.78 },
  { amp: 5,freq:0.055,speed:1.50,opacity:0.36,color:[110,200,235],yOff:0.81 },
  { amp: 3,freq:0.070,speed:1.80,opacity:0.28,color:[160,220,240],yOff:0.84 },
];

function mouseInfluence(x, baseY, idx) {
  if (mouse.x < 0) return 0;
  const dist = Math.hypot(x-mouse.x, baseY-mouse.y);
  if (dist > 220) return 0;
  const s = 1 - dist/220;
  return -s*s * Math.min(Math.hypot(mouse.vx,mouse.vy), 30) * 1.2 * (1-idx*0.08);
}

function drawLayer(i) {
  const ln = LN[i], ld = LD[i], t = transition;
  const amp = lerp(ln.amp,ld.amp,t), freq = lerp(ln.freq,ld.freq,t);
  const speed = lerp(ln.speed,ld.speed,t), opacity = lerp(ln.opacity,ld.opacity,t);
  const c = [Math.round(lerp(ln.color[0],ld.color[0],t)),Math.round(lerp(ln.color[1],ld.color[1],t)),Math.round(lerp(ln.color[2],ld.color[2],t))];
  const baseY = H * ln.yOff;
  ctx.beginPath();
  for (let x = 0; x <= W; x += 3) {
    const y = baseY
      + Math.sin(x*freq + time*speed)*amp
      + Math.sin(x*freq*1.7 + time*speed*0.8+1.2)*(amp*0.4)
      + Math.sin(x*freq*0.5 + time*speed*1.3+2.5)*(amp*0.25)
      + mouseInfluence(x, baseY, i);
    x === 0 ? ctx.moveTo(x,y) : ctx.lineTo(x,y);
  }
  ctx.lineTo(W,H); ctx.lineTo(0,H); ctx.closePath();
  const g2 = ctx.createLinearGradient(0, baseY-amp*2, 0, H);
  g2.addColorStop(0, `rgba(${c[0]},${c[1]},${c[2]},${opacity})`);
  g2.addColorStop(1, `rgba(${Math.max(c[0]-10,0)},${Math.max(c[1]-20,0)},${Math.max(c[2]-30,0)},${opacity+0.08})`);
  ctx.fillStyle = g2; ctx.fill();
}

function drawFoam() {
  if (mouse.x < 0) return;
  const spd = Math.hypot(mouse.vx, mouse.vy);
  if (spd < 3) return;
  const top = LN[LN.length-1], baseY = H * top.yOff;
  for (let i = 0; i < 6; i++) {
    const ox = mouse.x + (Math.random()-0.5)*180;
    const oy = baseY + Math.sin(ox*top.freq+time*top.speed)*top.amp + mouseInfluence(ox,baseY,LN.length-1) - 4;
    ctx.beginPath(); ctx.arc(ox, oy, Math.random()*3+1, 0, Math.PI*2);
    ctx.fillStyle = `rgba(200,240,255,${Math.random()*0.4*Math.min(spd/15,1)})`; ctx.fill();
  }
}

function frame() {
  time += 0.016;
  if (transDir !== 0) {
    transition = Math.max(0, Math.min(1, transition + transDir * 0.012));
    if (transition <= 0 || transition >= 1) transDir = 0;
  }
  ctx.clearRect(0, 0, W, H);
  drawSky(); drawStars(1-transition); drawClouds(transition);
  drawCelestial();
  for (let i = 0; i < LN.length; i++) drawLayer(i);
  drawFoam();
  requestAnimationFrame(frame);
}
frame();
 const strengthFill  = document.getElementById('strengthFill');
  const strengthLabel = document.getElementById('strengthLabel');

  const checkStrength = pwd => {
    let score = 0;
    if (pwd.length >= 8)                          score++;
    if (/[A-Z]/.test(pwd))                        score++;
    if (/[0-9]/.test(pwd))                        score++;
    if (/[^A-Za-z0-9]/.test(pwd))                score++;

    const levels = [
      { cls: '',       label: 'Escribe tu contraseña', color: '' },
      { cls: 'weak',   label: 'Débil',                 color: '#f87171' },
      { cls: 'fair',   label: 'Regular',               color: '#fbbf24' },
      { cls: 'good',   label: 'Buena',                 color: '#a78bfa' },
      { cls: 'strong', label: 'Fuerte 💪',             color: '#4ade80' },
    ];

    const lvl = levels[score];
    strengthFill.className  = strength-fill ;{lvl.cls};
    strengthLabel.textContent = lvl.label;
    strengthLabel.style.color  = lvl.color;
    return score;
  };