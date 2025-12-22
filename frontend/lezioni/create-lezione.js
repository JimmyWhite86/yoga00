// yoga00/frontend/lezioni/create-lezione.js

$(document).ready(function () {

  // Mostro il form quando l'utente fa clock su crea nuova lezione
  $(document).on('click', '.vaiGestioneLezioni', function (e) {

    e.preventDefault();

    // Creo l'html del form
    const formNuovaLezioneHTML = `
    <form id="formNuovaLezione" action="#" method="post">
    
      <label for="nomeLezione">Nome</label>
      <input type="text" name="nomeLezione" id="nomeLezione" required>
    
      <label for="descrizioneLezione">Descrizione</label>
      <input type="text" name="descrizioneLezione" id="descrizioneLezione">
    
      <label for="orarioInizioLezione">Orario di inizio</label>
      <input type="time" name="orarioInizioLezione" id="orarioInizioLezione">
    
      <label for="orarioFineLezione">Orario di inizio</label>
      <input type="time" name="orarioFineLezione" id="orarioFineLezione">
    
      <label for="giornoLezione">Giorno</label>
      <input type="date" name="giornoLezione" id="giornoLezione">
    
      <label for="insegnanteLezione">Insegnante</label>
      <input type="text" name="insegnanteLezione" id="insegnanteLezione">
    
      <label for="postiDisponibili">Posti disponibili</label>
      <input type="number" min="1" max="50" name="postiDisponibili" id="postiDisponibili" >
      
      
      <button type="submit" class="btn">
        Crea lezione
      </button>
    </form>
  `;

    // Cambio titolo della pagina
    cambiaTitoloPagina("Crea una nuova lezione");

    // Inietto l'html nella pagina
    document.getElementById("page-content").innerHTML = formNuovaLezioneHTML;

  });

  $(document).on('submit', '#formNuovaLezione', function (e) {

    e.preventDefault();

    // Recupero i dati dal form
    const dati_form = JSON.stringify(Object.fromEntries(new FormData(this))); // this = il form inviato

    // Per debug
    console.log("FORM DATA: " + dati_form);

    // Uso la funzione per inviare la richiesta
    inviaRichiesta("/lezioni/create.php", mostraLezioni, "POST", dati_form);


  });

});






