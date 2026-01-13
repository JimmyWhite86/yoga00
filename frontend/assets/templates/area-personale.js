// /Applications/MAMP/htdocs/yoga00/frontend/assets/templates/area-personale.js

function generaAreaPersonale() {

  // Aggiorno il titolo della pagina
  const titoloDellaPagina = "Area Personale";
  cambiaTitoloPagina(titoloDellaPagina);


  // Controllo che l'utente sia loggato
  if (!utente_corrente) {
    document.getElementById('page-content').innerHTML =
      '<h2 class="text-danger">Attenzione! Utente non loggato</h2>';
    return;
  }


  // Formatto i dati dell'utente
  // Viene usato "Description List"
  //
  //
  // https://www.w3schools.com/html/html_lists.asp
  // HTML also supports description lists.
  // A description list is a list of terms, with a description of each term.
  // - The <dl> tag defines the description list
  // - The <dt> tag defines the term (name)
  // - The <dd> tag describes each term
  //
  //
  // <dt> e <dd> sono una coppia speciale di HTML -> Description list
  // Serve a creare liste strutturate "termine -> descrizione"
  //
  // <dl> -> Definition List = contenitore della lista intera
  // <dt> -> Definition Term = termine o etichetta
  // <dd> -> Definition Description = la descrizione o valore
  //
  // Vantaggi
  // - Semantico = piu performante rispetto a tabella per screen reader => aumenta accessibilità
  // - Modificalbile con Bootstrap
  // - Meno codice rispetto a tabella
  //
  let datiUtenteFormattati = `
      <dl class="row">
        <dt class="col-sm-4">ID Utente</dt>  
        <dd class="col-sm-8">${utente_corrente.utente_id}</dd>  
      
        <dt class="col-sm-4">Nome</dt>
        <dd class="col-sm-8">${utente_corrente.nome_utente}</dd>
  
        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8">${utente_corrente.email}</dd>
  
        <dt class="col-sm-4">Data di nascita</dt>
        <dd class="col-sm-8">${new Date(utente_corrente.data_nascita).toLocaleDateString('it-IT')}</dd>
  
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

  // Accodo ai dati dell'utente la parte per mostrare le prenotazioni
  areaPersonaleHTML += `
    <div class="row">
      <div class="col-lg-10 mx-auto">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">
            <h4 class="mb-0">
              <i class="fa fa-calendar"></i> Le tue prenotazioni
            </h4>
          </div>
          <div class="card-body">
            <div id="prenotazioniUtente">
                <!-- Dove viene inserito il codice HTML generato dalle funzioni sotto -->
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

  // Inietto il codice html appena creato
  document.getElementById('page-content').innerHTML = areaPersonaleHTML;

  // Carico le prenotazioni effettuate dall'utente
  caricaPrenotazioniUtente();
}


// Funzione per caricare le prenotazioni dell'utente corrente
function caricaPrenotazioniUtente() {

  // Controllo che l'utente sia presente e che sia disponibile anche l'id
  if (!utente_corrente || !utente_corrente.utente_id) {
    document.getElementById('prenotazioniUtente').innerHTML = `
      <p class="text-danger">ID utente non disponibile</p>
    `;
    return;
  }

  // Chiamo l'API per recuperare le prenotazioni dell'utente
  inviaRichiesta(`prenotazioni/read_by_utente.php?id=${utente_corrente.utente_id}`, mostraPrenotazioniUtente);
}

// Funzione per mostrare le prenotazioni nell'area personale
function mostraPrenotazioniUtente(data) {

  // Per debug
  console.log("Prenotazioni ricevute: ", data);

  // Associo il tag html in cui andrò ad iniettare l'html ad una variabile
  const containerPrenotazioni = document.getElementById('prenotazioniUtente');

  // Controllo se ci sono prenotazioni attive per quell'utente
  if (!data.Prenotazione || data.Prenotazione.length === 0) {   /* Caso in cui l'utente non ha ancora effettuato prenotazioni*/
    containerPrenotazioni.innerHTML = `
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>Non hai ancora prenotato nessuna lezione
      </div>  
    `;
    return;
  }

  // Caso in cui utente ha prenotazioni => Genero le card
  let prenotazioniHtml = '<div class="row g-3">';

  data.Prenotazione.forEach(prenotazione => {
    prenotazioniHtml += `
      
      <div class="col-md-6">
      
        <div class="card h-100">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="fa fa-book"></i> ${prenotazione.lezione_nome}
            </h5>
          </div>
          <div class="card-body">
            <ul class="list-unstyled">
              <li><strong><i class="fa fa-calendar"></i> Data:</strong> ${new Date(prenotazione.data_prenotata).toLocaleDateString('it-IT')}</li>
              <li><strong><i class="fa fa-clock-o"></i> Giorno:</strong> ${prenotazione.giorno_settimana}</li>
              <li><strong><i class="fa fa-user"></i> Insegnante:</strong> ${prenotazione.insegnante}</li>
              <li class="mt-2">
                <strong>Stato:</strong> 
                ${prenotazione.stato === 'confermata' ? 'Confermata' : 'Cancellata'}
              </li>
            </ul>
          </div>
          <div class="card-footer">
            <small class="text-muted">
              Prenotata il: ${new Date(prenotazione.prenotato_il).toLocaleDateString('it-IT')}
            </small>
            ${prenotazione.stato === 'confermata' ? `
              <button type="button" class="btn btn-sm btn-danger float-end annulla-prenotazione" data-id="${prenotazione.prenotazione_id}" disabled>
                <i class="fa fa-times"></i> Annulla
              </button>
            ` : ''}
          </div>
        </div>
      </div>
    `;
  })

  prenotazioniHtml += '</div>';
  containerPrenotazioni.innerHTML = prenotazioniHtml;

}



