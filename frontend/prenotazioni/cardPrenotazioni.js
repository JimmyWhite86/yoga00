// /yoga00/frontend/common/cardPrenotazioni.js

// CARD PRENOTAZIONI
// Genera l'HTML per visualizzare le prenotazioni come card Bootstrap
//
// PARAMETRO:
// @param {Array} prenotazioni - Array di oggetti prenotazione
//
// RETURN:
// @return {string} - HTML completo con tutte le card
function card_prenotazione(lezioni) {

  // Controllo se l'utente è admin
  const isAdmin = isCurrentUserAdmin();

  // Inizializzo un contenitore per il codice HTML
  let cards_html = `<div class="row g-4">`; // Container per le card

  // Itero su tutte le prenotazioni
  lezioni.forEach(function (val, key) {   // JS Vanilla

    // Costruisco la singola card
    cards_html += `
    <div class="card" style="width: 18rem;">
      
      <!-- Immagine della card -->
      <!--<img class="card-img-top" src="..." alt="Card image cap">-->
      
      <!-- ID, nome e descrizione della prenotazione-->
      <div class="card-body">
        <h5 class="card-title">${val.prenotazione_id} Prenotazione Lezione ${val.lezione_id}</h5>
        <p class="card-text">Lezione: ${val.lezione_nome}</p>
        <p class="card-text">Utente: ${val.utente_email}</p>
      </div>
      
      <!-- Altre informazioni sulla prenotazione -->
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Data prenotazione: ${val.data_prenotazione}</li>
        <li class="list-group-item">Stato: ${val.stato}</li>
      </ul>
      
      <div class="card-body">
        <!--<a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>-->
        
        <!-- BOTTONI -->
        <div class='btn-group btn-group-sm'>
            
            <!-- Bottone per leggere una singola prenotazione -->
            <button class='btn btn-primary me-2 read-one-product-button readPrenotazione' data-id='${val.prenotazione_id}'>   <!--La classe readPrenotazione mi serve per andare a leggere l'evento sul bottone -->
              <span class='fa fa-eye'></span> <small>Leggi</small>
            </button>
            
            <!-- Bottone per modificare una prenotazione => Attivo solo per admin -->
            ${isAdmin ? `
              <button class='btn btn-info me-2 update-product-button' data-id='${val.prenotazione_id}'>
                <span class='fa fa-pencil'></span> <small>Modifica</small>
              </button>` : ``}
            
            <!-- Bottone per eliminare una prenotazione => Attivo solo per admin -->
            ${isAdmin ? `
              <button class='btn btn-danger delete-product-button' data-id='${val.prenotazione_id}'>
                <span class='fa fa-trash'></span> <small>Elimina</small>
              </button>` : ``}
              
        </div>
      </div> 
        `;
  });

  // Chiudo il contenitore che raccoglie tutte le card create
  cards_html += `</div>`;

  // Restituisco il codice HTML completo, con tutte le card contenute in un div
  return cards_html;
}