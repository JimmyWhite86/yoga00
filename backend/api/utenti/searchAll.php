<?php

    require_once '../cors.php';
    
    // Viene specificato il formato della risposta
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once '../../database/DatabaseBase.php';
    require_once '../../classes/Utente.php';
    
    
    // Creo una connessione al DBMS
    $database = new DatabaseBase();
    $db = $database->getConnection();
    
    // Controllo la connessione al database => Utile in fase di debug
    if (! $db) {
        http_response_code(500);        // response code 500 = internal server error
        echo json_encode(array("messaggio" => "Errore di connessione al server"));
        exit;
    }
    
    
    // Creo un'istanza di Utente
    $utente = new Utente($db);
    
    
    // Invoco il metodo searchAll() che restituisce l'elenco di tutti gli utenti presenti nel db
    $stmt = $utente -> searchAll();
    
    if ($stmt) { // Se stmt è diverso da falls vuol dire che sono stati trovati degli utenti
        
        // Creo una coppia "utenti : [lista-utenti]
        $utenti_lista = array();
        $utenti_lista["utenti"] = array();
        
        foreach ($stmt as $row) {
            // Costruisco un array associativo che rappresenta ogni singolo Utente
            $utente_singolo = array(
                "utente_id" => $row['utente_id'],
                "admin" => $row['admin'],
                "nome_utente" => $row['nome_utente'],
                "cognome_utente" => $row['cognome_utente'],
                "data_nascita" => $row['data_nascita'],
                "email" => $row['email'],
                "password" => $row['password'] );
            
            // Aggiungo l'array appena creato al fondo di utenti_lista
            array_push($utenti_lista["utenti"], $utente_singolo);
        }
        
        http_response_code(200);        // response code 200 = ok
        echo json_encode($utenti_lista);
        
    } else {    // Nel caso di $stmt = falls => Non sono stati trovati utenti
        http_response_code(404);        // response code 404 = Not Found
        echo json_encode(array("messaggio" => "Nessun utente trovato"));
    }