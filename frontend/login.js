// ------------------------------------------------

// Gestione delle autenticazioni con sessioni in PHP
// Viene usato JavaScript Vanilla

// Struttura generale del file:
// - All'avvio della pagina controlla se c'è già una sessione attiva (funzione controlloStatoSessione();)
// - Mostra il form per il login quando viene cliccato l'apposito pulsante
// - Gestisce il submit del form inviando i dati a login.php
// - Gestisce il logut tramite il file logout.php
// - Aggiorna l'interfaccia in base allo stato dell'utente.

// ------------------------------------------------

// Variabile dove memorizzo i dati dell'utente loggato.
// La inizializzo a null = utente non loggato.
let utente_corrente = null;

// ------------------------------------------------

// LISTENER
// Eseguo il codice quando il DOM è completamente caricato.
// All'interno inserisco gli event listener principali
// In JQUERY => $(document).ready()
document.addEventListener('DOMContentLoaded', function() {


  // Controllo lo stato della sessione
  controlloStatoSessione();


  // FORM LOGIN
  // Mostro il form di login quando l'utente clicca sul bottone login
  // Event Delegation
  document.addEventListener('click', function (e) {

    if (e.target.closest('.login-button')) {

      // Disabilito l'evento di default del componente
      // developer.mozilla.org/en-US/docs/Web/API/Event/preventDefault
      // w3schools.com/jsref/event_preventdefault.asp
      e.preventDefault();

      // Richiamo la funzione che carica l'html del form
      mostraFormLogin();
    }
  });


  // LOGIN SUBMIT
  // Gestisco il submit del form di login
  // Event delegation
  document.addEventListener('submit', function(e) {

    // Disabilito l'azione di default
    e.preventDefault();

    // Leggo nome utente e password inserite nel form dall'utente e le memorizzo in due variabili
    const email = e.target.querySelector("input[name='email']").value;
    const password = e.target.querySelector("input[name='password']").value;

    // Trasforma in JSON un oggetto JavaScript
    // www.w3schools.com/js/js_json_stringify.asp
    const datiLogin = JSON.stringify({email, password});

    // Invio i dati in formato JSON a login.php con la funzione sendRequest
    inviaRichiesta("login.php", gestisciLogin, "POST", datiLogin);
  });


  // LOGUOT
  // Gestione del logout
  document.addEventListener('click', function (e) {
    if (e.target.closest('.logout-button')) {
      e.preventDefault();
      logout();
    }
  });

}); // FINE LISTENER

// ------------------------------------------------

// FUNZIONI CHIAMATE NEI LISTENER

// CONTROLLO SESSIONE
// Controlla se c'è una sessione attiva
function controlloStatoSessione() {

  // Chiamo l'endpoint check_session.php tramite AJAX. Usa la funzione inviaRichiesta
  // Ricevo una risposta JSON dal server
  inviaRichiesta("check_session.php", data => {

    // In base al valore di logged_in, contenuto nella risposta
    if(data.logged_in) {                  // Se logged_in è true => utente loggato;
      utente_corrente = data.utente;      // Salvo i dati dell'utente nella variabile
      aggiornaHTMLperUtenteLoggato();     // Aggiorno l'html della pagina con quella riservata agli utenti loggati
    } else {                              // Se logged_in è false => utente non loggato;
      utente_corrente = null;             // Imposto la variabile a null
      aggiornaHTMLperUtenteGuest();       // Aggiorno l'html della pagina con quella per utenti guest
    }
  });
}


// HTML DEL FORM DI LOGIN
function showLoginForm() {
  const loginHtml = `
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form id="login-form">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="username" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required />
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <span class="fa fa-sign-in"></span> Accedi
                            </button>
                            <button type="button" class="btn btn-secondary mostra-lezioni-button">
                                Annulla
                            </button>
                        </form>
                        <!--<div class="mt-3">
                            <small>Demo credentials:<br>
                            Admin: admin / admin123<br>
                            User: user / admin123</small>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>`;

  document.getElementById('page-content').innerHTML = loginHtml;
  cambiaTitoloPagina("Login");
}


//
function gestisciLogin(data) {
  utente_corrente = data.utente;
  aggiornaHTMLperUtenteLoggato();     // Aggiorno l'html della pagina con quella riservata agli utenti loggati
  mostraLezioni();

  // Per dubug TODO: Rimuovere / Modificare in versione definitiva
  alert(`Benvenuto ${utente_corrente.nome_utente}! Ruolo: ${utente_corrente.admin}`);
}



/*
* EVENT DELEGATION:
* - Invece di abbinare un listener a ogni pulsante, ne mette uno solo su document
* - Controllo se il click è avvenuto su un elemento con classe .login-button o suo figlio
* In questo modo funziona con elementi che vengono creati dinamicamente
*
*   developer.mozilla.org/en-US/docs/Learn_web_development/Core/Scripting/Event_bubbling#event_delegation
*
* */