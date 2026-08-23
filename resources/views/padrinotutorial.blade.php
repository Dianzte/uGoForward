<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tutorial para Padrinos — UGF</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
  @vite(['resources/css/rol-flow.css', 'resources/js/rolflow.js'])
</head>
<body class="rf-page">

  <header class="rf-tutorial-hero rf-reveal">
    <span class="rf-intro-eyebrow">Bienvenido, Padrino</span>
    <h1>Tu apoyo cambia el rumbo de un estudiante</h1>
    <p>Así es como puedes usar la plataforma UGF para conectar con estudiantes, ofrecer becas y dar seguimiento a tu impacto.</p>
  </header>

  <main class="rf-tutorial-steps">

    <article class="rf-card rf-tutorial-card rf-reveal">
      <div class="rf-tutorial-icon-row">
        <div class="rf-tutorial-num">1</div>
        <div class="rf-tutorial-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/><circle cx="12" cy="12" r="9"/></svg>
        </div>
      </div>
      <h3>Crea y ofrece oportunidades de beca</h3>
      <p>Publica becas o programas de apoyo financiero, definiendo el monto, los requisitos y el área de estudio que quieres impulsar.</p>
    </article>

    <article class="rf-card rf-tutorial-card rf-reveal">
      <div class="rf-tutorial-icon-row">
        <div class="rf-tutorial-num">2</div>
        <div class="rf-tutorial-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
      </div>
      <h3>Explora perfiles de estudiantes</h3>
      <p>Revisa los perfiles de estudiantes según su departamento, área de interés y resultado del test socioemocional para encontrar el mejor ajuste.</p>
    </article>

    <article class="rf-card rf-tutorial-card rf-reveal">
      <div class="rf-tutorial-icon-row">
        <div class="rf-tutorial-num">3</div>
        <div class="rf-tutorial-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="m9 15 2 2 4-4"/></svg>
        </div>
      </div>
      <h3>Formaliza tu compromiso de apoyo</h3>
      <p>Establece un contrato o compromiso claro con el estudiante: monto, duración y condiciones del apoyo, todo dentro de la plataforma.</p>
    </article>

    <article class="rf-card rf-tutorial-card rf-reveal">
      <div class="rf-tutorial-icon-row">
        <div class="rf-tutorial-num">4</div>
        <div class="rf-tutorial-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
        </div>
      </div>
      <h3>Da seguimiento al progreso</h3>
      <p>Consulta el avance académico del estudiante que apadrinas y mantente en contacto a través del foro y las notificaciones de la plataforma.</p>
    </article>

    <article class="rf-card rf-tutorial-card rf-reveal">
      <div class="rf-tutorial-icon-row">
        <div class="rf-tutorial-num">5</div>
        <div class="rf-tutorial-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
      </div>
      <h3>Participa en el foro</h3>
      <p>Conecta con otros padrinos y estudiantes, comparte experiencias y resuelve dudas sobre el proceso de apadrinamiento.</p>
    </article>

    <article class="rf-card rf-tutorial-card rf-reveal">
      <div class="rf-tutorial-icon-row">
        <div class="rf-tutorial-num">6</div>
        <div class="rf-tutorial-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </div>
      </div>
      <h3>Consulta tu calendario de becas</h3>
      <p>Organiza fechas límite de convocatorias, entrevistas y entregas de fondos desde el calendario integrado de becas.</p>
    </article>

  </main>

  <footer class="rf-tutorial-cta rf-reveal">
    <a href="{{ route('becas.create') }}" class="rf-btn rf-btn-primary">Publicar mi primera beca</a>
    <a href="{{ route('perfil') }}" class="rf-btn rf-btn-outline">Ir a mi perfil</a>
  </footer>

</body>
</html>
