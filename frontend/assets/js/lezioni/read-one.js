// yoga00/frontend/common/read-one.js

// Funzione per mostrare il dettaglio della lezione caricata
function mostraDettaglioLezione(lezione_id) {

  // Uso la funzione inviaRichiesta per chiamare l'API e ottnere i dettagli della lezione selezionata dall'utente
  inviaRichiesta(`lezioni/read.php?id=${lezione_id}`, (lezione) => {

    // Stampo a console il dettaglio della lezione
    console.log("Dettagli lezione", lezione);

    // Controllo esistenza della lezione cercata
    if (!lezione || !lezione_id) {
      document.getElementById('page-content').innerHTML =
        `<div class="alert alert-danger">Lezione non trovata</div>`;
      return;
    }

    // Costruisco l'HTML che contiene i dettagli della lezione
    const dettagliLezioneHTML = `
      
      <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
      
          <!-- Pulsante torna indietro -->
          <button class="btn btn-outline-secondary mb-4" onclick="mostraLezioni()">
            <i class="fa fa-arrow-left me-2"></i>Torna alle lezioni
          </button>
      
          <!-- Card dettaglio lezione -->
          <div class="card shadow-sm border-0 h-100">
            
            <!-- Header -->
            <div class="card-header bg-primary text-white text-center py-4">
              <h3 class="mb-0">
                <i class="fa fa-calendar-check me-3"></i>
                ${lezione.nome}
              </h3>
            </div>
      
            <!-- Body -->
            <div class="card-body p-4 p-md-5">
              <!-- Descrizione -->
              ${lezione.descrizione ? `  <!-- La descrizione della lezione potrebbe non essere presente -->
              <div class="mb-5">
                <h5 class="text-primary mb-3">
                  <i class="fa fa-info-circle me-2"></i>Descrizione
                </h5>
                <p class="lead text-muted">${lezione.descrizione}</p>
              </div>
              <hr class="border-secondary opacity-25 mb-5" />
              ` : ''}
      
              <!-- Informazioni principali -->
              <div class="row g-4 mb-5">
                <div class="col-md-6">
                  <h5 class="text-primary mb-3">
                    <i class="fa fa-clock me-2"></i>Orario e giorno
                  </h5>
                  <p class="mb-2 fs-5">
                    <strong>${lezione.giorno_settimana}</strong>
                  </p>
                  <p class="text-muted fs-5">
                    dalle <strong>${lezione.ora_inizio.substring(0,5)}</strong> alle
                    <strong>${lezione.ora_fine.substring(0,5)}</strong>
                  </p>
                </div>
      
                <div class="col-md-6">
                  <h5 class="text-primary mb-3">
                    <i class="fa fa-chalkboard-teacher me-2"></i>Insegnante
                  </h5>
                  <p class="fs-5 mb-4">${lezione.insegnante || 'Non specificato'}</p>
      
                  <h5 class="text-primary mb-3">
                    <i class="fa fa-users me-2"></i>Posti disponibili
                  </h5>
                  <span class="badge bg-info fs-5 px-4 py-2">
                    ${lezione.posti_totali} posti totali
                  </span>
                </div>
              </div>
      
              <hr class="border-secondary opacity-25 mb-5" />
      
              <!-- Form prenotazione o messaggio login -->
              <div class="text-center" id="azioniUtente">
                ${utente_corrente ? utenteLoggatoHTML(lezione.lezione_id) : utenteNonLoggatoHTML()}
              </div>              
              
              
            </div>
          </div>
        </div>
      </div>
    `;

    // Inietto l'HTML in index.html
    document.getElementById('page-content').innerHTML = dettagliLezioneHTML;
  });
}


// HTML Per utente loggato => mostro pulsante prenotazione
function utenteLoggatoHTML(lezione_id) {
  return `
    <div class="row justify-contnt-center mb-4">
      <div>
        <label for="data_prenotata" class="form-label fw-bold">
          <i class="fa fa-calendar me-2 text-primary"></i>Seleziona la data 
        </label>
        <input type="date" id="data_prenotata" class="form-control form-control-lg"
               min="${new Date().toISOString().split('T')[0]}" required>
      </div>
    </div>
  
    <div class="row text-center">
      <button class="btn btn-primary mt-3" id="prenotaLezione" data-id="${lezione_id}">
        <i class="fa fa-sign-in me-2"></i>Prenota
      </button>
    </div>
    `;
}

// HTML per utente non loggato => mostro messaggio che dice di loggarsi per prenotarsi
function utenteNonLoggatoHTML() {
  return `
    <div class="alert alert-info border-0 shadow-sm py-4">
      <i class="fa fa-info-circle fa-2x mb-3 text-primary"></i>
      <h5>Accesso richiesto</h5>
      <p>Devi essere loggato per prenotare questa lezione.</p>
      <button class="btn btn-primary login-button mt-3">
        <i class="fa fa-sign-in me-2"></i>Accedi ora
      </button>
    </div>
      `;
}