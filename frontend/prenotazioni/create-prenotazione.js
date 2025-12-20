// /yoga00/frontend/prenotazioni/create-prenotazione.js

function createPrenotazione() {
  inviaRichiesta("prenotazioni/create.php", data => {

    // Per debug
    console.log("create-prenotazione.js => Dati ricevuti", data)



  })
}