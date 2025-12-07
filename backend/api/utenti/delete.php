<?php

    require_once '../cors.php';
    
    // Viene specificato il formato della risposta
    header ("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once '../../database/Database.php';
    require_once '../../classes/Utente.php';
    
    
    // Creo una connessione al DBMS
    $database = new Database();
    $db = $database->getConnection();
    
    // Controllo la connessione al database
    if (!$db) {
        http_response_code(500); // internal server error
        echo json_encode(array("messaggio" => "Errore d connessione al database"));
    }
    
    // Leggo e valido l'id nella richiesta GET e lo inserisco nella variabile utente_id dell'oggetto utente
    if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
        http_response_code(400);
        echo json_encode(array("messaggio" => "ID utente mancante o non valido"));
        exit;
    } else {
        $id_letto = $_GET['id'];
    }
    
    
    $utente = new Utente($db);      // Creo un istanza di utente
    $utente->setId($id_letto);      // Setto l'id dell'oggetto con l'id letto

    
    // Invoco il metodo delete() per cancellare l'utente selezionato
    if ($utente->delete()) {
        http_response_code(200);
        echo json_encode(array("messaggio" => "utente " . $id_letto . " eliminato con successo"));
    } else {
        http_response_code(503); // Service unavailable
        echo json_encode(array("messaggio" => "Impossibile eliminare l'utente id " . $id_letto));
    }
    
    