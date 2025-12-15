const BASEURL = '../../yoga00/'



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
function inviaRichiesta(api, callback, method = "GET", body) {

  // Costruzione della richiesta fetch
  // URL Completa concatenando BASEURL + API
  const fetchPromise = fetch(BASEURL + api, {

    // Opzioni della richiesta:

    // Metodo http => di default GET
    method,

    // Headers
    // Operatore ternario
    //    - Se c'è un body => imposto Content-Type: application/json (dice al server quale formato stiamo inviando)
    //    - Se non c'è un body => metto headers come undefined
    headers: body ? {'Content-Type': 'application/json'} : undefined,

    // Body (corpo della richiesta)
    // Contiene i dati da inviare
    body
  });

  // Gestione della risposta
  // fetch() restituisce una promise che viene soddisfatta quando arriva la risposta dal server.
  //
  // Flusso:
  // 1. fetch() invia richiesta → Promise pending
  // 2. Server risponde → Promise resolved
  // 3. .then() gestisce la risposta
  // 4. .catch() gestisce eventuali errori
  fetchPromise
    // Controllo lo stato HTTP
    // response è un oggetto Response con le seguenti proprietà:
    // - response.ok:           true se status 200-299, false altrimenti
    // - response.status:       codice HTTP (200, 404, 500, ecc.)
    // - response.statusText:   testo status ("OK", "Not Found", ecc.)
    // - response.headers:      headers della risposta
    // - response.body:         contenuto della risposta (stream)
    .then((response) => {                           // Viene eseguita quando arriva la risposta
      if (!response.ok) {                                     // Se la risposta è diversa da .ok => Gestisco errore
        throw new Error(`HTTP error: ${response.status}`);    // Lancio errore che viene catturato da catch
      }
      return response.json();                                 // response.ok === true => "parso" la risposta come JSON.
                                                              // response.json(); restituisce una Promise che si risolve con i dati JSON "parsati"
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

  // Inizializzo un contenitore per il codice HTML
  let cards_html = `<div class="row g-4">`; // Container per le card

  // Itero su tutte le lezioni
  lezioni.forEach(function (key, val) {

    // Costruisco la singola card
    cards_html += `
    <div class="card" style="width: 18rem;">
      <img class="card-img-top" src="..." alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">${val.lezione_id} ${val.nome}</h5>
        <p class="card-text">${val.descrizione}</p>
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Giorno della settimana: ${val.giorno_settimana}</li>
        <li class="list-group-item">Orario: dalle ${val.ora_inizio} alle ${val.ora_fine}</li>
        <li class="list-group-item">Insegnante: ${val.insegnante}</li>
        <li class="list-group-item">Posti totali: ${val.posti_totali}</li>
      </ul>
      <div class="card-body">
        <!--<a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>-->
        <div class='btn-group btn-group-sm'>
            <button class='btn btn-primary me-2 read-one-product-button' data-id='${val.lezione_id}'>
              <span class='fa fa-eye'></span> <small>Leggi</small>
            </button>
            <button class='btn btn-info me-2 update-product-button' data-id='${val.lezione_id}'>
              <span class='fa fa-edit'></span> <small>Modifica</small>
            </button>
            <button class='btn btn-danger delete-product-button' data-id='${val.lezione_id}'>
              <span class='fa fa-remove'></span> <small>Cancella</small>
            </button>
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
