<?php
    
    // Richiamo il file che contiene le funzioni condivise con le varie API
    require_once '../utils/utils_api.php';
    
    if (isLoggedIn()) {
        $dati_utente = leggiDatiUtenteInSessione();
        http_response_code(200); // Ok
        echo json_encode(array(
            "logged_in" => true,
            "utente" => $dati_utente
        ));
    } else {
        http_response_code(200);    // Ok
        echo json_encode(array(
            "logged_in" => false
        ));
    }