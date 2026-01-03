// /Applications/MAMP/htdocs/yoga00/frontend/assets/js/lezioni/delete-lezione.js

// Gestione della cancellazione di una lezione
// Event Delegation => catturo il click su bottone generati dinamicamente

$(document).on('click', '.delete-product-button', function (e) {

  // Prevego il comportamento di default del bottone
  e.preventDefault();

  // recupero l'id della lezione che voglio eliminare
  // $(this) = il bottone cliccato
  // .attr('data-id') legge l'attributo HTML data-id="..." impostato nella card
  const lezione_id = $(this).attr('data-id');

  // Bootbox
  // https://bootboxjs.com/
  // A small JavaScript library which allows you to create programmatic dialog boxes using Bootstrap modals,
  // without having to worry about creating, managing, or removing any of the required DOM elements or JavaScript event handlers.
  bootbox.confirm({
    title: "Attenzione!",                                       // Titolo del modal
    message: "Sei sicurə di voler eliminare questa lezione?",   // Messaggio principale
    swapButtonOrder: true,                                      // Inverte ordine dei bottoni => Annulla a sinistra / Conferma a destra
    buttons: {
      confirm: {  // Bottone di conferma cancellazione
        label: '<span class="fa fa-check"></span>Conferma',     // Testo e icona del bottone
        className: 'btn-danger'                                 // Classe CSS del bottone
      },
      cancel: {   // Bottone per annullare la cancellazione
        label: '<span class="fa fa-times"></span>Annulla',      // Testo e icona del bottone
        className: 'btn-secondary'                              // Classe CSS del bottone
      }
    },
    callback: function (result) {                         // Callback eseguita alla chiusura del modal
      // result = true  => utente ha premuto "Conferma"
      // result = false => utente ha premuto "Annulla" o chiuso la modal
      if (result) {
        // Invia richiesta HTTP DELETE all'endpoint del backend
        // Passa mostraLezioni() come callback di successo
        // Al termine dell'eliminazione, ricarica automaticamente la lista lezioni
        // Usa DELETE come metodo HTTP
        inviaRichiesta('lezioni/delete.php?id='+lezione_id, mostraLezioni, "DELETE" );
      }
      // Se result === false → non fa nulla (l'utente ha annullato)
    }
  });
});