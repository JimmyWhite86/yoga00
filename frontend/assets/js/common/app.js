// yoga00/frontend/common/app.js

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
// Parametri:
// @param {string} api          =>      Endpoint relativo (es "lezioni/search_all.php")
// @param {function} callback   =>      Funzione da eseguire quando la risposta è positiva
// @param {string} method       =>      Metodo HTTP (di default GET)
// @param {string} body         =>      Dati da inviare (solo per POST e PUT). Deve essere JSON
//
function inviaRichiesta (api, callback, method = 'GET', body = null) {
  fetch(BASEURL + api, {
    method,                                                             // Metodo HTTP richiesto
    headers: body ? {'Content-Type': 'application/json'} : undefined,   // Imposta l'header in base alla presenza o meno di un body
    body,                                                               // Corpo della richiesta
    credentials: 'include'                                              // Includo i cookie di sessione
  })
    // Gestione della risposta
    // Legge il JSON anche in caso di errore HTTP
    // Permette al backend di restituire errori strutturati
    .then(response=> {
      // Leggo JSON anche se è presente un errore
      return response.json().then(data => ({
        ok: response.ok,          // Booleano => true se httpstatus 200-299, false altrimenti
        status: response.status,  // Codice HTTP numerico
        data: data                // Il JSON parsato dal server
      }));
    })
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
    // Gestione errori di rete
    // Errore di rete o parsing JSON fallito (server offline, risposta non JSON)
    .catch(error => {
      console.error("Errore: ", error);
      alert ("Errore di connessione al server");
      // TODO: modificare alert con qualcosa di piu elegante.
    })
}
// ------------------------------------------------
// Versione modificata il 17.12.2025 per gestire anche i casi in cui l'utente inserisce dati errati in fase di login
// Prima non gestiva i casi di credenziali sbagliate



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