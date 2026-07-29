/* ===================================
   UGF ScholarShip — main.js
   Ocean animation + scroll immersion
   =================================== */

/* ─────────────────────────────────────
   1. OCEAN CANVAS — Animated waves
   ───────────────────────────────────── */
const canvas  = document.getElementById('oceanCanvas');
const ctx     = canvas.getContext('2d');

let W, H, tick = 0;

function resizeCanvas() {
  W = canvas.width  = window.innerWidth;
  H = canvas.height = window.innerHeight;
}

resizeCanvas();
window.addEventListener('resize', resizeCanvas);

/* Wave layer config */
const waveLayers = [
  { amp: 28, period: 0.008, speed: 0.018, yBase: 0.54, alpha: 0.22, color: '#1a6ea0' },
  { amp: 20, period: 0.012, speed: 0.024, yBase: 0.56, alpha: 0.28, color: '#1a85b8' },
  { amp: 16, period: 0.018, speed: 0.032, yBase: 0.57, alpha: 0.20, color: '#2aa8d8' },
  { amp: 10, period: 0.025, speed: 0.045, yBase: 0.575, alpha: 0.30, color: '#70c8e8' },
  { amp:  6, period: 0.035, speed: 0.065, yBase: 0.58, alpha: 0.22, color: '#aae0f5' },
];

/* Sky gradient — stays consistent */
function drawSky() {
  const grad = ctx.createLinearGradient(0, 0, 0, H * 0.6);
  grad.addColorStop(0,    '#050d1a');
  grad.addColorStop(0.35, '#0a1e38');
  grad.addColorStop(0.7,  '#0d3255');
  grad.addColorStop(1,    '#0e4470');
  ctx.fillStyle = grad;
  ctx.fillRect(0, 0, W, H);
}

/* Stars */
const stars = Array.from({ length: 140 }, () => ({
  x: Math.random(),
  y: Math.random() * 0.5,
  r: Math.random() * 1.2 + 0.2,
  blink: Math.random() * Math.PI * 2,
  speed: Math.random() * 0.02 + 0.005,
}));

function drawStars(t) {
  stars.forEach(s => {
    const alpha = 0.4 + 0.6 * Math.abs(Math.sin(s.blink + t * s.speed));
    ctx.beginPath();
    ctx.arc(s.x * W, s.y * H, s.r, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(220,235,255,${alpha})`;
    ctx.fill();
  });
}

/* Moon */
function drawMoon(t) {
  const mx = W * 0.82;
  const my = H * 0.12;
  const mr = 28;
  /* glow */
  const glow = ctx.createRadialGradient(mx, my, 0, mx, my, mr * 4);
  glow.addColorStop(0,   'rgba(220,210,160,0.18)');
  glow.addColorStop(1,   'transparent');
  ctx.fillStyle = glow;
  ctx.beginPath();
  ctx.arc(mx, my, mr * 4, 0, Math.PI * 2);
  ctx.fill();
  /* moon disc */
  ctx.beginPath();
  ctx.arc(mx, my, mr, 0, Math.PI * 2);
  const moonGrad = ctx.createRadialGradient(mx - 6, my - 6, 2, mx, my, mr);
  moonGrad.addColorStop(0, '#fffbe0');
  moonGrad.addColorStop(1, '#c8b860');
  ctx.fillStyle = moonGrad;
  ctx.fill();
}

/* Moon reflection on water */
function drawMoonReflection(t, waveY) {
  const mx = W * 0.82;
  for (let i = 0; i < 8; i++) {
    const ry = waveY + 18 * i + Math.sin(t * 1.5 + i) * 4;
    const rw = 16 - i * 1.5;
    const alpha = (0.22 - i * 0.025) * Math.max(0, 1 - scrollDepth);
    ctx.beginPath();
    ctx.ellipse(mx + Math.sin(t + i) * 6, ry, rw, 3, 0, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(255,240,160,${alpha})`;
    ctx.fill();
  }
}

/* Single wave path */
function getWavePath(layer, t, offsetY) {
  ctx.beginPath();
  ctx.moveTo(0, H);
  const yCenter = (layer.yBase + offsetY) * H;
  for (let x = 0; x <= W; x += 3) {
    const y = yCenter
      + Math.sin(x * layer.period + t * layer.speed * 60) * layer.amp
      + Math.sin(x * layer.period * 0.6 + t * layer.speed * 40 + 1.2) * layer.amp * 0.5;
    ctx.lineTo(x, y);
  }
  ctx.lineTo(W, H);
  ctx.lineTo(0, H);
  ctx.closePath();
  return yCenter;
}

/* Deep ocean below water line */
function drawDeepOcean(waveY, offsetY) {
  const grad = ctx.createLinearGradient(0, waveY, 0, H);
  const depth = scrollDepth;
  const r1 = Math.round(10 + depth * 0);
  const g1 = Math.round(68 + depth * (-50));
  const b1 = Math.round(130 + depth * (-60));
  const r2 = Math.round(2 + depth * 0);
  const g2 = Math.round(13 + depth * 0);
  const b2 = Math.round(24 + depth * 0);
  grad.addColorStop(0, `rgb(${r1},${g1},${b1})`);
  grad.addColorStop(1, `rgb(${r2},${g2},${b2})`);
  ctx.fillStyle = grad;
  ctx.fillRect(0, waveY, W, H - waveY);
}

/* Foam on wave crest */
function drawFoam(layer, t, offsetY) {
  const yCenter = (layer.yBase + offsetY) * H;
  for (let x = 0; x < W; x += 18) {
    const y = yCenter
      + Math.sin(x * layer.period + t * layer.speed * 60) * layer.amp
      + Math.sin(x * layer.period * 0.6 + t * layer.speed * 40 + 1.2) * layer.amp * 0.5;
    const foamAlpha = 0.15 + 0.1 * Math.sin(x * 0.05 + t * 0.02);
    ctx.beginPath();
    ctx.arc(x, y, 4 + Math.sin(x * 0.04) * 2, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(200,240,255,${foamAlpha})`;
    ctx.fill();
  }
}

/* Main render loop */
let lastTime = 0;
let scrollDepth = 0;  // 0 = surface, 1 = abyss (updated by scroll)
let waveOffsetY  = 0; // shifts waves down as you scroll

function render(now) {
  const dt = (now - lastTime) / 1000;
  lastTime = now;
  tick += dt;

  ctx.clearRect(0, 0, W, H);

  /* Sky shifts to deep blue as you go underwater */
  const skyBlend = Math.min(scrollDepth * 2, 1);
  if (skyBlend < 1) {
    drawSky();
    drawStars(tick);
    drawMoon(tick);
  }

  /* Deep ocean solid fill */
  const deepY = H * (0.54 + waveOffsetY) - 10;
  drawDeepOcean(deepY, waveOffsetY);

  /* Underwater bioluminescence glow at deep levels */
  if (scrollDepth > 0.4) {
    const bioAlpha = (scrollDepth - 0.4) * 0.3;
    for (let i = 0; i < 5; i++) {
      const bx = W * (0.1 + i * 0.2 + Math.sin(tick * 0.3 + i) * 0.05);
      const by = H * (0.5 + Math.sin(tick * 0.2 + i * 1.3) * 0.2);
      const bg = ctx.createRadialGradient(bx, by, 0, bx, by, 80);
      bg.addColorStop(0, `rgba(0,200,180,${bioAlpha})`);
      bg.addColorStop(1, 'transparent');
      ctx.fillStyle = bg;
      ctx.beginPath();
      ctx.arc(bx, by, 80, 0, Math.PI * 2);
      ctx.fill();
    }
  }

  /* Draw wave layers */
  let mainWaveY = H * 0.55;
  waveLayers.forEach((layer, i) => {
    ctx.globalAlpha = layer.alpha * (1 - scrollDepth * 0.5);
    mainWaveY = getWavePath(layer, tick, waveOffsetY);
    ctx.fillStyle = layer.color;
    ctx.fill();
    /* foam on top layers */
    if (i >= 3 && scrollDepth < 0.3) drawFoam(layer, tick, waveOffsetY);
  });
  ctx.globalAlpha = 1;

  if (scrollDepth < 0.15) drawMoonReflection(tick, mainWaveY);

  requestAnimationFrame(render);
}

requestAnimationFrame(render);

/* ─────────────────────────────────────
   2. SCROLL — Depth & Ship immersion
   ───────────────────────────────────── */
const ship         = document.getElementById('shipWrapper');
const overlay      = document.getElementById('underwaterOverlay');
const depthMeter   = document.getElementById('depthMeter');
const depthFill    = document.getElementById('depthFill');
const depthValue   = document.getElementById('depthValue');
const bubbles      = document.getElementById('bubblesContainer');
const seaweed      = document.getElementById('seaweedContainer');
const fishCont     = document.getElementById('fishContainer');
const diveTransition = document.getElementById('diveTransition');

let shipSinkDepth = 0;
let shipBobOffset = 0;
let shipTiltAngle = 0;

function updateScroll() {
  const scrollY   = window.scrollY;
  const docH      = document.documentElement.scrollHeight - window.innerHeight;
  const progress  = Math.min(scrollY / docH, 1);
  scrollDepth     = progress;

  /* Wave offset — waves move down then off screen as we sink */
  waveOffsetY = Math.min(progress * 0.8, 0.75);

  /* Underwater overlay — tints the whole page ocean-blue progressively */
  const overlayStart = 0.08;
  const overlayMax   = 0.72;
  if (progress > overlayStart) {
    const p     = (progress - overlayStart) / (1 - overlayStart);
    const r     = Math.round(2  + p * 0);
    const g     = Math.round(20 + p * 10);
    const b     = Math.round(60 + p * (-40));
    const alpha = Math.min(p * overlayMax, overlayMax);
    overlay.style.background = `rgba(${r},${g},${b},${alpha})`;
  } else {
    overlay.style.background = 'transparent';
  }


  /* Bubbles — appear when underwater */
  if (progress > 0.12) {
    bubbles.classList.add('visible');
  } else {
    bubbles.classList.remove('visible');
  }

  /* Seaweed — appear at mid depth */
  if (progress > 0.35) {
    seaweed.classList.add('visible');
  } else {
    seaweed.classList.remove('visible');
  }

  /* Fish — appear at medium depth */
  if (progress > 0.25) {
    fishCont.classList.add('visible');
  } else {
    fishCont.classList.remove('visible');
  }

  /* Ship position and sink */
  updateShip(progress, scrollY);
}

/* ─────────────────────────────────────
   3. SHIP ANIMATION (Flotación Natural)
   ───────────────────────────────────── */
let shipAnimTick = 0;
let lastShipTime = 0;

function animateShip(now) {
  const dt = (now - lastShipTime) / 1000;
  lastShipTime = now;
  shipAnimTick += dt;

  // El balanceo rítmico
  const bobIntensity = 1; 
  const shipBobOffset = Math.sin(shipAnimTick * 1.8) * 8 * bobIntensity;
  const shipTiltAngle = Math.sin(shipAnimTick * 1.8 + 0.4) * 3 * bobIntensity;

  updateShip(shipBobOffset, shipTiltAngle);
  requestAnimationFrame(animateShip);
}

requestAnimationFrame(animateShip);

function updateShip(bob, tilt) {
  // Aplicamos el movimiento usando translateY para no interferir con el scroll
  // El translateX(-50%) es para mantenerlo centrado si así lo tienes en CSS
  ship.style.transform = `translateX(-50%) translateY(${bob}px) rotate(${tilt}deg)`;
  
  // Aseguramos que las propiedades de visibilidad sean constantes
  ship.style.opacity = "1";
  ship.style.filter = "none";
}
/* ─────────────────────────────────────
   4. BUBBLES — Dynamic spawn
   ───────────────────────────────────── */
function spawnBubble() {
  const el   = document.createElement('div');
  el.className = 'bubble';
  const size = Math.random() * 14 + 4;
  const x    = Math.random() * 100;
  const dur  = Math.random() * 8 + 5;
  const delay = Math.random() * 4;
  el.style.cssText = `
    width:${size}px; height:${size}px;
    left:${x}%; bottom:${Math.random()*20}%;
    animation-duration:${dur}s;
    animation-delay:${delay}s;
  `;
  bubbles.appendChild(el);
  /* Remove after a few cycles */
  setTimeout(() => el.remove(), (dur + delay) * 1000 * 2);
}

/* Seed initial bubbles */
for (let i = 0; i < 20; i++) spawnBubble();
/* Keep spawning */
setInterval(() => { if (bubbles.classList.contains('visible')) spawnBubble(); }, 800);

/* ─────────────────────────────────────
   5. SEAWEED — Spawn along bottom
   ───────────────────────────────────── */
const seaweedColors = [
  ['#1a5c2a','#2d8a45'],
  ['#14503d','#248a6a'],
  ['#0e3d55','#1a7a88'],
  ['#2a5c14','#4a8a22'],
];

for (let i = 0; i < 18; i++) {
  const sw = document.createElement('div');
  sw.className = 'seaweed';
  const h = Math.random() * 80 + 60;
  const swayDur = Math.random() * 2 + 2;
  const col = seaweedColors[Math.floor(Math.random() * seaweedColors.length)];
  sw.style.cssText = `
    height:${h}px;
    background: linear-gradient(to top, ${col[0]}, ${col[1]}, ${col[0]});
    animation-duration:${swayDur}s;
    animation-delay:${Math.random() * 2}s;
    width:${Math.random() * 10 + 12}px;
    opacity:${0.7 + Math.random() * 0.3};
  `;
  seaweed.appendChild(sw);
}



/* ─────────────────────────────────────
   7. NAVBAR scroll effect
   ───────────────────────────────────── */
const navbar = document.getElementById('navbar');

window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 40);
  updateScroll();
}, { passive: true });

/* Initial call */
updateScroll();

/* ─────────────────────────────────────
   8. MOBILE MENU
   ───────────────────────────────────── */
const burger     = document.getElementById('burger');
const mobileMenu = document.getElementById('mobileMenu');

burger.addEventListener('click', () => mobileMenu.classList.toggle('open'));
mobileMenu.querySelectorAll('a').forEach(a => {
  a.addEventListener('click', () => mobileMenu.classList.remove('open'));
});



/* ─────────────────────────────────────
   9. REVEAL ON SCROLL
   ───────────────────────────────────── */
const revealEls = document.querySelectorAll('[data-reveal]');

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    const el    = entry.target;
    const delay = parseInt(el.dataset.delay || 0);
    setTimeout(() => el.classList.add('revealed'), delay);
    revealObserver.unobserve(el);
  });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

/* Stagger children in grids */
document.querySelectorAll('.services-grid .service-card, .mvv-cards .mvv-card').forEach((el, i) => {
  el.dataset.delay = i * 90;
});

revealEls.forEach(el => revealObserver.observe(el));

/* ─────────────────────────────────────
   10. COUNTERS
   ───────────────────────────────────── */
function animateCount(el, target, duration = 1600) {
  const start = performance.now();
  const isK   = target >= 1000;
  function step(now) {
    const p = Math.min((now - start) / duration, 1);
    const e = 1 - Math.pow(1 - p, 3);
    const v = Math.round(e * target);
    el.textContent = isK ? (v / 1000).toFixed(v < 1000 ? 1 : 0) + 'K' : v;
    if (p < 1) requestAnimationFrame(step);
    else el.textContent = isK ? (target / 1000).toFixed(0) + 'K' : target;
  }
  requestAnimationFrame(step);
}

let counted = false;
const counterObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting && !counted) {
      counted = true;
      document.querySelectorAll('.stat-num[data-count]').forEach(el => {
        animateCount(el, parseInt(el.dataset.count));
      });
    }
  });
}, { threshold: 0.5 });

const heroStats = document.querySelector('.hero-stats');
if (heroStats) counterObs.observe(heroStats);

/* ─────────────────────────────────────
   11. MAP INTERACTIVITY — Premium University Modal
   ───────────────────────────────────── */

// === BASE DE DATOS DE UNIVERSIDADES POR DEPARTAMENTO ===
const UNI_DATA = {
  'san-salvador': {
    name: 'San Salvador',
    region: 'Zona Central',
    unis: [
      {
        name: 'UES',
        fullName: 'Universidad de El Salvador',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'La principal institución pública de educación superior del país. Fundada en 1841, ofrece la mayor oferta académica a nivel nacional con el sistema de becas remuneradas más amplio de El Salvador.',
        badges: ['🏛️ Pública', '👨‍🎓 65,000 Estudiantes', '⭐ Fundada 1841', '📅 Beca 2026 Abierta'],
        careers: ['Medicina', 'Ingeniería Industrial', 'Derecho', 'Economía', 'Ciencias Químicas', 'Informática', 'Odontología', 'Arquitectura', 'Química y Farmacia'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:00 PM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['🏥 Clínica Médica Gratuita', '📚 Biblioteca Central 24H', '🍽️ Comedor Universitario', '🚌 Transporte Colectivo', '💻 Laboratorios TI', '🏋️ Deportes', '🎨 Arte y Cultura', '🔬 Centro de Investigación'],
        beca: {
          tipo: 'Beca Remunerada y Exoneración de Escolaridad',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo de 7.0 en bachillerato' },
            { icon: '💰', text: 'Comprobante de necesidad económica' },
            { icon: '📋', text: 'Partida de nacimiento certificada' },
            { icon: '🎯', text: 'Prueba de aptitudes aprobada' },
            { icon: '📆', text: 'Aplicación antes de Enero 2026' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UCA',
        fullName: 'Universidad Centroamericana José Simeón Cañas',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwjqm5gCq_2DYlnuKO0g7HRGDJfUvSqOXhpQ&s',
        youtubeId: null,
        desc: 'Universidad privada de alto prestigio y orientación social. Sus programas de beca de excelencia son reconocidos en todo Centroamérica.',
        badges: ['🎓 Privada', '👨‍🎓 8,000 Estudiantes', '⭐ Alta Calidad Académica', '🌎 Reconocimiento Internacional'],
        careers: ['Ingeniería Informática', 'Administración de Empresas', 'Psicología', 'Comunicaciones', 'Maestría en Derecho', 'Ingeniería Civil', 'Teología'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno', hora: '7:00 AM – 7:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Biblioteca Especializada', '💻 Campus Digital', '🌱 Cafetería', '🔬 Laboratorios Modernos', '🎭 Centro Cultural', '♿ Accesibilidad Total'],
        beca: {
          tipo: 'Beca Excelencia Académica 2026',
          requisitos: [
            { icon: '📊', text: 'Promedio de bachillerato de 8.5 o superior' },
            { icon: '🏆', text: 'Participación en actividades comunitarias' },
            { icon: '📋', text: 'Carta de motivación personal' },
            { icon: '💼', text: 'Entrevista con comité de admisiones' },
            { icon: '📆', text: 'Convocatoria: Febrero – Marzo 2026' },
          ],
        },
        website: 'https://www.uca.edu.sv',
      },
    ],
  },
  'santa-ana': {
    name: 'Santa Ana',
    region: 'Zona Occidental',
    unis: [
      {
        name: 'UNASA',
        fullName: 'Universidad Autónoma de Santa Ana',
        image: 'https://campussostenible.unasa.edu.sv/images/UNASA2024/UNASA_SUR_2024.jpg',
        youtubeId: null,
        desc: 'Institución líder en la zona occidental especializada en ciencias de la salud y tecnología. Cuenta con convenios de beca con la municipalidad.',
        badges: ['🏥 Especialidad Salud', '🌍 Zona Occidental', '📅 Beca Parcial Activa'],
        careers: ['Medicina', 'Laboratorio Clínico', 'Enfermería', 'Fisioterapia', 'Nutrición y Dietética'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno', hora: '7:30 AM – 6:30 PM' },
          { dia: 'Sábado', turno: 'Complementario', hora: '8:00 AM – 1:00 PM' },
        ],
        services: ['🏥 Clínica de Práctica', '🔬 Laboratorio Biomédico', '📚 Biblioteca', '🍽️ Cafetería', '🏋️ Área Deportiva'],
        beca: {
          tipo: 'Beca Mérito Académico — Zona Occidental',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 8.0 en bachillerato' },
            { icon: '🏠', text: 'Residir en Santa Ana, Ahuachapán o Sonsonate' },
            { icon: '💰', text: 'Ingreso familiar menor a $500/mes' },
            { icon: '📋', text: 'DUI del solicitante o tutor' },
          ],
        },
        website: 'https://www.unasa.edu.sv',
      },
      {
        name: 'UCO',
        fullName: 'Universidad Católica de Occidente',
        image: 'https://upload.wikimedia.org/wikipedia/commons/d/db/Logo_UCO_unico.png',
        youtubeId: null,
        desc: 'Universidad de tradición católica en el corazón de Santa Ana con oferta académica en derecho, negocios y ciencias.',
        badges: ['⛪ Privada Católica', '👨‍🎓 4,500 Estudiantes', '🏛️ Tradición Santa Ana'],
        careers: ['Derecho', 'Ciencias Empresariales', 'Arquitectura', 'Ingeniería Civil', 'Educación'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno y Nocturno', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['⛪ Capilla Universitaria', '📚 Biblioteca Jurídica', '💻 Sala de Computación', '🎓 Bienestar Estudiantil'],
        beca: {
          tipo: 'Beca Vocacional Occidental',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5' },
            { icon: '📋', text: 'Carta parroquial o de comunidad religiosa' },
            { icon: '💰', text: 'Comprobante de ingreso familiar' },
          ],
        },
        website: 'https://www.uco.edu.sv',
      },
    ],
  },
  'la-libertad': {
    name: 'La Libertad',
    region: 'Zona Central-Sur',
    unis: [
      {
        name: 'UTEC',
        fullName: 'Universidad Tecnológica de El Salvador',
        image: 'https://www.utec.edu.sv/images/utec-campus.jpg',
        youtubeId: null,
        desc: 'Una de las universidades privadas más grandes del país. Fuertemente orientada a tecnología, negocios y diseño.',
        badges: ['💻 Tecnología', '👨‍🎓 30,000 Estudiantes', '🌐 Sede Nacional', '📅 Beca Activa'],
        careers: ['Informática', 'Diseño Gráfico', 'Administración', 'Mercadotecnia', 'Periodismo', 'Ingeniería Electrónica', 'Gastronomía'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:30 PM – 9:30 PM' },
        ],
        services: ['💻 Data Center Propio', '🎨 Estudio de Diseño', '📡 Canal Universitario UTEC TV', '🍽️ Comedor', '🏋️ Gimnasio', '🚌 Buses Inter-Sede'],
        beca: {
          tipo: 'Beca Digital Futuro',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '💰', text: 'Estudio socioeconómico aprobado' },
            { icon: '🖥️', text: 'Interés comprobado en áreas tecnológicas' },
          ],
        },
        website: 'https://www.utec.edu.sv',
      },
      {
        name: 'UDB',
        fullName: 'Universidad Don Bosco',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnJelJuKqLj7HCIIFwKzeFWt2cQHVdvCFEPQ&s',
        youtubeId: null,
        desc: 'Reconocida por su excelencia en ingeniería y formación técnica de alto nivel. Muy fuerte en STEM con equipamiento de primer nivel.',
        badges: ['⚙️ Ingeniería & STEM', '🤖 Mecatrónica', '🌟 Top STEM El Salvador', '📅 Convocatoria Abierta'],
        careers: ['Mecatrónica', 'Ingeniería de Sistemas', 'Electrónica', 'Industrial', 'Software', 'Biomédica'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno', hora: '7:00 AM – 6:30 PM' },
          { dia: 'Sábado', turno: 'Especial', hora: '8:00 AM – 12:00 PM' },
        ],
        services: ['🔬 Laboratorio Mecatrónica', '🏭 Taller Industrial', '📡 Red WiFi Campus', '🍽️ Cafetería', '🏋️ Área Deportiva', '🤝 Vinculación Empresarial'],
        beca: {
          tipo: 'Beca Talento STEM — Don Bosco',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 8.0 en materias STEM' },
            { icon: '🏆', text: 'Participación en ferias científicas o robótica' },
            { icon: '📋', text: 'Carta de recomendación del bachillerato' },
            { icon: '💡', text: 'Examen de habilidades científicas' },
          ],
        },
        website: 'https://www.udb.edu.sv',
      },
    ],
  },
  'san-miguel': {
    name: 'San Miguel',
    region: 'Zona Oriental',
    unis: [
      {
        name: 'UES Oriente',
        fullName: 'Universidad de El Salvador — Sede Oriental',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'Sede de la Universidad de El Salvador en el oriente del país. La opción pública más accesible para estudiantes de San Miguel, Usulután, Morazán y La Unión.',
        badges: ['🏛️ Pública', '🌍 Zona Oriental', '📅 Beca Remunerada 2026'],
        careers: ['Derecho', 'Economía', 'Agronomía', 'Enfermería', 'Ingeniería', 'Educación'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:00 PM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '🍽️ Comedor Estudiantil', '💻 Sala de Computación', '🏋️ Deportes', '🏥 Clínica'],
        beca: {
          tipo: 'Beca Pública Oriental 2026',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '🏠', text: 'Residir en zona oriental del país' },
            { icon: '💰', text: 'Necesidad económica comprobada' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UGB',
        fullName: 'Universidad Gerardo Barrios',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'La universidad privada más importante del oriente. Cubre los 14 departamentos con sedes estratégicas y becas de liderazgo.',
        badges: ['🎓 Privada', '📍 14 Departamentos', '🏆 Liderazgo Estudiantil'],
        careers: ['Administración', 'Contaduría', 'Derecho', 'Ingeniería Industrial', 'Turismo', 'Psicología'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno y Nocturno', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Biblioteca Digital', '💻 Laboratorios', '🎓 Bolsa de Trabajo', '🏋️ Deportes', '🌐 Campus Virtual'],
        beca: {
          tipo: 'Beca Liderazgo UGB',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5 en bachillerato' },
            { icon: '🏆', text: 'Demostrar liderazgo en comunidad escolar' },
            { icon: '📋', text: 'Cartas de recomendación' },
            { icon: '📝', text: 'Ensayo de 500 palabras sobre metas académicas' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'ahuachaapan': {
    name: 'Ahuachapán',
    region: 'Zona Occidental',
    unis: [
      {
        name: 'UGB — Sede Ahuachapán',
        fullName: 'Universidad Gerardo Barrios — Sede Ahuachapán',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Sede de la Universidad Gerardo Barrios que atiende a los estudiantes del departamento de Ahuachapán con carreras enfocadas en negocios y tecnología.',
        badges: ['🎓 Privada', '📍 Zona Occidental', '📅 Beca Liderazgo'],
        careers: ['Administración de Empresas', 'Contaduría Pública', 'Ingeniería en Sistemas', 'Derecho'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno y Nocturno', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Laboratorio Informático', '🎓 Orientación Vocacional', '📶 WiFi Campus'],
        beca: {
          tipo: 'Beca Liderazgo Occidental — UGB',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5' },
            { icon: '🏠', text: 'Residir en Ahuachapán' },
            { icon: '🏆', text: 'Participación activa en actividades estudiantiles' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
      {
        name: 'UNIVO — Sede Ahuachapán',
        fullName: 'Universidad de Oriente — Sede Ahuachapán',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'La Universidad de Oriente con presencia nacional ofrece carreras accesibles para bachilleres de Ahuachapán.',
        badges: ['🌾 Zona Occidental', '💼 Enfoque Empresarial', '📅 Admisión Abierta'],
        careers: ['Ciencias Jurídicas', 'Administración de Empresas', 'Contaduría Pública'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Nocturno', hora: '5:00 PM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Computación', '🎓 Bolsa de Empleo'],
        beca: {
          tipo: 'Beca Socioeconómica UNIVO',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '💰', text: 'Estudio socioeconómico favorable' },
            { icon: '📋', text: 'Documentos personales completos' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'sonsonate': {
    name: 'Sonsonate',
    region: 'Zona Occidental',
    unis: [
      {
        name: 'UNICO',
        fullName: 'Universidad de Sonsonate (UNICO)',
        image: 'https://www.unico.edu.sv/images/campus.jpg',
        youtubeId: null,
        desc: 'La principal institución privada en Sonsonate, con enfoque en negocios, tecnología y carreras de la salud.',
        badges: ['🏫 Privada', '📍 Sonsonate', '📅 Convocatoria Activa'],
        careers: ['Administración', 'Informática', 'Nutrición', 'Derecho', 'Contaduría'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno y Nocturno', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Laboratorios', '🍽️ Cafetería', '🎓 Bienestar Estudiantil'],
        beca: {
          tipo: 'Beca Municipal Sonsonate',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5' },
            { icon: '🏠', text: 'Residir en municipio de Sonsonate' },
            { icon: '💰', text: 'Necesidad económica verificada' },
          ],
        },
        website: 'https://www.unico.edu.sv',
      },
    ],
  },
  'chalatenango': {
    name: 'Chalatenango',
    region: 'Zona Norte',
    unis: [
      {
        name: 'UGB — Chalatenango',
        fullName: 'Universidad Gerardo Barrios — Sede Chalatenango',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Sede en la región norte que brinda acceso a educación superior a estudiantes de Chalatenango y sus municipios.',
        badges: ['🎓 Privada', '📍 Zona Norte', '📅 Beca Liderazgo'],
        careers: ['Administración de Empresas', 'Derecho', 'Contaduría', 'Ingeniería en Sistemas'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Diurno y Nocturno', hora: '7:00 AM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Lab. Informático', '📶 Internet Campus'],
        beca: {
          tipo: 'Beca Liderazgo Norte',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5' },
            { icon: '🏠', text: 'Residir en departamento de Chalatenango' },
            { icon: '🏆', text: 'Participación comunitaria' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'cabanas': {
    name: 'Cabañas',
    region: 'Zona Norte-Central',
    unis: [
      {
        name: 'UNIVO — Cabañas',
        fullName: 'Universidad de Oriente — Sede Cabañas',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Presencia de la Universidad de Oriente en el departamento de Cabañas, ofreciendo carreras accesibles para la población estudiantil local.',
        badges: ['🌾 Zona Norte-Central', '💼 Bachilleres Cabañas', '📅 Admisión Agosto'],
        careers: ['Ciencias Jurídicas', 'Administración de Empresas', 'Contaduría Pública'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Nocturno', hora: '5:30 PM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Completo', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Sala de Cómputo', '🎓 Asesoría Académica'],
        beca: {
          tipo: 'Beca Socioeconómica Cabañas',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '🏠', text: 'Residir en Cabañas' },
            { icon: '💰', text: 'Ingreso familiar comprobado bajo' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'cuscatlan': {
    name: 'Cuscatlán',
    region: 'Zona Central',
    unis: [
      {
        name: 'UNICO Cuscatlán',
        fullName: 'Universidad de Cuscatlán',
        image: 'https://www.udecusca.edu.sv/images/campus.jpg',
        youtubeId: null,
        desc: 'Universidad privada con sede en Cojutepeque, ofreciendo acceso a educación superior para la zona central-norte del país.',
        badges: ['🏫 Privada', '📍 Cojutepeque', '📅 Beca Parcial'],
        careers: ['Administración', 'Contaduría', 'Ciencias Jurídicas', 'Ingeniería en Computación'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Nocturno', hora: '5:30 PM – 9:00 PM' },
          { dia: 'Sábado', turno: 'Fin de Semana', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Laboratorio', '🎓 Orientación'],
        beca: {
          tipo: 'Beca Acceso Cuscatlán',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '💰', text: 'Estudio socioeconómico' },
          ],
        },
        website: '#',
      },
      {
        name: 'UNIVO — Sede Cuscatlán',
        fullName: 'Universidad de Oriente — Cuscatlán',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Sede de UNIVO en la zona central ofreciendo continuidad educativa para bachilleres de Cuscatlán.',
        badges: ['🌾 Zona Central', '📅 Admisión Continua'],
        careers: ['Administración', 'Derecho', 'Contaduría'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Computación'],
        beca: {
          tipo: 'Beca Socioeconómica',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '💰', text: 'Necesidad económica comprobada' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'la-paz': {
    name: 'La Paz',
    region: 'Zona Central-Sur',
    unis: [
      {
        name: 'UNIVO — La Paz',
        fullName: 'Universidad de Oriente — Sede La Paz',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Sede en la zona paracentral que atiende a estudiantes de La Paz y municipios circundantes.',
        badges: ['🌾 Paracentral', '📅 Beca Socioeconómica'],
        careers: ['Administración de Empresas', 'Contaduría', 'Ciencias Jurídicas'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Computación', '🎓 Asesoría'],
        beca: {
          tipo: 'Beca Socioeconómica La Paz',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '🏠', text: 'Residir en La Paz' },
            { icon: '💰', text: 'Necesidad económica' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
      {
        name: 'UGB — La Paz',
        fullName: 'Universidad Gerardo Barrios — Sede La Paz',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Sede de UGB en la zona paracentral con énfasis en carreras empresariales y jurídicas.',
        badges: ['🎓 Privada', '📍 Zacatecoluca', '🏆 Beca Liderazgo'],
        careers: ['Administración', 'Derecho', 'Contaduría', 'Ingeniería en Sistemas'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Diurno y Nocturno', hora: '7:00 AM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Laboratorio', '🎓 Bolsa de Empleo'],
        beca: {
          tipo: 'Beca Liderazgo Paracentral',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5' },
            { icon: '🏠', text: 'Residir en La Paz' },
            { icon: '🏆', text: 'Activismo estudiantil' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'san-vicente': {
    name: 'San Vicente',
    region: 'Zona Paracentral',
    unis: [
      {
        name: 'UES — San Vicente',
        fullName: 'Universidad de El Salvador — Paramédica San Vicente',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'Centro regional de la UES para la zona paracentral, con enfoque en ciencias de la salud y ciencias agropecuarias.',
        badges: ['🏛️ Pública', '🌿 Agropecuario', '📅 Beca Nacional'],
        careers: ['Enfermería', 'Técnico Agropecuario', 'Educación'],
        schedule: [
          { dia: 'Lunes – Viernes', turno: 'Diurno', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Biblioteca', '🌿 Parcela Agrícola', '🏥 Clínica', '🍽️ Cafetería'],
        beca: {
          tipo: 'Beca Pública Paracentral',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '🏠', text: 'Residir en zona paracentral' },
            { icon: '💰', text: 'Necesidad económica comprobada' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UNIVO — San Vicente',
        fullName: 'Universidad de Oriente — Sede San Vicente',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Sede universitaria para bachilleres de San Vicente con carreras accesibles en horario nocturno.',
        badges: ['🌾 Paracentral', '📅 Admisión Continua'],
        careers: ['Administración', 'Contaduría', 'Derecho'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Computación'],
        beca: {
          tipo: 'Beca Socioeconómica',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '💰', text: 'Necesidad económica' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'usulutan': {
    name: 'Usulután',
    region: 'Zona Oriental',
    unis: [
      {
        name: 'UGB — Usulután',
        fullName: 'Universidad Gerardo Barrios — Sede Usulután',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Sede de UGB en Usulután atendiendo a estudiantes del litoral y zona costera del oriente.',
        badges: ['🎓 Privada', '🌊 Zona Costera', '🏆 Beca Liderazgo'],
        careers: ['Administración', 'Turismo y Hotelería', 'Contaduría', 'Derecho'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Laboratorio', '🏋️ Deporte'],
        beca: {
          tipo: 'Beca Liderazgo Oriente',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5' },
            { icon: '🏠', text: 'Residir en Usulután' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'morazan': {
    name: 'Morazán',
    region: 'Zona Oriental',
    unis: [
      {
        name: 'UGB — Morazán',
        fullName: 'Universidad Gerardo Barrios — Sede Morazán',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'La única sede universitaria privada con beca activa en el departamento de Morazán, facilitando acceso en la zona más rural del oriente.',
        badges: ['🎓 Privada', '🏔️ Zona Rural', '📅 Beca Acceso Rural'],
        careers: ['Administración', 'Contaduría', 'Derecho'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Fin de Semana', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Computación', '🎓 Asesoría'],
        beca: {
          tipo: 'Beca Acceso Rural Morazán',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '🏠', text: 'Residir en Morazán' },
            { icon: '💰', text: 'Necesidad económica' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
      {
        name: 'UNIVO — Morazán',
        fullName: 'Universidad de Oriente — Sede Morazán',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Sede de UNIVO que brinda cobertura educativa en la zona montañosa de Morazán.',
        badges: ['🌾 Rural', '📅 Admisión Continua'],
        careers: ['Administración de Empresas', 'Ciencias Jurídicas'],
        schedule: [
          { dia: 'Sábado y Domingo', turno: 'Fin de Semana', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Computación'],
        beca: {
          tipo: 'Beca Socioeconómica Rural',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.0' },
            { icon: '💰', text: 'Estudio de necesidad económica' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'la-union': {
    name: 'La Unión',
    region: 'Zona Oriental',
    unis: [
      {
        name: 'UGB — La Unión',
        fullName: 'Universidad Gerardo Barrios — Sede La Unión',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Sede portuaria de UGB en La Unión, con carreras orientadas a comercio internacional, logística y administración.',
        badges: ['⚓ Zona Portuaria', '🚢 Logística & Comercio', '📅 Beca Liderazgo'],
        careers: ['Administración', 'Contaduría', 'Comercio Internacional', 'Derecho'],
        schedule: [
          { dia: 'Lunes – Sábado', turno: 'Nocturno', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Biblioteca', '💻 Laboratorio', '🌐 Conexión Portuaria'],
        beca: {
          tipo: 'Beca Liderazgo La Unión',
          requisitos: [
            { icon: '📊', text: 'Promedio mínimo 7.5' },
            { icon: '🏠', text: 'Residir en La Unión' },
            { icon: '🏆', text: 'Participación comunitaria' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
};

// === GENERADOR DE CARDS DE UNIVERSIDAD ===
function buildUniCard(uni, idx) {
  const tabId = `uni-${idx}`;

  const careersHtml = uni.careers.map(c => `<span class="career-chip">${c}</span>`).join('');
  const badgesHtml = uni.badges.map(b => `<span class="uni-stat-badge"><span>${b}</span></span>`).join('');
  const scheduleHtml = uni.schedule.map(s => `
    <tr>
      <td><span class="schedule-dot"></span>${s.dia}</td>
      <td>${s.turno}</td>
      <td><strong style="color:#fff;">${s.hora}</strong></td>
    </tr>`).join('');
  const servicesHtml = uni.services.map(s => `
    <div class="service-item">
      <span class="service-icon-sm">${s.split(' ')[0]}</span>
      <span>${s.split(' ').slice(1).join(' ')}</span>
    </div>`).join('');
  const reqHtml = uni.beca.requisitos.map(r => `
    <div class="beca-requirement">
      <span class="beca-req-icon">${r.icon}</span>
      <span>${r.text}</span>
    </div>`).join('');

  const mediaHtml = uni.youtubeId
    ? `<iframe src="https://www.youtube.com/embed/${uni.youtubeId}?autoplay=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`
    : `<img src="${uni.image}" alt="${uni.name}" onerror="this.src='https://images.unsplash.com/photo-1562774053-701939374585?w=400&q=80'">`;

  return `
  <div class="uni-modal-card">
    <div class="uni-card-top">
      <div class="uni-media-wrap">
        ${mediaHtml}
        <span class="uni-media-badge">${uni.youtubeId ? '▶ Video' : '📷 Foto'}</span>
      </div>
      <div class="uni-card-info">
        <div class="uni-card-name">${uni.name}</div>
        <div class="uni-card-full-name">${uni.fullName}</div>
        <p class="uni-card-desc">${uni.desc}</p>
        <div class="uni-stat-badges">${badgesHtml}</div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="uni-tabs-bar" id="tabs-${tabId}">
      <button class="uni-tab-btn active" data-tab="carreras-${tabId}">🎓 Carreras</button>
      <button class="uni-tab-btn" data-tab="horario-${tabId}">🕐 Horarios</button>
      <button class="uni-tab-btn" data-tab="servicios-${tabId}">🛠️ Servicios</button>
      <button class="uni-tab-btn" data-tab="beca-${tabId}">⭐ Beca Info</button>
    </div>

    <div id="carreras-${tabId}" class="uni-tab-panel active">
      <div class="careers-grid">${careersHtml}</div>
    </div>
    <div id="horario-${tabId}" class="uni-tab-panel">
      <table class="schedule-table">
        <thead><tr><th>Días</th><th>Turno</th><th>Horario</th></tr></thead>
        <tbody>${scheduleHtml}</tbody>
      </table>
    </div>
    <div id="servicios-${tabId}" class="uni-tab-panel">
      <div class="services-list">${servicesHtml}</div>
    </div>
    <div id="beca-${tabId}" class="uni-tab-panel">
      <div class="beca-info-panel">
        <h4 style="color:var(--gold); margin-bottom:1rem; font-size:1rem;">🏆 ${uni.beca.tipo}</h4>
        ${reqHtml}
      </div>
    </div>

    <!-- Footer -->
    <div class="uni-modal-card-footer">
      <a href="${uni.website}" target="_blank" rel="noopener" class="uni-website-link">
        🌐 Sitio oficial — ${uni.fullName} ↗
      </a>
      <a href="{{ route('becas.calendario') }}" class="btn-primary" style="padding:0.45rem 1.1rem; font-size:0.82rem;">Ver Calendario de Becas →</a>
    </div>
  </div>`;
}

// === ABRIR MODAL AL HACER CLICK EN DEPT ===
const mapModalOverlay = document.getElementById('mapModalOverlay');
const mapModalClose = document.getElementById('mapModalClose');
const mapModalBody = document.getElementById('mapModalBody');
const modalDeptNameEl = document.getElementById('modalDeptName');
const modalDeptUniCount = document.getElementById('modalDeptUniCount');
const modalDeptRegion = document.getElementById('modalDeptRegion');

function openMapModal(deptId) {
  const data = UNI_DATA[deptId];
  if (!data || !data.unis || data.unis.length === 0) {
    mapModalBody.innerHTML = `
      <div class="map-modal-empty">
        <div class="empty-icon">🏗️</div>
        <h3>Información en construcción</h3>
        <p>Próximamente agregaremos las universidades de <strong>${deptId}</strong>. ¡Estamos trabajando en ello!</p>
      </div>`;
    modalDeptNameEl.textContent = deptId.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    modalDeptUniCount.textContent = '0 universidades cargadas';
    modalDeptRegion.textContent = 'El Salvador';
    mapModalOverlay.classList.add('open');
    return;
  }

  modalDeptNameEl.textContent = data.name;
  const n = data.unis.length;
  modalDeptUniCount.textContent = `${n} universidad${n !== 1 ? 'es' : ''} con becas`;
  modalDeptRegion.textContent = data.region;

  mapModalBody.innerHTML = data.unis.map((uni, idx) => buildUniCard(uni, `${deptId}-${idx}`)).join('');

  // Inicializar tabs para cada universidad
  mapModalBody.querySelectorAll('.uni-tabs-bar').forEach(bar => {
    bar.querySelectorAll('.uni-tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const targetPanelId = btn.dataset.tab;
        const card = btn.closest('.uni-modal-card');
        card.querySelectorAll('.uni-tab-btn').forEach(b => b.classList.remove('active'));
        card.querySelectorAll('.uni-tab-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(targetPanelId)?.classList.add('active');
      });
    });
  });

  mapModalOverlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeMapModal() {
  mapModalOverlay.classList.remove('open');
  document.body.style.overflow = '';
}

if (mapModalClose) mapModalClose.addEventListener('click', closeMapModal);
if (mapModalOverlay) mapModalOverlay.addEventListener('click', e => {
  if (e.target === mapModalOverlay) closeMapModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMapModal(); });

// === TOOLTIP HOVER + CLICK EN DEPT ===
const mapWrapper  = document.querySelector('.map-wrapper');
const tooltip     = document.getElementById('mapTooltip');
const tooltipName = document.getElementById('tooltipName');
const tooltipUnisText = document.getElementById('tooltipUnisText');

let activedept = null;

document.querySelectorAll('.dept').forEach(dept => {
  dept.addEventListener('mouseenter', () => {
    if (tooltip) {
      const deptName = dept.dataset.name || 'Departamento';
      const n = parseInt(dept.dataset.unis || 0);
      tooltipName.textContent = deptName;
      tooltipUnisText.textContent = n === 1 ? '1 universidad con becas' : `${n} universidades con becas`;
      tooltip.classList.add('visible');
    }
  });

  dept.addEventListener('mousemove', e => {
    if (!mapWrapper || !tooltip) return;
    const rect = mapWrapper.getBoundingClientRect();
    let lx = e.clientX - rect.left + 16;
    let ly = e.clientY - rect.top - 50;
    if (lx + 290 > rect.width) lx = e.clientX - rect.left - 295;
    if (ly < 0) ly = e.clientY - rect.top + 16;
    tooltip.style.left = lx + 'px';
    tooltip.style.top = ly + 'px';
  });

  dept.addEventListener('mouseleave', () => {
    if (tooltip) tooltip.classList.remove('visible');
  });

  dept.addEventListener('click', () => {
    if (tooltip) tooltip.classList.remove('visible');
    // Mark active
    document.querySelectorAll('.dept.active').forEach(d => d.classList.remove('active'));
    dept.classList.add('active');
    activedept = dept;

    const deptId = dept.dataset.id || '';
    openMapModal(deptId);
  });
});

document.addEventListener('click', e => {
  if (!e.target.classList.contains('dept') && !mapModalOverlay?.contains(e.target)) {
    document.querySelectorAll('.dept.active').forEach(d => d.classList.remove('active'));
  }
});



/* ==========================================================================
   INTERACTIVE CAROUSELS, COMPASS & FLOATING DOCK LOGIC
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // 1. BECAS CAROUSEL LOGIC
    const track = document.getElementById('becasTrack');
    const prevBtn = document.getElementById('prevBecaBtn');
    const nextBtn = document.getElementById('nextBecaBtn');
    const filterPills = document.querySelectorAll('#becasFilterPills .filter-pill');

    if (track && prevBtn && nextBtn) {
        let currentIndex = 0;

        function getCardWidth() {
            const card = track.querySelector('.beca-card-3d');
            return card ? card.offsetWidth + 24 : 340; // 24px gap
        }

        function updateTrackPosition() {
            const cardWidth = getCardWidth();
            track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        }

        nextBtn.addEventListener('click', () => {
            const visibleCards = track.querySelectorAll('.beca-card-3d:not([style*="display: none"])');
            const maxIndex = Math.max(0, visibleCards.length - 1);
            if (currentIndex < maxIndex) {
                currentIndex++;
            } else {
                currentIndex = 0; // loop back
            }
            updateTrackPosition();
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = 0;
            }
            updateTrackPosition();
        });

        // Filter Pills Event
        filterPills.forEach(pill => {
            pill.addEventListener('click', () => {
                filterPills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');

                const filter = pill.dataset.filter;
                const cards = track.querySelectorAll('.beca-card-3d');

                cards.forEach(card => {
                    const cat = card.dataset.category || '';
                    if (filter === 'todos' || cat.includes(filter)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });

                currentIndex = 0;
                updateTrackPosition();
            });
        });

        // Responsive resize
        window.addEventListener('resize', updateTrackPosition);
    }

    // 2. FLOATING COMPASS NEEDLE & JUMP TO TOP
    const compassWidget = document.getElementById('floatingCompass');
    const compassNeedle = document.getElementById('compassNeedle');

    if (compassWidget && compassNeedle) {
        window.addEventListener('scroll', () => {
            const totalScrollable = document.documentElement.scrollHeight - window.innerHeight;
            if (totalScrollable > 0) {
                const scrollPercent = window.scrollY / totalScrollable;
                const angle = scrollPercent * 720; // rotates as you scroll deeper
                compassNeedle.style.transform = `rotate(${angle}deg)`;
            }
        });

        compassWidget.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // 3. FLOATING MARINE DOCK ACTIVE LINK
    const dockItems = document.querySelectorAll('#marineDock .dock-item');
    const sections = document.querySelectorAll('section[id]');

    if (dockItems.length && sections.length) {
        window.addEventListener('scroll', () => {
            let currentSec = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 200;
                if (window.scrollY >= sectionTop) {
                    currentSec = section.getAttribute('id');
                }
            });

            dockItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === `#${currentSec}`) {
                    item.classList.add('active');
                }
            });
        });
    }

    // 4. 3D CARD TILT EFFECT ON HOVER
    const tiltCards = document.querySelectorAll('.beca-card-3d, .bitacora-card, .service-card');
    tiltCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * -6;
            const rotateY = ((x - centerX) / centerX) * 6;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    // 5. SIMULADOR DE BECA CALCULATOR LOGIC
    const btnCalcularSim = document.getElementById('btnCalcularSim');
    if (btnCalcularSim) {
        btnCalcularSim.addEventListener('click', () => {
            const prom = document.getElementById('simPromedio')?.value || '100';
            const dest = document.getElementById('simDestino')?.value || 'sv';

            let baseScore = parseInt(prom);
            if (dest === 'es' || dest === 'usa') baseScore = Math.max(75, baseScore - 5);

            const scoreEl = document.getElementById('scoreCompat');
            const descEl = document.getElementById('simDescText');
            const countEl = document.getElementById('countOpEncontradas');
            const cobEl = document.getElementById('coberturaEstimada');

            if (scoreEl) {
                let current = 0;
                const timer = setInterval(() => {
                    current += 3;
                    if (current >= baseScore) {
                        current = baseScore;
                        clearInterval(timer);
                    }
                    scoreEl.innerText = `${current}%`;
                }, 20);
            }

            if (baseScore >= 90) {
                if (descEl) descEl.innerText = '¡Felicidades! Tienes un perfil excepcional para becas completas de Excelencia y Posgrado Internacional con estipendio mensual.';
                if (countEl) countEl.innerText = '5 Becas Matheadas';
                if (cobEl) cobEl.innerText = '100% Cobertura + Estipendio';
            } else if (baseScore >= 80) {
                if (descEl) descEl.innerText = '¡Gran perfil! Calificas para becas de Pregrado y Padrinazgo Educativo con cobertura de matrícula y mensualidades.';
                if (countEl) countEl.innerText = '3 Becas Matheadas';
                if (cobEl) cobEl.innerText = '80% - 100% Matrícula';
            } else {
                if (descEl) descEl.innerText = 'Calificas para programas de apoyo socioeconómico y padrinazgo personalizado en universidades de El Salvador.';
                if (countEl) countEl.innerText = '2 Becas Matheadas';
                if (cobEl) cobEl.innerText = '50% - 75% Arancel';
            }
        });
    }

    // 6. ROADMAP STEPPER LOGIC
    const roadmapSteps = document.querySelectorAll('.roadmap-step-item');
    const stepData = {
        '1': {
            tag: 'Paso 1 de 5',
            title: 'Test Socioemocional & Orientación Vocacional',
            desc: 'Inicias identificando tus inteligencias múltiples y rasgos socioemocionales. Esto te permite elegir la carrera y universidad con mayor proyección para ti.',
            btnText: 'Hacer el Test Gratis →',
            btnHref: '#'
        },
        '2': {
            tag: 'Paso 2 de 5',
            title: 'Exploración en el Mapa Territorial',
            desc: 'Filtra universidades por departamento para descubrir las ofertas académicas más cercanas a tu municipio con programa de becas activo.',
            btnText: 'Explorar Mapa →',
            btnHref: '#universidades'
        },
        '3': {
            tag: 'Paso 3 de 5',
            title: 'Organización en Agenda & Calendario',
            desc: 'Añade alertas de cierre y fechas límite para tus entregas de documentos con nuestro semáforo interactivo de urgencias.',
            btnText: 'Ver Calendario →',
            btnHref: '/calendario'
        },
        '4': {
            tag: 'Paso 4 de 5',
            title: 'Conexión Transparente con Padrinos',
            desc: 'Conecta con patrocinadores e instituciones dispuestas a financiar tu educación bajo condiciones justas y directas.',
            btnText: 'Conocer Padrinos →',
            btnHref: '#servicios'
        },
        '5': {
            tag: 'Paso 5 de 5',
            title: '¡Zarpar a la Universidad y Triunfar!',
            desc: 'Presenta tu admisión respaldada por UGF y comienza tus clases universitarias rumbo a un futuro brillante.',
            btnText: 'Buscar Mi Beca →',
            btnHref: '/becas'
        }
    };

    roadmapSteps.forEach(stepItem => {
        stepItem.addEventListener('click', () => {
            roadmapSteps.forEach(s => s.classList.remove('active'));
            stepItem.classList.add('active');

            const stepNum = stepItem.dataset.step;
            const data = stepData[stepNum];

            if (data) {
                const stepTag = document.getElementById('stepTag');
                const stepTitle = document.getElementById('stepTitle');
                const stepDesc = document.getElementById('stepDesc');
                const stepBtn = document.getElementById('stepBtn');

                if (stepTag) stepTag.innerText = data.tag;
                if (stepTitle) stepTitle.innerText = data.title;
                if (stepDesc) stepDesc.innerText = data.desc;
                if (stepBtn) {
                    stepBtn.innerText = data.btnText;
                    stepBtn.setAttribute('href', data.btnHref);
                }
            }
        });
    });

    // 7. ACCORDION FAQ LOGIC
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const header = item.querySelector('.faq-header');
        if (header) {
            header.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                faqItems.forEach(i => i.classList.remove('active'));
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        }
    });
});