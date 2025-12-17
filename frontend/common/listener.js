// yoga00/frontend/common/listener.js


// LISTENER
// Eseguo il codice quando il DOM è completamente caricato.
// All'interno inserisco gli event listener principali
// In JQUERY => $(document).ready()
document.addEventListener('DOMContentLoaded', function() {

  // Controllo lo stato della sessione
  controlloStatoSessione();

  // Mostro le lezioni nella home
  // mostraLezioni();
  // generaHomepage()

  // LOGIN
  // Gestisco il click sul pulsante di login
  document.addEventListener('click', function (e) {
    if (e.target.closest('.login-button')) {
      e.preventDefault();
      mostraFormLogin();
    }
  })


  // LOGIN SUBMIT
  // Gestisco il submit del form di login
  // Quando l'utente preme "Accedi" nel form di login
  // Uso 'submit' e non 'click' perchè in questo modo riesco ad intercettare anche quando l'utente preme invio da tastiera.
  // Event delegation
  document.addEventListener('submit', function(e) {

    // Controllo che il form sia quello di login (in caso di altri form nella stessa pagina).
    if (e.target.id === 'login-form') {

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


  // Navigazione Link
  document.addEventListener('click', function (e) {
    if (e.target.closest('.home-link')) {
      e.preventDefault();
      mostraLezioni();
    }
  })

  // Homepage
  document.addEventListener('click', function (e) {
    if(e.target.closest('#logo-navbar') || e.target.closest('#home-link')) {
      e.preventDefault();
      generaHomepage();
    }
  });


  // Area personale per utenti loggati
  document.addEventListener('click', function (e) {
    if(e.target.closest('#areaPersonaleLink')) {
      e.preventDefault();
      generaAreaPersonale();
    }
  });

  // Lezioni
  document.addEventListener('click', function (e) {
    if(e.target.closest('#lezioniLink')) {
      e.preventDefault();
      mostraLezioni();
    }
  });

  // Prenota lezione
  document.addEventListener('click', function (e) {
    if(e.target.closest)
  })




}); // FINE LISTENER
// ------------------------------------------------