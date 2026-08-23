<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Test Socioemocional — UGF</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
  @vite(['resources/css/rol-flow.css'])
</head>
<body class="rf-page">

<div class="rf-test-wrap">

  <div class="rf-test-header">
    <h2>Test Socioemocional</h2>
    <p>Responde con sinceridad. No hay respuestas correctas o incorrectas — solo queremos conocerte mejor.</p>
    <div class="rf-test-progress-track"><div class="rf-test-progress-fill" id="rfProgressFill"></div></div>
    <p class="rf-test-progress-label"><span id="rfProgressLabel">Pregunta 1 de 18</span></p>
  </div>

  {{-- ============ FORMULARIO DEL TEST ============ --}}
  <form id="rfTestForm" action="{{ route('test.socioemocional.guardar') }}" method="POST" class="rf-card rf-question-card">
    @csrf

    @php
      $preguntas = [
        1 => '¿Disfrutas reparar o construir cosas con tus manos?',
        2 => '¿Te interesa trabajar con herramientas, máquinas o vehículos?',
        3 => '¿Prefieres actividades al aire libre en vez de estar en una oficina?',
        4 => '¿Te gusta investigar por qué ocurren las cosas antes de actuar?',
        5 => '¿Disfrutas resolver problemas lógicos o matemáticos?',
        6 => '¿Te consideras una persona curiosa que hace muchas preguntas?',
        7 => '¿Te gusta expresarte a través del arte, la música o la escritura?',
        8 => '¿Prefieres tareas donde puedas ser original en lugar de seguir un manual?',
        9 => '¿Disfrutas imaginar ideas o soluciones poco convencionales?',
        10 => '¿Te motiva ayudar a otras personas a resolver sus problemas?',
        11 => '¿Disfrutas enseñar, explicar o guiar a otros?',
        12 => '¿Te consideras una persona empática que escucha bien a los demás?',
        13 => '¿Te gusta convencer o influir en las decisiones de otras personas?',
        14 => '¿Disfrutas liderar proyectos o tomar la iniciativa en grupo?',
        15 => '¿Te sientes cómodo tomando decisiones bajo presión?',
        16 => '¿Prefieres seguir procesos claros y ordenados?',
        17 => '¿Te gusta organizar información, datos o archivos?',
        18 => '¿Disfrutas revisar detalles para asegurarte de que todo esté correcto?',
      ];
    @endphp

    @foreach ($preguntas as $id => $texto)
      <div class="rf-question" data-question="{{ $id }}" style="{{ $id === 1 ? '' : 'display:none;' }}">
        <p class="rf-question-count">Pregunta {{ $id }} de 18</p>
        <p class="rf-question-text">{{ $texto }}</p>
        <div class="rf-likert">
          @foreach ([1 => 'Nunca', 2 => 'Casi nunca', 3 => 'A veces', 4 => 'Casi siempre', 5 => 'Siempre'] as $val => $label)
            <label class="rf-likert-opt">
              <input type="radio" name="answers[{{ $id }}]" value="{{ $val }}" required>
              <span class="rf-likert-dot">{{ $val }}</span>
              <small>{{ $label }}</small>
            </label>
          @endforeach
        </div>
      </div>
    @endforeach

    {{-- Reflexión final --}}
    <div class="rf-question rf-reflection" data-question="19" style="display:none;">
      <p class="rf-question-count">Para terminar</p>
      <p class="rf-question-text">¿Hay algo más que quieras contarnos sobre tus intereses o metas?</p>
      <textarea name="reflection" maxlength="1000" placeholder="Opcional — cuéntanos lo que quieras (máx. 1000 caracteres)"></textarea>
    </div>

    <div class="rf-test-nav">
      <button type="button" class="rf-btn rf-btn-ghost" id="rfBtnPrev" disabled>Atrás</button>
      <button type="button" class="rf-btn rf-btn-primary" id="rfBtnNext">Siguiente</button>
      <button type="submit" class="rf-btn rf-btn-primary" id="rfBtnSubmit" style="display:none;">Ver mi resultado</button>
    </div>
  </form>

  {{-- ============ RESULTADO (se rellena vía JS tras el envío) ============ --}}
  <div class="rf-card rf-result-card" id="rfResultCard" style="display:none;">
    <div class="rf-result-badge" id="rfResultAfinidad">--%</div>
    <p class="rf-intro-eyebrow">Tu carrera sugerida</p>
    <h2 class="rf-result-career" id="rfResultCarrera"></h2>
    <p class="rf-result-reason" id="rfResultRazon"></p>

    <div class="rf-strengths" id="rfResultFortalezas"></div>

    <div class="rf-uni-list" id="rfResultUniversidades"></div>

    <ul class="rf-alt-careers" id="rfResultAlternativas">
      <h4>También podrían interesarte</h4>
    </ul>

    <div class="rf-intro-actions" style="margin-top:1.5rem;">
      <a href="{{ route('becas.index') }}" class="rf-btn rf-btn-primary">Explorar becas</a>
      <a href="{{ route('perfil') }}" class="rf-btn rf-btn-outline">Ir a mi perfil</a>
    </div>
  </div>

  <div class="rf-alert rf-alert-error" id="rfErrorBox" style="display:none;"></div>

</div>

<script>
(function () {
  const form          = document.getElementById('rfTestForm');
  const questions     = Array.from(form.querySelectorAll('.rf-question'));
  const total         = questions.length;
  const progressFill  = document.getElementById('rfProgressFill');
  const progressLabel = document.getElementById('rfProgressLabel');
  const btnPrev       = document.getElementById('rfBtnPrev');
  const btnNext       = document.getElementById('rfBtnNext');
  const btnSubmit     = document.getElementById('rfBtnSubmit');
  const errorBox      = document.getElementById('rfErrorBox');
  let index = 0;

  function render() {
    questions.forEach((q, i) => q.style.display = i === index ? 'block' : 'none');
    btnPrev.disabled = index === 0;
    const isLast = index === total - 1;
    btnNext.style.display = isLast ? 'none' : 'inline-flex';
    btnSubmit.style.display = isLast ? 'inline-flex' : 'none';

    const answeredQuestions = Math.min(index + 1, 18);
    progressFill.style.width = ((answeredQuestions) / 18 * 100) + '%';
    progressLabel.textContent = isLast
      ? 'Última pregunta'
      : `Pregunta ${index + 1} de 18`;
  }

  function currentQuestionValid() {
    const q = questions[index];
    if (q.dataset.question === '19') return true;
    return !!q.querySelector('input[type=radio]:checked');
  }

  btnNext.addEventListener('click', () => {
    if (!currentQuestionValid()) {
      errorBox.style.display = 'block';
      errorBox.textContent = 'Por favor selecciona una opción antes de continuar.';
      return;
    }
    errorBox.style.display = 'none';
    index = Math.min(index + 1, total - 1);
    render();
  });

  btnPrev.disabled = true;
  btnPrev.addEventListener('click', () => {
    index = Math.max(index - 1, 0);
    render();
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!currentQuestionValid() && questions[index].dataset.question !== '19') return;

    btnSubmit.disabled = true;
    btnSubmit.textContent = 'Calculando...';
    errorBox.style.display = 'none';

    try {
      const formData = new FormData(form);
      const res = await fetch('{{ route('test.socioemocional.guardar') }}', {
        method: 'POST',
        headers: { 
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: formData,
      });

      const json = await res.json();

      if (!res.ok || !json.success) {
        throw new Error(json.message || 'Error al procesar la respuesta del servidor.');
      }

      mostrarResultado(json.data);
    } catch (err) {
      errorBox.style.display = 'block';
      errorBox.textContent = err.message || 'Ocurrió un error al calcular tu resultado. Intenta de nuevo.';
      btnSubmit.disabled = false;
      btnSubmit.textContent = 'Ver mi resultado';
    }
  });

  function mostrarResultado(data) {
    form.style.display = 'none';
    document.querySelector('.rf-test-header').style.display = 'none';

    document.getElementById('rfResultAfinidad').textContent = (data.afinidad || 80) + '%';
    document.getElementById('rfResultCarrera').textContent = data.carrera_principal || 'Carrera Sugerida';
    document.getElementById('rfResultRazon').textContent = data.razonamiento || '';

    const fortalezasBox = document.getElementById('rfResultFortalezas');
    fortalezasBox.innerHTML = '';
    (data.fortalezas_detectadas || []).forEach(f => {
      const chip = document.createElement('span');
      chip.className = 'rf-strength-chip';
      chip.textContent = f;
      fortalezasBox.appendChild(chip);
    });

    const uniBox = document.getElementById('rfResultUniversidades');
    uniBox.innerHTML = '';
    (data.universidades_sugeridas || []).forEach(u => {
      const item = document.createElement('div');
      item.className = 'rf-uni-item';
      item.innerHTML = `<div><strong>${u.nombre}</strong><span>${u.detalle}</span></div>`;
      uniBox.appendChild(item);
    });

    const altList = document.getElementById('rfResultAlternativas');
    (data.carreras_alternativas || []).forEach(c => {
      const li = document.createElement('li');
      li.innerHTML = `<strong>${c.nombre}</strong> — ${c.motivo}`;
      altList.appendChild(li);
    });

    document.getElementById('rfResultCard').style.display = 'block';
    document.getElementById('rfResultCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  render();
})();
</script>

</body>
</html>