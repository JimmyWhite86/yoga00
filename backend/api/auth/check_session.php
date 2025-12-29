<?php
    // yoga00/backend/api/check_session.php
    
    
    // Verifica se l'utente ha una sessione attiva (loggato) e restituisce i dati se disponibile
    
    // Richiamo il file che contiene le funzioni condivise con le varie API
    require_once '../utils/utils_api.php';

    // Verifico se l'utente è loggato con la funzione presente in utils_session.php
    if (isLoggedIn()) {         // Se l'utente è loggato

        // Recupero i dati sotto forma di array
        $dati_utente = leggiDatiUtenteInSessione();

        // http response code 200 = Ok
        http_response_code(200);

        // Creo un oggetto JSON con due proprietà:
        echo json_encode(array(
            "logged_in" => true,      // logged_in => true e l'utente è loggato, false in caso contrario
            "utente" => $dati_utente  // utente => array con tutti i dati dell'utente
        ));

    } else {                  // Se l'utente non è loggato
        http_response_code(200);    // Ok => la richiesta è andata a buon fine, semplicemente utente non loggato.
        echo json_encode(array(
            "logged_in" => false    // nell'oggetto json metto solo is logged_in a false
        ));
    }