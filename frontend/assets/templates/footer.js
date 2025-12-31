function generaFooter() {
  let footerHTML = `
  <footer class="bg-light border-top py-5 mt-5">
    <div class="container">
      <div class="row align-items-center text-center text-md-start">
        <!-- Logo / Nome studio -->
        <div class="col-md-4 mb-3 mb-md-0">
          <h4 class="mb-0 fw-bold">
          <i class="fa fa-spa me-2"></i> White Yoga
          </h4>
        </div>

        <!-- Nome, cognome e matricola -->
        <div class="col-md-4 mb-3 mb-md-0 text-md-center">
          <p class="mb-1 text-muted">Progetto realizzato da</p>
          <p class="mb-0 fw-bold">Bianchi Andrea</p>
          <p class="mb-0 text-muted small">Matricola: 954122</p>
        </div>

        <!-- Spazio destro (vuoto per bilanciamento) -->
        <div class="col-md-4 text-md-center">
          <p class="mb-1 text-muted">Esame di</p>
          <p class="mb-1 fw-bold">Tecnologie Web: Approcci avanzati</p>
          <p class="mb-0 text-muted small">CPS0547</p>
        </div>
      </div>
    </div>
  </footer>
  `;

  // Inietto l'thml della navabar appena creata
  document.getElementById('contenitore-footer').innerHTML = footerHTML;

}