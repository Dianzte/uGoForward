<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <h2>{{ __('Test Socioemocional') }}</h2>
    <p>{{ __('Answer sincerely. There are no right or wrong answers — we just want to know you better.') }}</p>
    <div class="rf-test-progress-track"><div class="rf-test-progress-fill" id="rfProgressFill"></div></div>
    <p class="rf-test-progress-label"><span id="rfProgressLabel">{{ __('Question 1 of 18') }}</span></p>
  </div>

  {{-- ============ FORMULARIO DEL TEST ============ --}}
  <form id="rfTestForm" action="{{ route('test.socioemocional.guardar') }}" method="POST" class="rf-card rf-question-card">
    @csrf

    @php
      $preguntas = [
            1 => __('¿Disfrutas reparar o construir cosas con tus manos?'),
            2 => __('¿Te interesa trabajar con herramientas, máquinas o vehículos?'),
            3 => __('¿Prefieres actividades al aire libre en vez de estar en una oficina?'),
            4 => __('¿Te gusta investigar por qué ocurren las cosas antes de actuar?'),
            5 => __('¿Disfrutas resolver problemas lógicos o matemáticos?'),
            6 => __('¿Te consideras una persona curiosa que hace muchas preguntas?'),
            7 => __('¿Te gusta expresarte a través del arte, la música o la escritura?'),
            8 => __('¿Prefieres tareas donde puedas ser original en lugar de seguir un manual?'),
            9 => __('¿Disfrutas imaginar ideas o soluciones poco convencionales?'),
            10 => __('¿Te motiva ayudar a otras personas a resolver sus problemas?'),
            11 => __('¿Disfrutas enseñar, explicar o guiar a otros?'),
            12 => __('¿Te consideras una persona empática que escucha bien a los demás?'),
            13 => __('¿Te gusta convencer o influir en las decisiones de otras personas?'),
            14 => __('¿Disfrutas liderar proyectos o tomar la iniciativa en grupo?'),
            15 => __('¿Te sientes cómodo tomando decisiones bajo presión?'),
            16 => __('¿Prefieres seguir procesos claros y ordenados?'),
            17 => __('¿Te gusta organizar información, datos o archivos?'),
            18 => __('¿Disfrutas revisar detalles para asegurarte de que todo esté correcto?'),
      ];
    @endphp

    @foreach ($preguntas as $id => $texto)
      <div class="rf-question" data-question="{{ $id }}" style="{{ $id === 1 ? '' : 'display:none;' }}">
        <p class="rf-question-count">{{ __('Question') }} {{ $id }} {{ __('of') }} 18</p>
        <p class="rf-question-text">{{ $texto }}</p>
        <div class="rf-likert">
          @foreach ([1 => __('Never'), 2 => __('Almost never'), 3 => __('Sometimes'), 4 => __('Almost always'), 5 => __('Always')] as $val => $label)
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
      <p class="rf-question-count">{{ __('To finish') }}</p>
      <p class="rf-question-text">{{ __('Is there anything else you would like to tell us about your interests or goals?') }}</p>
      <textarea name="reflection" maxlength="1000" placeholder="{{ __('Optional — tell us what you want (max 1000 characters)') }}"></textarea>
    </div>

    <div class="rf-test-nav">
      <button type="button" class="rf-btn rf-btn-ghost" id="rfBtnPrev" disabled>{{ __('Back') }}</button>
      <button type="button" class="rf-btn rf-btn-primary" id="rfBtnNext">{{ __('Next') }}</button>
      <button type="submit" class="rf-btn rf-btn-primary" id="rfBtnSubmit" style="display:none;">{{ __('View my result') }}</button>
    </div>
  </form>

  {{-- ============ RESULTADO (se rellena vía JS tras el envío) ============ --}}
  <div class="rf-card rf-result-card" id="rfResultCard" style="display:none;">
    <div class="rf-result-badge" id="rfResultAfinidad">--%</div>
    <p class="rf-intro-eyebrow">{{ __('Your suggested career') }}</p>
    <h2 class="rf-result-career" id="rfResultCarrera"></h2>
    <p class="rf-result-reason" id="rfResultRazon"></p>

    <div class="rf-strengths" id="rfResultFortalezas"></div>

    <div class="rf-uni-list" id="rfResultUniversidades"></div>

    <ul class="rf-alt-careers" id="rfResultAlternativas">
      <h4>{{ __('You might also be interested in') }}</h4>
    </ul>

    <div class="rf-intro-actions" style="margin-top:1.5rem;">
      <a href="{{ route('becas.index') }}" class="rf-btn rf-btn-primary">{{ __('Explore scholarships') }}</a>
      <a href="{{ route('perfil') }}" class="rf-btn rf-btn-outline">{{ __('Go to my profile') }}</a>
    </div>
  </div>

  <div class="rf-alert rf-alert-error" id="rfErrorBox" style="display:none;"></div>

</div>

<script>
(function () {
  const translations = {
    lastQuestion: '{{ __('Last question') }}',
    questionOf: '{{ __('Question') }} %d {{ __('of') }} 18',
    selectOption: '{{ __('Please select an option before continuing.') }}',
    calculating: '{{ __('Calculating...') }}',
    viewResult: '{{ __('View my result') }}',
    apiError: '{{ __('Error processing the server response.') }}',
    calcError: '{{ __('An error occurred while calculating your result. Try again.') }}'
  };
  
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
      ? translations.lastQuestion
      : translations.questionOf.replace('%d', (index + 1));
  }

  function currentQuestionValid() {
    const q = questions[index];
    if (q.dataset.question === '19') return true;
    return !!q.querySelector('input[type=radio]:checked');
  }

  btnNext.addEventListener('click', () => {
    if (!currentQuestionValid()) {
      errorBox.style.display = 'block';
      errorBox.textContent = translations.selectOption;
      return;
    }
    errorBox.style.display = 'none';
    index = Math.min(index + 1, total - 1);
    render();
  });

  btnPrev.addEventListener('click', () => {
    index = Math.max(index - 1, 0);
    render();
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!currentQuestionValid() && questions[index].dataset.question !== '19') return;

    btnSubmit.disabled = true;
    btnSubmit.textContent = translations.calculating;
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
        throw new Error(json.message || translations.apiError);
      }

      mostrarResultado(json.data);
    } catch (err) {
      errorBox.style.display = 'block';
      errorBox.textContent = err.message || translations.calcError;
      btnSubmit.disabled = false;
      btnSubmit.textContent = translations.viewResult;
    }
  });

  function mostrarResultado(data) {
    form.style.display = 'none';
    document.querySelector('.rf-test-header').style.display = 'none';

    document.getElementById('rfResultAfinidad').textContent = (data.afinidad || 80) + '%';
    document.getElementById('rfResultCarrera').textContent = data.carrera_principal || '{{ __('Suggested Career') }}';
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