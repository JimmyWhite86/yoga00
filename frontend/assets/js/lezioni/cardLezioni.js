
/**
 * CARD LEZIONI
 *
 * Genera dinamicamente le card Bootstrap per visualizzare le lezioni
 *
 * @param {array} lezioni - Array di oggetti lezione
 * @return {string} - HTML completo con tutte le card
 *
 * @path /Applications/MAMP/htdocs/yoga00/frontend/assets/js/lezioni/cardLezioni.js
 * @author Bianchi Andrea
 * @version 1.0.0
 */


// CARD LEZIONI
// Genera l'HTML per visualizzare le lezioni come card Bootstrap
//
// PARAMETRO:
// @param {Array} lezioni - Array di oggetti lezione
//
// RETURN:
// @return {string} - HTML completo con tutte le card
//
function card_lezione(lezioni) {

  /**
   * Cambia il titolo della pagina
   *
   * Usa la funzione definita in app.js
   *
   * @param {string} titoloDellaPagina - Il nuovo titolo della pagina
   * @return {void}
   * */
  const titoloDellaPagina = "Lezioni";
  cambiaTitoloPagina(titoloDellaPagina);

  /**
   * Verifico se l'utente corrente è un admin
   *
   * Usa la funzione definita in login.js
   *
   * @return {boolean} - true se l'utente è admin, false altrimenti
   * */
  const isAdmin = isCurrentUserAdmin();

  /**
   * Inizializzo il contenitore HTML per le card
   * @type {string}
   */
  let cards_html = `<div class="container-fluid px-2 px-md-4">`;
  cards_html += `<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">`;
  // row-cols-* gestisce automaticamente le colonne responsive
  // g-4 aggiunge gap (spacing) tra le card

  // Itero su tutte le lezioni
  // $.each(lezioni, function (key, val) { => JQUERY
  lezioni.forEach(function (val, key) {   // JS Vanilla

    // Formatto il giorno della lezione
    // Lo restituisco con la prima lettera maiuscola
    const giorno = formattaLettere(val.giorno_settimana);

    // Formatto ora inizio e ora fine per rimuovere i secondi
    const oraInizio = rimuoviSecondiOrario(val.ora_inizio);
    const oraFine = rimuoviSecondiOrario(val.ora_fine);

    // Badge per stato attivo non attivo
    const badgeAttiva = val.attiva == 1
      ? '<span class="badge bg-success"><i class="fa fa-check-circle"></i>Attiva</span>'
      : '<span class="badge bg-secondary"><i class="fa fa-times-circle"></i> Non Attiva</span>';

    // Costruisco la singola card
    cards_html += `
    <div class="col">
      <div class="card h-100 shadow-sm border-0 fade-in">
        
        <!-- Intestazione della card -->
        <div class="card-header text-white" style="background: var(--primary)">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
              <i class="fa fa-om px-2"></i> ${val.nome}
            </h5>
            ${badgeAttiva}
          </div>
        </div>
        
        <!-- Corpo Card -->
        <div class="card-body d-flex flex-column">
          
          <!-- Descrizione -->
          <p class="card-text text-muted mb-3 flex-grow-1" style="min-height: 60px;">
            ${val.descrizione || 'Nessuna descrizione disponibile'}
          </p>
          
          <!-- Info Lezione con icone -->
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item d-flex align-items-center px-0 border-0 py-2">
              <i class="fa fa-calendar me-2" style="color: var(--accent); width: 20px;"></i>
              <strong>${giorno}</strong>
            </li>
            
            <li class="list-group-item d-flex align-items-center px-0 border-0 py-2">
              <i class="fa fa-clock-o me-2" style="color: var(--accent); width: 20px;"></i>
              <span>${oraInizio} - ${oraFine}</span>
            </li>
            
            <li class="list-group-item d-flex align-items-center px-0 border-0 py-2">
              <i class="fa fa-user me-2" style="color: var(--accent); width: 20px;"></i>
              <span>${val.insegnante || 'Da definire'}</span>
            </li>
            
            <li class="list-group-item d-flex align-items-center px-0 border-0 py-2">
              <i class="fa fa-users me-2" style="color: var(--accent); width: 20px;"></i>
              <span>${val.posti_totali} posti disponibili</span>
            </li>
          </ul>
          
          <!-- BOTTONI -->
          <!-- mt-auto => fa in modo che i bottoni restino nella parte bassa -->
          <div class="btn-group btn-group-sm d-flex mt-auto" role="group">
            
            <!-- Bottone per leggere una singola lezione -->
            <button class="btn btn-primary me-2 flex-fill readLezione" 
                    data-id="${val.lezione_id}"
                    title="Visualizza dettagli">
              <i class="fa fa-eye"></i> Dettagli
            </button>
            
            <!-- Se l'utente è admin => Mostro i bottoni per modificare o cancellare la lezione -->
            ${isAdmin ? `
              <!-- Bottone per modificare una lezione -->
              <button class="btn btn-info flex-fill me-2 update-product-button" 
                      data-id="${val.lezione_id}"
                      title="Modifica lezione">
                <i class="fa fa-edit"></i> Modifica
              </button>
              
              <!-- Bottone per cancellare una lezione -->
              <button class="btn btn-danger flex-fill delete-product-button" 
                      data-id="${val.lezione_id}"
                      title="Elimina lezione">
                <i class="fa fa-trash"></i> Elimina
              </button>
            ` : ''}
          </div>
          
        </div>
      </div>
    </div>
  `;
  });

  // Chiudo i contenitori
  cards_html += `</div>`; // Chiudo row
  cards_html += `</div>`; // Chiudo container

  // Restituisco l'html completo
  return cards_html;
}
// ------------------------------------------------