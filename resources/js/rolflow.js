/* ==========================================================================
   UGF · rolflow.js — Animación de entrada en scroll
   Solo anima elementos marcados explícitamente con .rf-reveal, para no
   interferir con tarjetas que ya controlan su propia visibilidad por JS
   (como el formulario del test o el resultado).
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.rf-reveal');
  if (!items.length) return;

  // Fallback: si el navegador no soporta IntersectionObserver, mostrar todo de inmediato.
  if (!('IntersectionObserver' in window)) {
    items.forEach(el => el.classList.add('rf-visible'));
    return;
  }

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      const el = entry.target;
      const delay = Number(el.dataset.revealDelay || 0);

      setTimeout(() => el.classList.add('rf-visible'), delay);
      obs.unobserve(el);
    });
  }, {
    threshold: 0.15,
    rootMargin: '0px 0px -60px 0px',
  });

  items.forEach((el, index) => {
    // Efecto cascada automático si no se definió un delay manual
    if (!el.dataset.revealDelay) {
      el.dataset.revealDelay = String((index % 6) * 90);
    }
    observer.observe(el);
  });
});
