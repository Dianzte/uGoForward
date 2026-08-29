<!-- FOOTER -->
    @vite('resources/css/footer.css')
  <footer class="footer">
    <div class="container footer-inner">
      <div class="footer-brand">
        <a class="nav-logo" href="{{ route('index') }}"> UGF</a>
        <p>{{ __('Plataforma de Becas El Salvador') }}</p>
      </div>
      <div class="footer-links">
        <div>
          <h5>{{ __('Plataforma') }}</h5>
          <a href="{{ route('index') }}#servicios">{{ __('Test Socioemocional') }}</a>
          <a href="{{ route('index') }}#universidades">{{ __('Mapa de Universidades') }}</a>
          <a href="{{ route('becas.calendario') }}">{{ __('Calendario de Becas') }}</a>
        </div>
        <div>
          <h5>{{ __('Comunidad') }}</h5>
          <a href="{{ route('index') }}#hub-social">{{ __('Hub Social') }}</a>
          <a href="{{ route('index') }}#servicios">{{ __('Padrinos') }}</a>
          <a href="{{ route('index') }}#testimonios">{{ __('Testimonios') }}</a>
        </div>
        <div>
          <h5>{{ __('Empresa') }}</h5>
          <a href="{{ route('index') }}#nosotros">{{ __('Sobre nosotros') }}</a>
          <a href="#">{{ __('Contacto') }}</a>
          <a href="#">{{ __('Privacidad') }}</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>{{ __('Página sin fines de lucro. Imagenes con fines educativos.') }}</p>
    </div>
  </footer>