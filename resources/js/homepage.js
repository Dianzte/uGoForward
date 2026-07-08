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
   11. MAP INTERACTIVITY — Popup/Tooltip
   ───────────────────────────────────── */
const mapWrapper  = document.querySelector('.map-wrapper');
const tooltip     = document.getElementById('mapTooltip');
const tooltipName = document.getElementById('tooltipName');
const tooltipUnis = document.getElementById('tooltipUnis');
const tooltipDesc = document.getElementById('tooltipDesc');

let activedept = null;

document.querySelectorAll('.dept').forEach(dept => {

  /* ── HOVER: mostrar tooltip ── */
  dept.addEventListener('mouseenter', () => {
    tooltipName.textContent = dept.dataset.name || 'Departamento';
    const n = parseInt(dept.dataset.unis || 0);
    tooltipUnis.textContent = n === 1 ? '1 universidad con becas' : `${n} universidades con becas`;
    tooltipDesc.textContent = dept.dataset.desc || '';
    tooltip.classList.add('visible');
  });

  /* ── MOUSE MOVE: seguir cursor ── */
  dept.addEventListener('mousemove', e => {
    if (!mapWrapper) return;
    const rect = mapWrapper.getBoundingClientRect();
    let lx = e.clientX - rect.left + 16;
    let ly = e.clientY - rect.top  - 50;
    // Evitar que se salga por la derecha
    if (lx + 270 > rect.width) lx = e.clientX - rect.left - 275;
    // Evitar que se salga por arriba
    if (ly < 0) ly = e.clientY - rect.top + 16;
    tooltip.style.left = lx + 'px';
    tooltip.style.top  = ly + 'px';
  });

  /* ── MOUSE LEAVE: ocultar tooltip ── */
  dept.addEventListener('mouseleave', () => {
    tooltip.classList.remove('visible');
  });

  /* ── CLICK: resaltar departamento ── */
  dept.addEventListener('click', () => {
    // Quitar active del anterior
    if (activedept && activedept !== dept) {
      activedept.classList.remove('active');
    }
    dept.classList.toggle('active');
    activedept = dept.classList.contains('active') ? dept : null;
  });
});

/* Click fuera del mapa: quitar active */
document.addEventListener('click', e => {
  if (!e.target.classList.contains('dept')) {
    document.querySelectorAll('.dept.active').forEach(d => d.classList.remove('active'));
    activedept = null;
  }
});


/*MODAL 1*/ 

const modal = document.getElementById("uniModal");
const closeModal = document.getElementById("closeModal");
const modalDept = document.getElementById("modalDept");
const universidadesContainer =
document.getElementById("universidadesContainer");

document.querySelectorAll(".dept").forEach(dept=>{

    dept.addEventListener("click",()=>{

        modalDept.innerText=dept.dataset.name;

        universidadesContainer.innerHTML="";

        const universidades=JSON.parse(dept.dataset.universities || "[]");

        universidades.forEach(u=>{

            universidadesContainer.innerHTML+=`

            <div class="universidad">

                <img src="${u.image}">

                <div class="uni-info">

                    <h3>${u.name}</h3>

                    <p>${u.description}</p>

                    <p><strong>Carreras:</strong> ${u.careers}</p>

                    <a href="${u.website}" target="_blank">
                        Sitio web
                    </a>

                </div>

            </div>

            `;

        });

        modal.style.display="block";

    });

});

closeModal.onclick=()=>{

modal.style.display="none";

}

window.onclick=(e)=>{

if(e.target==modal){

modal.style.display="none";

}

}

/* JS DE PAGINA DE CARGA BELLACONA       */
window.addEventListener("load", () => {
    const loader = document.getElementById("gta-loader");
    
    // Agregamos un retraso de 2000 milisegundos (2 segundos) estilo videojuego
    setTimeout(() => {
        loader.classList.add("gta-loader-hidden");
    }, 2000); 
});