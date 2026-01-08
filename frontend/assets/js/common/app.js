// /Applications/MAMP/htdocs/yoga00/frontend/assets/js/common/app.js

// ------------------------------------------------
// URL BASE
// Definisce la radice comune di tutte le API backend
// Se cambio la struttura delle cartelle, devo solo modificare questa stringa
const BASEURL = '../backend/api/';
// ------------------------------------------------


// ------------------------------------------------
// INVIA RICHIESTA
// Funzione generica per effettuare chiamate AJAX alle API REST del backend
//
// Chiamata asincrona con promessa
// JavaScript promises con la funzione fetch()
// [slide 09_AJAX p15]
//
// Parametri:
// @param {string} api          =>      Endpoint relativo (es "lezioni/search_all.php")
// @param {function} callback   =>      Funzione da eseguire quando la risposta è positiva
// @param {string} method       =>      Metodo HTTP (di default GET)
// @param {string} body         =>      Dati da inviare (solo per POST e PUT). Deve essere JSON
//
function inviaRichiesta (api, callback, method = 'GET', body = null) {

  // 1. Invio della richiesta HTTP
  fetch(BASEURL + api, {
    method,                                                             // Metodo HTTP richiesto
    headers: body ? {'Content-Type': 'application/json'} : undefined,   // Imposta l'header in base alla presenza o meno di un body. Se POST/PUT => Mi serve body / Se GET => Non mi serve body
    body,                                                               // Corpo della richiesta
    credentials: 'include'                                              // Includo i cookie di sessione (per sessione php)
  })

    // 2. Primo promise
    // Gestione della risposta
    // Legge il JSON anche in caso di errore HTTP
    // Permette al backend di restituire errori strutturati
    .then(response=> {
      // Leggo JSON anche se è presente un errore

      // Per debug
      console.log("Response status:", response.status);
      console.log("Response headers:", response.headers.get('content-type'));

      // Parsing del JSON che restituisce un'altra promise
      return response.json().then(data => ({
        ok: response.ok,          // Booleano => true se httpstatus 200-299, false altrimenti
        status: response.status,  // Codice HTTP numerico
        data: data                // Il JSON parsato
      }));
    })

    // 3. Seconda Primse: Quando il JSON è pronto
    // Gestione Successo/Errore
    .then(result => {
      if (result.ok) {
        // Non ci sono errori => eseguo la callback normale
        callback(result.data);
      } else {
        // In caso di errori HTTP
        const msg = result.data.messaggio || 'Errore sconosciuto';
        console.error(`Errore dalle API: ${result.status}: ${msg}`);

        // Mostro messaggio all'utente
        alert(`Errore: ${msg}`);   // TODO: Cambiare alert ed eventualmente preparare una callback ad hoc
      }
    })

    // 4. Errori
    // Gestione errori di rete
    // Errore di rete o parsing JSON fallito (server offline, risposta non JSON)
    .catch(error => {
      console.error("Errore: ", error);
      alert ("Errore di connessione al server");
      // TODO: modificare alert con qualcosa di piu elegante.
    })
}



// ------------------------------------------------
// Funzione per cambiare il titolo della pagina
function cambiaTitoloPagina(titoloDellaPagina) {
  $("#page-title").text(titoloDellaPagina);   // Aggiorna il titolo della pagina
  document.title = titoloDellaPagina;         // Aggiorna il <title> del browser
}
// ------------------------------------------------


// ------------------------------------------------
// Funzione per restituire stringhe formattate con la prima lettera maiuscola e le altre minuscole
// Prevedo anche caso di valore mancante
// Esempio: "lunedi" → "Lunedi", "MARTEDI" → "Martedi", null → "N/D"
function formattaLettere(str) {
  return str ? str.charAt(0).toUpperCase() + str.slice(1).toLowerCase() : 'N/D';
}
// ------------------------------------------------


// ------------------------------------------------
// Funzione per rimuovere i secondi dagli orari
// Prevedo anche caso di valore mancante
// Il database restituisce orari come "14:30:00" → vogliamo solo "14:30"
function rimuoviSecondiOrario(str) {
  return str ? str.substring(0, 5) : '--:--';
}
// ------------------------------------------------



