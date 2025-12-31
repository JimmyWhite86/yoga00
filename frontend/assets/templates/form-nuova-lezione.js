// Creo l'html del form per inserire una nuova lezione nel db

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