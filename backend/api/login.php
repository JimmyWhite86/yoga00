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
        
        if ($utente->login()) {                 // Login riuscito => imposto la sessione
            impostaDatiUtenteInSessione(
                $utente->getId(),
                $utente->getNomeUtente(),
                $utente->isAdmin(),
                $utente->getEmail()
            );
            
            http_response_code(200);
            echo json_encode(array(
               "messaggio" => "Login effettuato con successo",
                "user" => array(
                    "id" => $utente->getId(),
                    "nome" => $utente->getNomeUtente(),
                    "admin" => $utente->isAdmin(),
                    "email" => $utente->getEmail()
                )
            ));
        } else {        // Login fallito
            http_response_code(401);    // Unauthorized
            echo json_encode(array(
                "messaggio" => "Credenziali non valide"
            ));
        }
    } else {
        http_response_code(400);    // Bad Request
        echo json_encode(array(
            "messaggio" => "Dati incompleti"
        ));
    }