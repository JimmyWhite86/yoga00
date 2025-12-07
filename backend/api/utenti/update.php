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
        http_response_code(500);        // response code 500 = internal server error
        echo json_encode(array("messaggio" => "Errore di connessione al server"));
        exit;
    }
    
    
    // Lettura e validazione del JSON
    $data = json_decode(file_get_contents("php://input"));  // Leggo i dati nel body della request (metodo PUT/POST/PATCH)
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array(
            "messaggio" => "JSON non valido",
            "errore" => json_last_error_msg()
        ));
        exit;
    }
    
    // Campi obbligatori
    $campi_obbligatori = [
        'utente_id',
        'nome_utente',
        'cognome_utente',
        'data_nascita',
        'email'
    ];
    $campi_mancanti = [];
    
    foreach ($campi_obbligatori as $campo) {
        if (!isset($data->$campo) || $data->$campo === '') {
            $campi_mancanti[] = $campo;
        }
    }
    
    if (!empty($campi_mancanti)) {
        http_response_code(400);
        echo json_encode(array(
            "messaggio" => "Dati mancanti o vuoti",
            "campi_mancanti" => $campi_mancanti
        ));
        exit;
    }
    
    // Creo un'istanza di Utente
    $utente = new Utente($db);
    
    
    try {
        $utente -> setId($data->utente_id);
        $utente -> setNomeUtente($data->nome_utente);
        $utente -> setCognomeUtente($data->cognome_utente);
        $utente -> setDataNascita($data->data_nascita);
        $utente -> setEmail($data->email);
        // $utente -> setPassword((string)$data->password);
        
        if ($utente->update()) {
            http_response_code(200);
            echo json_encode(array(
                "messaggio" => "Utente ID {$utente->getUtenteId()} aggiornato con successo"
            ));
        } else {
            http_response_code(404);
            echo json_encode(array(
                "messaggio" => "Utente non trovato o impossibile da aggiornare"
            ));
        }
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(array(
            "messagio" => "Errore validazione dati",
            "errore" => $e->getMessage()
        ));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array(
            "messaggio" => "Errore del server",
            "errore" => $e->getMessage()
        ));
    }
    