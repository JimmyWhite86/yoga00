// yoga00/frontend/common/area-personale.js



function generaAreaPersonale() {

  // Aggiorno il titolo della pagina
  const titoloDellaPagina = "Area Personale";
  cambiaTitoloPagina(titoloDellaPagina);


  // Controllo che l'utente sia loggato
  if (!utente_corrente) {
    document.getElementById('page-content').innerHTML =
      '<h2 class="text-danger">Attenzione! Utente non loggato</h2>';
  }


  let datiUtenteFormattati = `
    <dl class="row">
      <dt class="col-sm-4">Nome</dt>
        <dd class="col-sm-8">${utente_corrente.nome_utente}</dd>

        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8">${utente_corrente.email}</dd>

        <dt class="col-sm-4">Data di nascita</dt>
        <dd class="col-sm-8">${utente_corrente.data_nascita || 'Non specificata'}</dd>

        <dt class="col-sm-4">Ruolo</dt>
        <dd class="col-sm-8">
          <span class="badge bg-${utente_corrente.admin ? 'danger' : 'secondary'}">
              ${utente_corrente.admin ? 'Amministratore' : 'Utente'}
          </span>
        </dd>
    </dl>
  `;

  // Inserisco i dati dell'utente formattati all'intero di una card
  let areaPersonaleHTML = `
        <div class="row mb-5">
          <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white">
                <h4 class="mb-0">I tuoi dati</h4>
              </div>
              <div class="card-body">
                <div id="dati-personali">
                  ${datiUtenteFormattati}
                </div>
              </div>
            </div>
          </div>
        </div>
  `;

  document.getElementById('page-content').innerHTML = areaPersonaleHTML;
}


