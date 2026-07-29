<!-- FOOTER -->
    @vite('resources/css/footer.css')
  <footer class="footer">
    <div class="container footer-inner">
      <div class="footer-brand">
        <a class="nav-logo" href="#"> UGF</a>
        <p>Plataforma de Becas El Salvador</p>
      </div>
      <div class="footer-links">
        <div>
          <h5>Plataforma</h5>
          <a href="#">Test Socioemocional</a>
          <a href="#universidades">Mapa de Universidades</a>
          <a href="{{ route('becas.calendario') }}">Calendario de Becas</a>
        </div>
        <div>
          <h5>Comunidad</h5>
          <a href="#hub-social">Hub Social</a>
          <a href="#">Padrinos</a>
          <a href="#testimonios">Testimonios</a>
        </div>
        <div>
          <h5>Empresa</h5>
          <a href="#nosotros">Sobre nosotros</a>
          <a href="#">Contacto</a>
          <a href="#">Privacidad</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>Página sin fines de lucro. Imagenes con fines educativos.</p>
    </div>
  </footer>