// /yoga00/frontend/prenotazioni/create-prenotazione.js

function createPrenotazione(prenotazione) {
  inviaRichiesta(
    "prenotazioni/create.php",
        (data) => {
          alert(data.messaggio || "Prenotazione effettuata con successo");
          generaAreaPersonale();
        },
        "POST",
    JSON.stringify(prenotazione)


  );
}