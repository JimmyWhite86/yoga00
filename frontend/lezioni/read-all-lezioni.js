function mostraLezioni() {
  inviaRichiesta("/../backend/api/lezioni/search_all.php", data => {

    // Per debug
    console.log("Dati ricevuti: ", data);

    // Controllo se ci sono lezioni da mostrare
    if (data.Lezione && data.Lezione.length > 0) {
      let read_lezioni_html = products_table(data.Lezione);
      $("#page-content").html(read_lezioni_html);
    } else {
      $("#page-content").html("<p>Nessuna lezione trovata</p>");
    }

  });
  /*let read_lezioni_html = products_table(data.lezioni);
  $("#page-content").html(read_lezioni_html);*/
}