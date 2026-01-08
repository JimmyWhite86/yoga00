/*
* FUNZIONE INVIA RICHIESTA IN VERSIONE ASYNC AWAIT
*
* "async" permette di usare "await" dentro la funzione.
* La funzione restituisce automaticamente una promise
*
*
* Vedere slide 09_AJAX p21
*
* */

async function inviaRichiesta(api, callback, method = 'GET', body = null) {

  // Try => Provo ad eseguire il codice
  try {

    // await => aspetta che fetch() finisca prima di continuare
    // senza await il codice contiruerebbe subito e response sarebbe una Promise non risolta
    // fetch() invia la richeista http al server
    const response = await fetch(BASEURL + api, {
      method,
      headers: body ? {'Content-Type': 'application/json'} : undefined,
      body,
      credentials: 'include' // per inviare i cookie
    });

    // A questo punto la risposta è arrivata dal server
    // Ma il body non è ancora leggibile

    // Await => aspetta che JSON verga parsato
    // response.json() legge il body della risposta e lo converte in oggetto JS (prima era stringa json)
    // Anche questa fase è asincrona quindi va usato await
    const data = await response.json();

    // Ora data contiene l'oggetto JS parsato dal json ricevuto dal server

    if (response.ok) {
      // Chiamo la callback di successo passandogli i dati
      callback(null, data);
    } else {
      // Chiamo la callback di errore passandogli i dati
      callback(data, null);

      // Notifico all'utente
      alert("Si è verificato un errore: " + (data.message || response.statusText));
    }
  } catch (error) {
    // Log dell'errore nella console per debug
    console.error("Errore nella richiesta fetch:", error);

    // MOstro messaggio all'utente
    // Non mostro i dettagli tecnici dell'errore per sicurezza
    alert("Si è verificato un errore nella comunicazione con il server.");
  }
}