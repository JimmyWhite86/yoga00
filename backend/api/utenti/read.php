<?php
    
    require_once '../cors.php';
    
    
    // Viene specificato il formato della risposta
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once '../../database/Database.php';
    require_once '../../classes/Utente.php';
    
    
    // Creo una connessione al DBMS
    $database = new Database();
    $db = $database->getConnection();
    
    // Controllo la connessione al database => Utile in fase di debug
    if (!$db) {
        http_response_code(500); // response code = internal server error;
        echo json_encode(array("messaggio" => "Errore di connessione al server"));
        exit;
    }
    
    
    
    
    // Leggo e valido l'id nella richiesta GET e lo inserisco nella variabile di istanza utente_id dell'oggetto utente
    if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
        http_response_code(400);
        echo json_encode(array("messaggio" => "ID utente mancante o non valido"));
        exit;
    } else {
        $id_letto = $_GET['id'];
    }
    
    
    // Creo l'oggetto utente
    $utente = new Utente($db);
    
    $utente->setId($id_letto);
    
    if ($utente->readOne()) {
        $utente_trovato = [
            "utente_id" => $utente->getUtenteId(),
            "admin" => $utente->isAdmin(),
            "nome_utente" => $utente->getNomeUtente(),
            "cognome_utente" => $utente->getCognomeUtente(),
            "data_nascita" => $utente->getDataNascita(),
            "email" => $utente->getEmail()
        ];
        
        http_response_code(200);
        echo json_encode($utente_trovato, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(array("messaggio" => "Utente non trovato"));
    }
    
    
    
    
