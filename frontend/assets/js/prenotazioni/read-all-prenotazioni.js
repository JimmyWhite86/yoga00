// /yoga00/frontend/prenotazioni/read-all-read-one.js

function mostraPrenotazioni() {
  inviaRichiesta("prenotazioni/search_all.php", data => {

    // Per debug:
    console.log("read-all-prenotazione.js => Dati ricevuti: ", data);

    // Controllo se ci sono prenotazioni da mostrare
    if (data.Prenotazione && data.Prenotazione.length > 0) {
      let read_prenotazione_html = card_prenotazione(data.Prenotazione);
      $('#page-content').html(read_prenotazione_html);
    } else {
      $("#page-content").html("<p>Nessuna prenotazione trovata</p>");
    }
  });
}