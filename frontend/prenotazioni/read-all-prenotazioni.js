// /yoga00/frontend/prenotazioni/read-all-prenotazioni.js

function mostraPrenotazioni() {
  inviaRichiesta("prenotazioni/search-all.php", data => {

    // Per debug:
    console.log("Dati ricevuti: ", data);

    // Controllo se ci sono prenotazioni da mostrare
    if (data.Prenotazione && data.Prenotazione.length > 0) {
      let read_prenotazione_html = card_prenotazione(data.Prenotazione);
      $('#page-content').html(read_prenotazione_html);
    } else {
      $("#page-content").html("<p>Nessuna prenotazione trovata</p>");
    }
  });
}