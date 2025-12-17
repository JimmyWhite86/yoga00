// yoga00/frontend/app.js



// ------------------------------------------------
// URL BASE
const BASEURL = '../backend/api/';
// ------------------------------------------------




// INVIA RICHIESTA
// Funzione per effettuare chiamate AJAX alle API.
// Utilizza fetch
//
// Parametri:
// @param {string} api          - Endpoint API (es: "login.php", "lezioni/search_all.php")
// @param {function} callback   - Funzione da eseguire con i dati ricevuti
// @param {string} method       - Metodo HTTP (default: "GET")
// @param {string} body         - Dati da inviare (JSON string, opzionale)
function inviaRichiesta (api, callback, method = 'GET', body = null) {
    fetch(BASEURL + api, {
        method,
        headers: body ? {'Content-Type': 'application/json'} : undefined,
        body,
        credentials: 'include'
    })
        .then(response=> {
            // Leggo JSON anche se è presente un errore
            return response.json().then(data => ({
                ok: response.ok,
                status: response.status,
                data: data
            }));
        })
        .then(result => {
            if (result.ok) {
                // Non ci sono errori => eseguo la callback normale
                callback(result.data);
            } else {
                // In caso di errori HTTP
                const msg = result.data.messagio || 'Errore sconosciuto';
                console.error(`Errore dalle API: ${result.status}: ${msg}`);

                // Mostro messaggio all'utente
                alert(`Errore: ${msg}`);

                // TODO: Cambiare alert ed eventualmente preparare una callback ad hoc
            }
        })
        .catch(error => {
            console.error("Errore: ", error);
            alert ("Errore di connessione al server");
            // TODO: modificare alert con qualcosa di piu elegante.
        })
}



function inviaRichiestaOLD(api, callback, method = "GET", body) {
  const fetchPromise = fetch(BASEURL + api, {
    method,
    headers: body ? {'Content-Type': 'application/json'} : undefined,
    body,
    credentials: 'include'
  });
  fetchPromise
    .then((response) => {                           // Viene eseguita quando arriva la risposta
      if (!response.ok) {                                     // Se la risposta è diversa da .ok => Gestisco errore
        throw new Error(`HTTP error: ${response.status}`);    // Lancio errore che viene catturato da catch
      }
      return response.json();                                 // response.ok === true => "parso" la risposta come JSON.// response.json(); restituisce una Promise che si risolve con i dati JSON "parsati"
    })
    // Gestione dei dati
    .then(data => callback(data))   // Ricevo i dati JSON parsati, eseguo la funzione callback passando i dati.
    // Gestione errori
    .catch((error) => {
      const messaggio = error.message || 'Errore sconosciuto';
      console.error(`Errore: ${messaggio}`);
    })
}
// ------------------------------------------------



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
            <button class='btn btn-primary me-2 read-one-product-button' data-id='${val.lezione_id}'>
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



// ------------------------------------------------
