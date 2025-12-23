// /yoga00/frontend/lezioni/delete-lezione.js

$(document).on('click', '.delete-product-button', function (e) {

  e.preventDefault();

  // recupero l'id della lezione che voglio eliminare
  const lezione_id = $(this).attr('data-id');

  // Bootbox
  // https://bootboxjs.com/
  bootbox.confirm({
    title: "Attenzione!",
    message: "Sei sicurə di voler eliminare questa lezione?",
    swapButtonOrder: true,
    buttons: {
      confirm: {
        label: '<span class="fa fa-check"></span>Conferma',
        className: 'btn-danger'
      },
      cancel: {
        label: '<span class="fa fa-times"></span>Annulla',
        className: 'btn-secondary'
      }
    },
    callback: function (result) {
      if (result) {
        inviaRichiesta('lezioni/delete.php?id='+lezione_id, mostraLezioni, "DELETE" );
      }
    }
  });
});