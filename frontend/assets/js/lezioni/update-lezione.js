// /Applications/MAMP/htdocs/yoga00/frontend/assets/js/lezioni/update-lezione.js

$(document).ready(function () {

  // Mostro il form al click sul bottone
  // Il form è precompilato con i dati della lezione che si vuole modificare
  $(document).on("click", ".update-product-button", function (e) {

    // Recupero l'id della lezione che vogliamo modificare
    const lezione_id = $(this).attr("data-id"); // this = il bottone cliccato. Leggo l'attributo "data-id" del tag html

    // Carico i dati della lezione
    inviaRichiesta(`lezioni/read.php?id=${lezione_id}`, (data) => {

      // Controllo che ci siano i dati della lezione da modificare
      if (!data) {
        alert("Lezione non trovata");
        mostraLezioni();
        return;
      }

      // Creo l'html del form
      let formAggiornaLezioneHTML = `
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
              <i class="fa fa-plus-circle"></i> Modifica la lezione ${data.nome}
            </h4>
          </div>
        
          <div class="card-body">
            <form id="formModificaLezione" action="#" method="post">
        
              <!-- ID della lezione -->
              <!-- Creo un campo hidden per passare l'id della lezione che voglio modificare. -->
              <!-- Lo metto hidden perchè l'utente non deve essere in grado di modificare questo valore -->
              <input type="number" name="lezione_id" id="lezione_id" value="${data.lezione_id}" hidden>  
        
              <!-- Nome -->
              <div class="mb-3">
                <label for="nome" class="form-label">
                  <i class="fa fa-tag"></i> Nome Lezione
                </label>
                <input
                  type="text"
                  name="nome"
                  id="nome"
                  class="form-control"
                  value="${data.nome}"
                  required
                >
              </div>
        
              <!-- Descrizione -->
              <div class="mb-3">
                <label for="descrizione" class="form-label">
                  <i class="fa fa-align-left"></i> Descrizione
                </label>
                <textarea
                  name="descrizione"
                  id="descrizione"
                  class="form-control"
                  rows="3"
                  required
                >
                  ${data.descrizione || ""}
                </textarea>
              </div>
        
              <!-- Giorno -->
              <div class="mb-3">
                <label for="giorno_settimana" class="form-label">
                  <i class="fa fa-calendar"></i> Giorno della Settimana
                </label>
                <select
                  name="giorno_settimana"
                  id="giorno_settimana"
                  class="form-select"
                  required
                >
                  <option value="lunedi" ${data.giorno_settimana === "lunedi" ? "selected" : ""}>
                    Lunedì
                  </option>
                  <option value="martedi" ${data.giorno_settimana === "martedi" ? "selected" : ""}>
                    Martedì
                  </option>
                  <option value="mercoledi" ${data.giorno_settimana === "mercoledi" ? "selected" : ""}>
                    Mercoledì
                  </option>
                  <option value="giovedi" ${data.giorno_settimana === "giovedi" ? "selected" : ""}>
                    Giovedì
                  </option>
                  <option value="venerdi" ${data.giorno_settimana === "venerdi" ? "selected" : ""}>
                    Venerdì
                  </option>
                  <option value="sabato" ${data.giorno_settimana === "sabato" ? "selected" : ""}>
                    Sabato
                  </option>
                  <option value="domenica" ${data.giorno_settimana === "domenica" ? "selected" : ""}>
                    Domenica
                  </option>
                </select>
              </div>
        
              <!-- Orari -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="ora_inizio" class="form-label">
                    <i class="fa fa-clock-o"></i> Ora Inizio
                  </label>
                  <input
                    type="time"
                    name="ora_inizio"
                    id="ora_inizio"
                    class="form-control"
                    value="${data.ora_inizio.substring(0, 5)}"
                    required
                  >
                </div>
        
                <div class="col-md-6 mb-3">
                  <label for="ora_fine" class="form-label">
                    <i class="fa fa-clock-o"></i> Ora Fine
                  </label>
                  <input
                    type="time"
                    name="ora_fine"
                    id="ora_fine"
                    class="form-control"
                    value="${data.ora_fine.substring(0, 5)}"
                    required
                  >
                </div>
              </div>
        
              <!-- Insegnante -->
              <div class="mb-3">
                <label for="insegnante" class="form-label">
                  <i class="fa fa-user"></i> Insegnante
                </label>
                <input
                  type="text"
                  name="insegnante"
                  id="insegnante"
                  class="form-control"
                  value="${data.insegnante || ""}"
                  required
                >
              </div>
        
              <!-- Posti totali -->
              <div class="mb-3">
                <label for="posti_totali" class="form-label">
                  <i class="fa fa-users"></i> Posti Disponibili
                </label>
                <input
                  type="number"
                  min="1"
                  max="50"
                  name="posti_totali"
                  id="posti_totali"
                  class="form-control"
                  placeholder="20"
                  value="${data.posti_totali}"
                  required
                >
              </div>
              
              <div class="col-12">
                <label for="attiva" class="form-label fw-bold">
                  <i class="fa fa-toggle-on me-2"></i>Stato lezione
                </label>
                <select name="attiva" id="attiva" class="form-select form-select-lg" required>
                  <option value="1" ${data.attiva === 1 ? "selected" : ""}>Attiva</option>
                  <option value="0" ${data.attiva === 0 ? "selected" : ""}>Non attiva</option>
                </select>
              </div>
        
              <!-- Pulsanti -->
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                  <i class="fa fa-check"></i> Modifica Lezione
                </button>
                <button type="button" class="btn btn-secondary" onclick="mostraLezioni()">
                  <i class="fa fa-times"></i> Annulla
                </button>
              </div>
        
            </form>
          </div>
        </div>

    `;

      // Richiamo la funzione che cambia il titolo della pagina
      cambiaTitoloPagina("Aggiorna lezione");

      // Inietto l'html all'interno della pagina
      document.getElementById('page-content').innerHTML = formAggiornaLezioneHTML;
    });
  });

  // Mando la lezione aggiornata al servizio che gestisce l'update (update.php) quando l'utente invia il form
  $(document).on("submit", "#formModificaLezione", function (e) {
    e.preventDefault();

    // Recupero i dati dell'oggetto aggiornato
    const formData = JSON.stringify(Object.fromEntries(new FormData(this)));

    console.log("Dati lezione => ", formData);

    // Uso la mia funzione per inviare la richiesta
    inviaRichiesta(`lezioni/update.php`, handleModificaAvvenuta, "PUT", formData);
  });

  function handleModificaAvvenuta(data) {
    console.log("Modifica avvenuta", data);
    alert("Lezione modificata con successo!");
    mostraLezioni();
  }

});
