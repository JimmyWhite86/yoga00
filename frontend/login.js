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
  // Metto il listener sul document (che è sempre presente) e poi controllo se il click è avvenuto sul pulsante desiderato.
  // Utile quando la UI è generata dinamicamente.
  document.addEventListener('click', function (e) {

    // e.target = elemento cliccato
    // e.target = closest('.login-button') = cerca il pulsante piu vicino con classe .login-button risalendo l'albero del DOM.
    //                                       Se clicco sull'icona dentro il pulsante e.target sarebbe lo l'icona e non il pulsante.
    //                                       closest() risale fino a trovare il button
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
  // Quando l'utente preme "Accedi" nel form di login
  // Uso 'submit' e non 'click' perchè in questo modo riesco ad intercettare anche quando l'utente preme invio da tastiera.
  // Event delegation
  document.addEventListener('submit', function(e) {

    // Controllo che il form sia quello di login (in caso di altri form nella stessa pagina).
    if (e.rarget.id === 'login-form') {

      // Disabilito l'azione di default
      e.preventDefault();

      // Leggo nome utente e password inserite nel form dall'utente e le memorizzo in due variabili
      // e.target = il form
      // querySelector => trova il primo elemento che corrisponde
      const email = e.target.querySelector("input[name='email']").value;
      const password = e.target.querySelector("input[name='password']").value;

      // Trasforma in JSON un oggetto JavaScript
      // Il server PHP si aspetta una stringa JSON nel body
      // www.w3schools.com/js/js_json_stringify.asp
      const datiLogin = JSON.stringify({email: email, password: password});


      // Invio i dati in formato JSON a login.php con la funzione sendRequest
      // - Endpoint = login.php => Verifica le credenziali
      // - Callback = gestisciLogin => funzione da eseguire quando arriva la risposta
      // - Metodo = POST => sto trasmettendo la password, non voglio dati in chiaro
      // - Body = datiLogin => Stringa JSON che contiene email e password inserite nel form
      inviaRichiesta("login.php", gestisciLogin, "POST", datiLogin);
    }
  });


  // LOGUOT
  // Gestione del logout (quando utente clicca su bottone logout)
  document.addEventListener('click', function (e) {
    if (e.target.closest('.logout-button')) {
      e.preventDefault();
      logout();     // => Chiama la funzione che gestisce il logout
    }
  });

}); // FINE LISTENER

// ------------------------------------------------

// FUNZIONI PRINCIPALI

// CONTROLLO STATO SESSIONE
// Controlla se c'è una sessione attiva
function controlloStatoSessione() {

  // Chiamo l'endpoint check_session.php tramite AJAX. Usa la funzione inviaRichiesta
  // Ricevo una risposta JSON dal server
  // Questo endpoint non richiede parametri
  inviaRichiesta("check_session.php", data => {

    // data = risposta JSON dal server
    // Può essere:
    //   { "logged_in": true, "utente": {...} }
    //    oppure
    //   { "logged_in": false }

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
// Genera e inserisce l'HTML del form di login nella pagina
function mostraFormLogin() {
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
                                <input type="text" name="email" class="form-control" required />
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

  // Sostituisco tutto il contenuto di page-content con il form
  // innerHTML = imposta contenuto HTML di un elemento
  document.getElementById('page-content').innerHTML = loginHtml;

  // Cambio il titolo della pagina
  // cambiaTitoloPagina("Login");
}


// GESTIONE DEL LOGIN AVVENUTO CON SUCCESSO
// Questa funzione viene chiamata in automatico quando login.php risponde con successo
function gestisciLogin(data) {
  utente_corrente = data.utente;      // setto i dati dell'utente
  aggiornaHTMLperUtenteLoggato();     // Aggiorno l'html della pagina con quella riservata agli utenti loggati
  mostraLezioni();                    // Richiamo la funzione che richiama tutte le funzioni

  // Messaggio di benvenuto
  // TODO: sostituire con toast o notifica piu "elegante"
  alert(`Benvenuto ${utente_corrente.nome_utente}! Ruolo: ${utente_corrente.admin}`);
}

// LOGOUT
// Termina la sessione dell'utente sul server
function logout() {
  inviaRichiesta("logout.php", () => {    // Chiamo l'endpoint logout.php
    utente_corrente = null;             // Imposto la variabile utente a null
    aggiornaHTMLperUtenteGuest();       // Aggiorno l'html della pagina con quella per utenti guest
    mostraLezioni();                    // TOrno alla pagina che mostra le lezioni
    alert("Logout effettuato con succecsso");  // TODO: Sostituire con toast o notifica piu "elegante"
  });
}


// INTERFACCIA PER UTENTE LOGGATO
// Aggiorna UI per utente loggato
// Mostra info utente e pulsante logout in cima alla pagina
function aggiornaHTMLperUtenteLoggato() {
  // Creo HTML con info utente
  const userInfo = `
        <div class="user-info text-end mb-3">
            <span class="me-2">
                <span class="fa fa-user"></span> ${utente_corrente.username} 
                <span class="badge bg-${utente_corrente.admin === true ? 'danger' : 'secondary'}">${utente_corrente.admin}</span>
            </span>
            <button class="btn btn-sm btn-outline-danger logout-button">
                <span class="fa fa-sign-out"></span> Logout
            </button>
        </div>`;


  // Verifico se le info utente esistono già
  // querySelector restituisce il primo elemento trovato o null
  const datiUtenteEsistenti = document.querySelector('.user-info');

  // Se le info non esistono, allora le aggiungo
  if (!datiUtenteEsistenti) {
    const pageContent = document.getElementById('page-content');    // Prendo il container principale
    pageContent.insertAdjacentHTML('afterbegin', userInfo);
    // insertAdjacentHTML => inserisce l'HTML in una posizione specifica
    // 'afterbegin' = come primo figlio (all'inizio del contenuto)
  }
}


// Aggiorna UI per ospite
// Mostra solo il pulsante login
function aggiornaHTMLperUtenteGuest() {

  // Controllo se esistono dati utente da una sessione precedente e nel caso rimuovo
  const datiUtenteEsistenti = document.querySelector('.user-info');
  if (datiUtenteEsistenti) {
    datiUtenteEsistenti.remove();   // remove() => elimina l'elemento dal DOM
  }

  // Creo l'HTML per un pulsant edi login
  const loginButton = `
        <div class="text-end mb-3">
            <button class="btn btn-sm btn-primary login-button">
                <span class="fa fa-sign-in"></span> Login
            </button>
        </div>`;

  // Aggiungi pulsante login se non esiste
  const existingLoginButton = document.querySelector('.login-button');
  if (!existingLoginButton) {
    const pageContent = document.getElementById('page-content');
    pageContent.insertAdjacentHTML('afterbegin', loginButton);
  }
}


// UTENTE ADMIN
// Verifico se l'utente loggato è un admin
// ritorna true se l'utente è loggato con previlegi di admin
//         false in tutti gli altri casi
// può essere usato per esempio per mostare pulsanti elimina lezione solo ad admin
function isCurrentUserAdmin() {
  return utente_corrente && utente_corrente.admin;
}





/*
   ================================================
          NOTE FINALI SULL'EVENT DELEGATION
   ================================================
 *
 * EVENT DELEGATION - Perché è necessaria?
 *
 * PROBLEMA:
 * Se faccio così:
 *
 *   document.querySelector('.login-button')
 *           .addEventListener('click', function() { ... });
 *
 * NON FUNZIONA perché quando il JavaScript viene eseguito,
 * il pulsante .login-button NON ESISTE ANCORA nel DOM!
 * Viene creato dinamicamente più tardi.
 *
 * SOLUZIONE - Event Delegation:
 *
 *   document.addEventListener('click', function(e) {
 *     if (e.target.closest('.login-button')) {
 *       // gestisci click
 *     }
 *   });
 *
 * Funziona perché:
 * 1. Il listener è su document (esiste sempre)
 * 2. Quando clicco QUALSIASI elemento, l'evento "bolla" fino a document
 * 3. Controllo se l'elemento cliccato è (o è dentro) .login-button
 * 4. Se sì, eseguo il codice
 *
 * Event Bubbling (propagazione):
 * <document>
 *   <div id="page-content">
 *     <button class="login-button">
 *       <span class="fa fa-sign-in"></span> 👈 CLICCO QUI
 *     </button>
 *   </div>
 * </document>
 *
 * L'evento "bolla" verso l'alto:
 * span → button → div → document 👈 Il listener cattura qui!
*
*   developer.mozilla.org/en-US/docs/Learn_web_development/Core/Scripting/Event_bubbling#event_delegation
*
* */