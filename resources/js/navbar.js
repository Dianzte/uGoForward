/* ─────────────────────────────────────
   8.1 USER MENU (dropdown de perfil)
   ───────────────────────────────────── */
const userMenu    = document.getElementById('userMenu');
const userMenuBtn = document.getElementById('userMenuBtn');

if (userMenu && userMenuBtn) {
  userMenuBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = userMenu.classList.toggle('open');
    userMenuBtn.setAttribute('aria-expanded', isOpen);
  });

  document.addEventListener('click', (e) => {
    if (!userMenu.contains(e.target)) {
      userMenu.classList.remove('open');
      userMenuBtn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      userMenu.classList.remove('open');
      userMenuBtn.setAttribute('aria-expanded', 'false');
    }
  });
}