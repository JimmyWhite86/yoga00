<?php
    // yoga00/backend/api/auth/logout.php


    // Richiamo il file che contiene le funzioni condivise con le varie API
    require_once '../../utils/utils_api.php';
    
    distruggiSessione();
    
    http_response_code(200);    // ok
    echo json_encode(array(
        "messaggio" => "Logout effettuato con successo"
    ));