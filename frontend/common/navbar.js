// /yoga00/frontend/common/navbar.js

function generaNavbar() {
  let navbarHTML = `
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <div class="container-fluid">
        <a id="logo-navbar" class="navbar-brand fw-bold home-link" href="#">White Yoga</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          
          <ul class="navbar-nav me-auto">
            
            <li class="nav-item">
              <a id="homeLink" class="nav-link active home-link" href="#">Home</a>
            </li>
            
            <li class="nav-item">
              <a id="lezioniLink" class="nav-link" href="#">Lezioni</a>
            </li>
            
            ${isLoggedIn() ? `
            <li class="nav-item">
              <a id="areaPersonaleLink" class="nav-link" href="#">Area Personale</a>
            </li> `: ''}
            
            ${isCurrentUserAdmin() ? `
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Admin
              </a>
              <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item vaiGestioneUtente" href="#">
                        Gestione Utenti
                    </a>
                </li>
                <li>
                    <a class="dropdown-item vaiGestioneLezioni" href="#">
                        Gestione Lezioni
                    </a>
                </li>
                <li>
                    <a class="dropdown-item vaiGestionePrenotazioni" href="#">
                        Gestione Prenotazioni
                    </a>
                </li>
              </ul>
            </li>
            ` : ''}
            
          </ul>

          <!-- Area utente / login / logout -->
          <div class="d-flex align-items-center">
    `;

  if (utente_corrente) {
    navbarHTML += `
            <span class="me-3 text-muted">
                  <i class="fa fa-user"></i> ${utente_corrente.nome_utente}
                  <span class="badge bg-${utente_corrente.admin ? 'danger' : 'secondary'} ms-1">
                    ${utente_corrente.admin ? 'Admin' : 'User'}
                  </span>
                </span>
                <button class="btn btn-outline-danger btn-sm logout-button">
                  <i class="fa fa-sign-out"></i> Logout
                </button>
        `;
  } else {
    navbarHTML += `
            <button class="btn btn-primary login-button"
                    id="login-button">
                <i class="fa fa-sign-in"></i>Login            
            </button>
        `;
  }

  navbarHTML += `
                    </div>
                </div>
            </div>
        </nav>
    `;

  // Inietto l'thml della navabar appena creata
  document.getElementById('contenitore-navbar').innerHTML = navbarHTML;
}