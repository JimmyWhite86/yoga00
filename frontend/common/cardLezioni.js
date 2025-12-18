// yoga00/frontend/common/cardLezioni.js



// CARD LEZIONI
// Genera l'HTML per visualizzare le lezioni come card Bootstrap
//
// PARAMETRO:
// @param {Array} lezioni - Array di oggetti lezione
//
// RETURN:
// @return {string} - HTML completo con tutte le card
function card_lezione(lezioni) {

  // Controllo se l'utente è admin
  const isAdmin = isCurrentUserAdmin();

  // Inizializzo un contenitore per il codice HTML
  let cards_html = `<div class="row g-4">`; // Container per le card

  // Itero su tutte le lezioni
  // $.each(lezioni, function (key, val) { => JQUERY
  lezioni.forEach(function (val, key) {   // JS Vanilla

    // Costruisco la singola card
    cards_html += `
    <div class="card" style="width: 18rem;">
      
      <!-- Immagine della card -->
      <!--<img class="card-img-top" src="..." alt="Card image cap">-->
      
      <!-- ID, nome e descrizione della lezione-->
      <div class="card-body">
        <h5 class="card-title">${val.lezione_id} ${val.nome}</h5>
        <p class="card-text">${val.descrizione}</p>
      </div>
      
      <!-- Altre informazioni sulla lezione -->
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Giorno della settimana: ${val.giorno_settimana}</li>
        <li class="list-group-item">Orario: dalle ${val.ora_inizio} alle ${val.ora_fine}</li>
        <li class="list-group-item">Insegnante: ${val.insegnante}</li>
        <li class="list-group-item">Posti totali: ${val.posti_totali}</li>
      </ul>
      
      <div class="card-body">
        <!--<a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>-->
        
        <!-- BOTTONI -->
        <div class='btn-group btn-group-sm'>
            
            <!-- Bottone per leggere una singola lezione -->
            <button class='btn btn-primary me-2 read-one-product-button readLezione' data-id='${val.lezione_id}'>   <!--La classe readLezione mi serve per andare a leggere l'evento sul bottone -->
              <span class='fa fa-eye'></span> <small>Leggi</small>
            </button>
            
            <!-- Bottone per modificare una lezione => Attivo solo per admin -->
            ${isAdmin ? `
              <button class='btn btn-info me-2 update-product-button' data-id='${val.lezione_id}'>
                <span class='fa fa-edit'></span> <small>Modifica</small>
              </button>
            `: ''}
            
            <!-- Bottone per cancellare una lezione => Attivo solo per admin -->
            ${isAdmin ? `
              <button class='btn btn-danger delete-product-button' data-id='${val.lezione_id}'>
                <span class='fa fa-remove'></span> <small>Cancella</small>
              </button>
            `: ''}
          </div>
          
      </div>
    </div>
  `;
  });

  // Chiudo il contenitore che raccoglie tutte le card create
  cards_html += `</div>`;

  // Restituisco l'html completo di tutte le card, racchiuse in un div
  return cards_html;
}
// ------------------------------------------------