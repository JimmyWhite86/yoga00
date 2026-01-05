// /Applications/MAMP/htdocs/yoga00/frontend/assets/js/common/login.js


// Variabile dove memorizzo i dati dell'utente loggato.
// La inizializzo a null = utente non loggato.
let utente_corrente = null;

// ------------------------------------------------

// FUNZIONI PRINCIPALI

// CONTROLLO STATO SESSIONE
// Controlla se c'è una sessione attiva
function controlloStatoSessione() {

  // Chiamo l'endpoint check_session.php tramite AJAX. Usa la funzione inviaRichiesta
  // Ricevo una risposta JSON dal server
  // Questo endpoint non richiede parametri
  inviaRichiesta("auth/check_session.php", data => {

    // data = risposta JSON dal server
    // Può essere:
    //   { "logged_in": true, "utente": {...} }
    //    oppure
    //   { "logged_in": false }

    // In base al valore di logged_in, contenuto nella risposta
    if(data.logged_in) {                  // Se logged_in è true => utente loggato;
      utente_corrente = data.utente;      // Salvo i dati dell'utente nella variabile
      generaNavbar()
    } else {                              // Se logged_in è false => utente non loggato;
      utente_corrente = null;             // Imposto la variabile a null
      generaNavbar()
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
                <button type="button" class="btn btn-secondary mostra-lezioni-button" disabled>
                  Annulla <!-- TODO: Farlo funzionare -->
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
`;

  // Sostituisco tutto il contenuto di page-content con il form
  // innerHTML = imposta contenuto HTML di un elemento
  document.getElementById('page-content').innerHTML = loginHtml;

  // Aggiorno il titolo della pagina
  const titoloDellaPagina = "LOGIN";
  cambiaTitoloPagina(titoloDellaPagina);
}


// GESTIONE DEL LOGIN AVVENUTO CON SUCCESSO
// Questa funzione viene chiamata in automatico quando login.php risponde con successo
function gestisciLogin(data) {

  // Per debug => così capisco se la funzione viene effettivamente chiamata dopo il login
  console.log("gestisciLogin() => Caricata ok");
  console.log("Dati utente: ", data.utente);

  utente_corrente = data.utente;      // setto i dati dell'utente
  generaNavbar();                     // Genero la navbar personalizata con dati utente
  generaAreaPersonale();              // Richiamo la funzione che mostra l'area personale

  // Messaggio di benvenuto
  // TODO: sostituire con toast o notifica piu "elegante"
  alert(`Benvenuto ${utente_corrente.nome_utente}! Ruolo: ${utente_corrente.admin}`);
}

// LOGOUT
// Termina la sessione dell'utente sul server
function logout() {
  inviaRichiesta("auth/logout.php", () => {    // Chiamo l'endpoint logout.php
    utente_corrente = null;             // Imposto la variabile utente a null
    generaNavbar();                     // Genero la nav bar per utente generico
    mostraLezioni();                    // TOrno alla pagina che mostra le lezioni
    alert("Logout effettuato con succecsso");  // TODO: Sostituire con toast o notifica piu "elegante"
  });
}


// UTENTE LOGGATO
// Controllo se è presente un utente loggato
function isLoggedIn() {
  return utente_corrente;
}


// UTENTE ADMIN
// Verifico se l'utente loggato è un admin
function isCurrentUserAdmin() {
  return utente_corrente && utente_corrente.admin;
}





/*
   ================================================
          NOTE SULL'EVENT DELEGATION
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