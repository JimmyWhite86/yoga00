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




function products_table(products) {
  let table = `
    <table class='table table-bordered table-hover'>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Descrizione</th>
          <th>Giorno settimana</th>
          <th>Ora inizio</th>
          <th>Ora fine</th>
          <th>Insegnante</th>
          <th>Posti totali</th>
          <th>Attiva</th>
          <th class='text-center'>Azioni</th>
        </tr>
      </thead>
      <tbody>`;

  $.each(products, function(key, val) {
    table += `
      <tr>
        <td>${val.lezione_id}</td>
        <td>${val.nome}</td>
        <td>${val.descrizione}</td>
        <td>${val.giorno_settimana}</td>
        <td>${val.ora_inizio}</td>
        <td>${val.ora_fine}</td>
        <td>${val.insegnante}</td>
        <td>${val.posti_totali}</td>
        <td>${val.attiva ? "Sì" : "No"}</td>
        <td class='text-center'>
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
        </td>
      </tr>`;
  });

  table += `</tbody></table>`;
  return table;
}
