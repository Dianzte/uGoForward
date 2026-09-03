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
  
  // Aseguramos que las propiedades of visibilidad sean constantes
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
    region: 'Central Zone',
    unis: [
      {
        name: 'UES',
        fullName: 'University of El Salvador',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'The main public higher education institution in the country. Founded in 1841, it offers the largest academic offer nationwide with the widest paid scholarship system in El Salvador.',
        badges: ['🏛️ Public', '👨‍🎓 65,000 Students', '⭐ Founded 1841', '📅 2026 Scholarship Open'],
        careers: ['Medicine', 'Industrial Engineering', 'Law', 'Economics', 'Chemical Sciences', 'Computing', 'Dentistry', 'Architecture', 'Chemistry and Pharmacy'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:00 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['🏥 Free Medical Clinic', '📚 24H Central Library', '🍽️ University Cafeteria', '🚌 Public Transport', '💻 IT Labs', '🏋️ Sports', '🎨 Art and Culture', '🔬 Research Center'],
        beca: {
          tipo: 'Paid Scholarship and Tuition Waiver',
          requisitos: [
            { icon: '📊', text: 'Minimum high school GPA of 7.0' },
            { icon: '💰', text: 'Proof of financial need' },
            { icon: '📋', text: 'Certified birth certificate' },
            { icon: '🎯', text: 'Approved aptitude test' },
            { icon: '📆', text: 'Apply before January 2026' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UCA',
        fullName: 'Central American University José Simeón Cañas',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwjqm5gCq_2DYlnuKO0g7HRGDJfUvSqOXhpQ&s',
        youtubeId: null,
        desc: 'Private university of high prestige and social orientation. Its academic excellence scholarship programs are recognized throughout Central America.',
        badges: ['🎓 Private', '👨‍🎓 8,000 Students', '⭐ High Academic Quality', '🌎 International Recognition'],
        careers: ['Computer Engineering', 'Business Administration', 'Psychology', 'Communications', 'Master of Law', 'Civil Engineering', 'Theology'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 7:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Specialized Library', '💻 Digital Campus', '🌱 Cafeteria', '🔬 Modern Labs', '🎭 Cultural Center', '♿ Full Accessibility'],
        beca: {
          tipo: 'Academic Excellence Scholarship 2026',
          requisitos: [
            { icon: '📊', text: 'High school GPA of 8.5 or higher' },
            { icon: '🏆', text: 'Participation in community activities' },
            { icon: '📋', text: 'Personal motivation letter' },
            { icon: '💼', text: 'Interview with admissions committee' },
            { icon: '📆', text: 'Call for applications: February – March 2026' },
          ],
        },
        website: 'https://www.uca.edu.sv',
      },
    ],
  },
  'santa-ana': {
    name: 'Santa Ana',
    region: 'Western Zone',
    unis: [
      {
        name: 'UNASA',
        fullName: 'Autonomous University of Santa Ana',
        image: 'https://campussostenible.unasa.edu.sv/images/UNASA2024/UNASA_SUR_2024.jpg',
        youtubeId: null,
        desc: 'Leading institution in the western zone specializing in health sciences and technology. It has scholarship agreements with the municipality.',
        badges: ['🏥 Health Specialty', '🌍 Western Zone', '📅 Partial Scholarship Active'],
        careers: ['Medicine', 'Clinical Laboratory', 'Nursing', 'Physiotherapy', 'Nutrition and Dietetics'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:30 AM – 6:30 PM' },
          { dia: 'Saturday', turno: 'Complementary', hora: '8:00 AM – 1:00 PM' },
        ],
        services: ['🏥 Practice Clinic', '🔬 Biomedical Lab', '📚 Library', '🍽️ Cafeteria', '🏋️ Sports Area'],
        beca: {
          tipo: 'Academic Merit Scholarship — Western Zone',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA of 8.0 in high school' },
            { icon: '🏠', text: 'Reside in Santa Ana, Ahuachapán or Sonsonate' },
            { icon: '💰', text: 'Family income under $500/month' },
            { icon: '📋', text: 'Applicant or guardian ID' },
          ],
        },
        website: 'https://www.unasa.edu.sv',
      },
      {
        name: 'UCO',
        fullName: 'Catholic University of the West',
        image: 'https://upload.wikimedia.org/wikipedia/commons/d/db/Logo_UCO_unico.png',
        youtubeId: null,
        desc: 'University with Catholic tradition in the heart of Santa Ana with academic offers in law, business and sciences.',
        badges: ['⛪ Private Catholic', '👨‍🎓 4,500 Students', '🏛️ Santa Ana Tradition'],
        careers: ['Law', 'Business Sciences', 'Architecture', 'Civil Engineering', 'Education'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['⛪ University Chapel', '📚 Law Library', '💻 Computer Room', '🎓 Student Welfare'],
        beca: {
          tipo: 'Western Vocational Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '📋', text: 'Parish or religious community letter' },
            { icon: '💰', text: 'Proof of family income' },
          ],
        },
        website: 'https://www.uco.edu.sv',
      },
    ],
  },
  'la-libertad': {
    name: 'La Libertad',
    region: 'South-Central Zone',
    unis: [
      {
        name: 'UTEC',
        fullName: 'Technological University of El Salvador',
        image: 'https://www.utec.edu.sv/images/utec-campus.jpg',
        youtubeId: null,
        desc: 'One of the largest private universities in the country. Strongly oriented towards technology, business and design.',
        badges: ['💻 Technology', '👨‍🎓 30,000 Students', '🌐 National Headquarters', '📅 Active Scholarship'],
        careers: ['Computing', 'Graphic Design', 'Administration', 'Marketing', 'Journalism', 'Electronic Engineering', 'Gastronomy'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:30 PM' },
        ],
        services: ['💻 Own Data Center', '🎨 Design Studio', '📡 UTEC TV University Channel', '🍽️ Cafeteria', '🏋️ Gym', '🚌 Inter-Campus Buses'],
        beca: {
          tipo: 'Future Digital Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Approved socioeconomic study' },
            { icon: '🖥️', text: 'Proven interest in technological areas' },
          ],
        },
        website: 'https://www.utec.edu.sv',
      },
      {
        name: 'UDB',
        fullName: 'Don Bosco University',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnJelJuKqLj7HCIIFwKzeFWt2cQHVdvCFEPQ&s',
        youtubeId: null,
        desc: 'Recognized for its excellence in engineering and high-level technical training. Very strong in STEM with top-tier equipment.',
        badges: ['⚙️ Engineering & STEM', '🤖 Mechatronics', '🌟 Top STEM El Salvador', '📅 Open Call'],
        careers: ['Mechatronics', 'Systems Engineering', 'Electronics', 'Industrial', 'Software', 'Biomedical'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:30 PM' },
          { dia: 'Saturday', turno: 'Special', hora: '8:00 AM – 12:00 PM' },
        ],
        services: ['🔬 Mechatronics Lab', '🏭 Industrial Workshop', '📡 WiFi Campus Network', '🍽️ Cafeteria', '🏋️ Sports Area', '🤝 Business Linkage'],
        beca: {
          tipo: 'STEM Talent Scholarship — Don Bosco',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 8.0 in STEM subjects' },
            { icon: '🏆', text: 'Participation in science fairs or robotics' },
            { icon: '📋', text: 'Recommendation letter from high school' },
            { icon: '💡', text: 'Scientific skills test' },
          ],
        },
        website: 'https://www.udb.edu.sv',
      },
    ],
  },
  'san-miguel': {
    name: 'San Miguel',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UES Oriente',
        fullName: 'University of El Salvador — Eastern Campus',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'Campus of the University of El Salvador in the east of the country. The most accessible public option for students from San Miguel, Usulután, Morazán and La Unión.',
        badges: ['🏛️ Public', '🌍 Eastern Zone', '📅 Paid Scholarship 2026'],
        careers: ['Law', 'Economics', 'Agronomy', 'Nursing', 'Engineering', 'Education'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:00 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '🍽️ Student Cafeteria', '💻 Computer Room', '🏋️ Sports', '🏥 Clinic'],
        beca: {
          tipo: 'Eastern Public Scholarship 2026',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in the eastern zone of the country' },
            { icon: '💰', text: 'Proven financial need' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UGB',
        fullName: 'Gerardo Barrios University',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'The most important private university in the east. Covers all 14 departments with strategic campuses and leadership scholarships.',
        badges: ['🎓 Private', '📍 14 Departments', '🏆 Student Leadership'],
        careers: ['Administration', 'Accounting', 'Law', 'Industrial Engineering', 'Tourism', 'Psychology'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Digital Library', '💻 Labs', '🎓 Job Board', '🏋️ Sports', '🌐 Virtual Campus'],
        beca: {
          tipo: 'UGB Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5 in high school' },
            { icon: '🏆', text: 'Show leadership in school community' },
            { icon: '📋', text: 'Recommendation letters' },
            { icon: '📝', text: '500-word essay on academic goals' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'ahuachaapan': {
    name: 'Ahuachapán',
    region: 'Western Zone',
    unis: [
      {
        name: 'UGB — Ahuachapán Campus',
        fullName: 'Gerardo Barrios University — Ahuachapán Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Campus of the Gerardo Barrios University serving students from the department of Ahuachapán with careers focused on business and technology.',
        badges: ['🎓 Private', '📍 Western Zone', '📅 Leadership Scholarship'],
        careers: ['Business Administration', 'Public Accounting', 'Systems Engineering', 'Law'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 IT Lab', '🎓 Vocational Counseling', '📶 Campus WiFi'],
        beca: {
          tipo: 'Western Leadership Scholarship — UGB',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in Ahuachapán' },
            { icon: '🏆', text: 'Active participation in student activities' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
      {
        name: 'UNIVO — Ahuachapán Campus',
        fullName: 'University of the East — Ahuachapán Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'The University of the East with national presence offers accessible careers for high school graduates from Ahuachapán.',
        badges: ['🌾 Western Zone', '💼 Business Focus', '📅 Admission Open'],
        careers: ['Legal Sciences', 'Business Administration', 'Public Accounting'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Nighttime', hora: '5:00 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing', '🎓 Job Board'],
        beca: {
          tipo: 'UNIVO Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Favorable socioeconomic study' },
            { icon: '📋', text: 'Complete personal documents' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'sonsonate': {
    name: 'Sonsonate',
    region: 'Western Zone',
    unis: [
      {
        name: 'UNICO',
        fullName: 'University of Sonsonate (UNICO)',
        image: 'https://www.unico.edu.sv/images/campus.jpg',
        youtubeId: null,
        desc: 'The main private institution in Sonsonate, with a focus on business, technology and health careers.',
        badges: ['🏫 Private', '📍 Sonsonate', '📅 Active Call'],
        careers: ['Administration', 'Computing', 'Nutrition', 'Law', 'Accounting'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Labs', '🍽️ Cafeteria', '🎓 Student Welfare'],
        beca: {
          tipo: 'Sonsonate Municipal Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in the municipality of Sonsonate' },
            { icon: '💰', text: 'Verified financial need' },
          ],
        },
        website: 'https://www.unico.edu.sv',
      },
    ],
  },
  'chalatenango': {
    name: 'Chalatenango',
    region: 'Northern Zone',
    unis: [
      {
        name: 'UGB — Chalatenango',
        fullName: 'Gerardo Barrios University — Chalatenango Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Campus in the northern region that provides access to higher education to students from Chalatenango and its municipalities.',
        badges: ['🎓 Private', '📍 Northern Zone', '📅 Leadership Scholarship'],
        careers: ['Business Administration', 'Law', 'Accounting', 'Systems Engineering'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 IT Lab', '📶 Campus Internet'],
        beca: {
          tipo: 'Northern Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in the department of Chalatenango' },
            { icon: '🏆', text: 'Community participation' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'cabanas': {
    name: 'Cabañas',
    region: 'North-Central Zone',
    unis: [
      {
        name: 'UNIVO — Cabañas',
        fullName: 'University of the East — Cabañas Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Presence of the University of the East in the department of Cabañas, offering accessible careers for the local student population.',
        badges: ['🌾 North-Central Zone', '💼 Cabañas High School Grads', '📅 August Admission'],
        careers: ['Legal Sciences', 'Business Administration', 'Public Accounting'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Full', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Computer Room', '🎓 Academic Counseling'],
        beca: {
          tipo: 'Cabañas Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in Cabañas' },
            { icon: '💰', text: 'Proven low family income' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'cuscatlan': {
    name: 'Cuscatlán',
    region: 'Central Zone',
    unis: [
      {
        name: 'UNICO Cuscatlán',
        fullName: 'University of Cuscatlán',
        image: 'https://www.udecusca.edu.sv/images/campus.jpg',
        youtubeId: null,
        desc: 'Private university based in Cojutepeque, offering access to higher education for the north-central zone of the country.',
        badges: ['🏫 Private', '📍 Cojutepeque', '📅 Partial Scholarship'],
        careers: ['Administration', 'Accounting', 'Legal Sciences', 'Computer Engineering'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🎓 Counseling'],
        beca: {
          tipo: 'Cuscatlán Access Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Socioeconomic study' },
          ],
        },
        website: '#',
      },
      {
        name: 'UNIVO — Cuscatlán Campus',
        fullName: 'University of the East — Cuscatlán',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'UNIVO campus in the central zone offering educational continuity for high school graduates from Cuscatlán.',
        badges: ['🌾 Central Zone', '📅 Continuous Admission'],
        careers: ['Administration', 'Law', 'Accounting'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing'],
        beca: {
          tipo: 'Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Proven financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'la-paz': {
    name: 'La Paz',
    region: 'South-Central Zone',
    unis: [
      {
        name: 'UNIVO — La Paz',
        fullName: 'University of the East — La Paz Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Campus in the paracentral zone that serves students from La Paz and surrounding municipalities.',
        badges: ['🌾 Paracentral', '📅 Socioeconomic Scholarship'],
        careers: ['Business Administration', 'Accounting', 'Legal Sciences'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing', '🎓 Counseling'],
        beca: {
          tipo: 'La Paz Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in La Paz' },
            { icon: '💰', text: 'Financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
      {
        name: 'UGB — La Paz',
        fullName: 'Gerardo Barrios University — La Paz Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'UGB campus in the paracentral zone with an emphasis on business and legal careers.',
        badges: ['🎓 Private', '📍 Zacatecoluca', '🏆 Leadership Scholarship'],
        careers: ['Administration', 'Law', 'Accounting', 'Systems Engineering'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🎓 Job Board'],
        beca: {
          tipo: 'Paracentral Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in La Paz' },
            { icon: '🏆', text: 'Student activism' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'san-vicente': {
    name: 'San Vicente',
    region: 'Paracentral Zone',
    unis: [
      {
        name: 'UES — San Vicente',
        fullName: 'University of El Salvador — San Vicente Paracentral',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'UES regional center for the paracentral zone, focusing on health sciences and agricultural sciences.',
        badges: ['🏛️ Public', '🌿 Agricultural', '📅 National Scholarship'],
        careers: ['Nursing', 'Agricultural Technician', 'Education'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Library', '🌿 Agricultural Plot', '🏥 Clinic', '🍽️ Cafeteria'],
        beca: {
          tipo: 'Paracentral Public Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in paracentral zone' },
            { icon: '💰', text: 'Proven financial need' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UNIVO — San Vicente',
        fullName: 'University of the East — San Vicente Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'University campus for San Vicente high school graduates with accessible careers in nighttime hours.',
        badges: ['🌾 Paracentral', '📅 Continuous Admission'],
        careers: ['Administration', 'Accounting', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing'],
        beca: {
          tipo: 'Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'usulutan': {
    name: 'Usulután',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UGB — Usulután',
        fullName: 'Gerardo Barrios University — Usulután Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'UGB campus in Usulután serving students from the coast and coastal zone of the east.',
        badges: ['🎓 Private', '🌊 Coastal Zone', '🏆 Leadership Scholarship'],
        careers: ['Administration', 'Tourism and Hospitality', 'Accounting', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🏋️ Sports'],
        beca: {
          tipo: 'Eastern Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in Usulután' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'morazan': {
    name: 'Morazán',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UGB — Morazán',
        fullName: 'Gerardo Barrios University — Morazán Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'The only private university campus with an active scholarship in the department of Morazán, facilitating access in the most rural area of the east.',
        badges: ['🎓 Private', '🏔️ Rural Zone', '📅 Rural Access Scholarship'],
        careers: ['Administration', 'Accounting', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing', '🎓 Counseling'],
        beca: {
          tipo: 'Morazán Rural Access Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in Morazán' },
            { icon: '💰', text: 'Financial need' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
      {
        name: 'UNIVO — Morazán',
        fullName: 'University of the East — Morazán Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'UNIVO campus that provides educational coverage in the mountainous area of Morazán.',
        badges: ['🌾 Rural', '📅 Continuous Admission'],
        careers: ['Business Administration', 'Legal Sciences'],
        schedule: [
          { dia: 'Saturday and Sunday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing'],
        beca: {
          tipo: 'Rural Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Study of financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'la-union': {
    name: 'La Unión',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UGB — La Unión',
        fullName: 'Gerardo Barrios University — La Unión Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'UGB port campus in La Unión, with careers focused on international trade, logistics and administration.',
        badges: ['⚓ Port Zone', '🚢 Logistics & Trade', '📅 Leadership Scholarship'],
        careers: ['Administration', 'Accounting', 'International Trade', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🌐 Port Connection'],
        beca: {
          tipo: 'La Unión Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in La Unión' },
            { icon: '🏆', text: 'Community participation' },
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
        <span class="uni-media-badge">${uni.youtubeId ? '▶ Video' : '📷 Photo'}</span>
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
      <button class="uni-tab-btn active" data-tab="carreras-${tabId}">🎓 Careers</button>
      <button class="uni-tab-btn" data-tab="horario-${tabId}">🕐 Schedules</button>
      <button class="uni-tab-btn" data-tab="servicios-${tabId}">🛠️ Services</button>
      <button class="uni-tab-btn" data-tab="beca-${tabId}">⭐ Scholarship Info</button>
    </div>

    <div id="carreras-${tabId}" class="uni-tab-panel active">
      <div class="careers-grid">${careersHtml}</div>
    </div>
    <div id="horario-${tabId}" class="uni-tab-panel">
      <table class="schedule-table">
        <thead><tr><th>Days</th><th>Shift</th><th>Schedule</th></tr></thead>
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
        🌐 Official site — ${uni.fullName} ↗
      </a>
      <a href="{{ route('becas.calendario') }}" class="btn-primary" style="padding:0.45rem 1.1rem; font-size:0.82rem;">View Scholarship Calendar →</a>
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
        <h3>Information under construction</h3>
        <p>We will soon add the universities of <strong>${deptId}</strong>. We are working on it!</p>
      </div>`;
    modalDeptNameEl.textContent = deptId.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    modalDeptUniCount.textContent = '0 universidades cargadas';
    modalDeptRegion.textContent = 'El Salvador';
    mapModalOverlay.classList.add('open');
    return;
  }

  modalDeptNameEl.textContent = data.name;
  const n = data.unis.length;
  modalDeptUniCount.textContent = `${n} universit${n !== 1 ? 'ies' : 'y'} with scholarships`;
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
      const deptName = dept.dataset.name || 'Department';
      const n = parseInt(dept.dataset.unis || 0);
      tooltipName.textContent = deptName;
      tooltipUnisText.textContent = n === 1 ? '1 university with scholarships' : `${n} universities with scholarships`;
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
                if (descEl) descEl.innerText = '¡Felicidades! Tienes un perfil excepcional para becas completas of Excelencia y Posgrado Internacional con estipendio mensual.';
                if (countEl) countEl.innerText = '5 Matched Scholarships';
                if (cobEl) cobEl.innerText = '100% Coverage + Stipend';
            } else if (baseScore >= 80) {
                if (descEl) descEl.innerText = '¡Gran perfil! Calificas para becas of Pregrado y Padrinazgo Educativo con cobertura of matrícula y mensualidades.';
                if (countEl) countEl.innerText = '3 Matched Scholarships';
                if (cobEl) cobEl.innerText = '80% - 100% Tuition';
            } else {
                if (descEl) descEl.innerText = 'Calificas para programas of apoyo socioeconómico y padrinazgo personalizado en universidades of El Salvador.';
                if (countEl) countEl.innerText = '2 Matched Scholarships';
                if (cobEl) cobEl.innerText = '50% - 75% Fee';
            }
        });
    }

    // 6. ROADMAP STEPPER LOGIC
    const roadmapSteps = document.querySelectorAll('.roadmap-step-item');
    const stepData = {
        '1': {
            tag: 'Step 1 of 5',
            title: 'Socioemotional Test & Vocational Guidance',
            desc: 'You start by identifying your multiple intelligences and socioemotional traits. This allows you to choose the career and university with the best projection for you.',
            btnText: 'Take the Free Test →',
            btnHref: '#'
        },
        '2': {
            tag: 'Step 2 of 5',
            title: 'Exploration on the Territorial Map',
            desc: 'Filter universities by department to discover the closest academic offers to your municipality with an active scholarship program.',
            btnText: 'Explore Map →',
            btnHref: '#universidades'
        },
        '3': {
            tag: 'Step 3 of 5',
            title: 'Organization in Agenda & Calendar',
            desc: 'Add closing alerts and deadlines for your document deliveries with our interactive urgency traffic light.',
            btnText: 'View Calendar →',
            btnHref: '/calendario'
        },
        '4': {
            tag: 'Step 4 of 5',
            title: 'Transparent Connection with Sponsors',
            desc: 'Connect with sponsors and institutions willing to finance your education under fair and direct conditions.',
            btnText: 'Meet Sponsors →',
            btnHref: '#servicios'
        },
        '5': {
            tag: 'Step 5 of 5',
            title: 'Set Sail for University and Succeed!',
            desc: 'Present your admission backed by UGF and start your university classes towards a bright future.',
            btnText: 'Find My Scholarship →',
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