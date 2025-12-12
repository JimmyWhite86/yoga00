const BASEURL = '../../yoga00/'

function inviaRichiesta(api, callback, method = "GET", body) {
  const fetchPromise = fetch(BASEURL + api, {
    method,
    headers: body ? {'Content-Type': 'application/json'} : undefined,
    body
  });
  fetchPromise
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
      }
      return response.json();
    })
    .then(data => callback(data))
    .catch((error) => {
      messaggio = error.message || 'Errore sconosciuto';
      console.error(`Errore: ${messaggio}`);
    })
}


function card_lezione(lezioni) {

  let cards_html = `<div class="row">`; // Container per le card

  $.each(lezioni, function (key, val) {

    cards_html += `
    <div class="card" style="width: 18rem;">
      <img class="card-img-top" src="..." alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">${val.lezione_id} ${val.nome}</h5>
        <p class="card-text">${val.descrizione}</p>
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Giorno della settimana: ${val.giorno_settimana}</li>
        <li class="list-group-item">Orario: dalle ${val.ora_inizio} alle ${val.ora_fine}</li>
        <li class="list-group-item">Insegnante: ${val.insegnante}</li>
        <li class="list-group-item">Posti totali: ${val.posti_totali}</li>
      </ul>
      <div class="card-body">
        <!--<a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>-->
        <div class='btn-group btn-group-sm'>
            <button class='btn btn-primary me-2 read-one-product-button' data-id='${val.lezione_id}'>
              <span class='fa fa-eye'></span> <small>Leggi</small>
            </button>
            <button class='btn btn-info me-2 update-product-button' data-id='${val.lezione_id}'>
              <span class='fa fa-edit'></span> <small>Modifica</small>
            </button>
            <button class='btn btn-danger delete-product-button' data-id='${val.lezione_id}'>
              <span class='fa fa-remove'></span> <small>Cancella</small>
            </button>
          </div>
      </div>
    </div>
  `;
  });

    cards_html += `</div>`;
    return cards_html;
}
