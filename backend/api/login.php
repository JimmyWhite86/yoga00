<?php

    // Richiamo il file che contiene le funzioni condivise con le varie API
    require_once '../utils/utils_api.php';
    
    // Includo la classe Utente.php
    require_once '../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Creo oggetto utente
    $utente = new Utente($db);
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che valida il JSON appena letto
    isJSONvalid($data);
    
    
    
    if (!empty($data->email) && !empty($data->password)) {
        
        $utente->setEmail($data->email);
        $utente->setPassword($data->password);
        
        if ($utente->login()) {
            impostaDatiUtenteInSessione();
        }
        
    }