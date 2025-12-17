// yoga00/frontend/common/app.js



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
// ------------------------------------------------


// FUNZIONI DI NAVIGAZIONE
document.addEventListener('DOMContentLoaded', function () {

    // Controllo lo stato della sessione
    controlloStatoSessione();


    // Homepage
    document.addEventListener('click', function (e) {
        if(e.target.closest('#logo-navbar' || '#homeLink')) {
            e.preventDefault();
            generaHomepage();
        }
    });

    // Area personale per utenti loggati

    // Le mie prenotazioni

    // Lezioni



})



