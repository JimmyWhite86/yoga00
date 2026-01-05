<?php
    /**
     * API Logout Utente
     *
     * Endpoint che permette di effettuare il logout dell'utente distruggendo la sessione attiva.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/auth/logout.php
     * @package api.auth
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */


    // Richiamo il file che contiene le funzioni condivise con le varie API
    require_once '../../utils/utils_api.php';
    
    // Richiamo la funzione che distrugge la sessione
    // La funzione è salvata nel file di utilità utils_session.php
    distruggiSessione();
    
    http_response_code(200);    // ok
    echo json_encode(array(
        "messaggio" => "Logout effettuato con successo"
    ));