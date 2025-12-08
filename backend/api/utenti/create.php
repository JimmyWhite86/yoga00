<?php
    
    require_once '../cors.php';
    
    // Viene specificato il formato della risposta (JSON)
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once '../../database/Database.php';
    require_once '../../classes/Utente.php';
    
    
    // Creo una connessione al DBMS
    $database = new Database();
    $db = $database->getConnection();
    
    // Controllo la connessione al database => utile in fase di debug
    if (!$db) {
        http_response_code(500);    // response code 500 = internal server error
        echo json_encode(array("messaggio" => "Errore connessione al server"));
        exit;
    }
    
    
    // Creo un'istanza di Utente
    $lezione = new Utente($db);
    
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Controllo la presenza dei dati ed eventuali errori
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array("messaggio" => "JSON non valido: " . json_last_error_msg()));
        exit;
    }
    
    // Valido la presenza di campi obbligatori
    $campi_obbligatori = [
        'nome_utente',
        'cognome_utente',
        'data_nascita',
        'email',
        'password'];
    $campi_incompleti = [];
    
    foreach ($campi_obbligatori as $campo) {
        if (empty($data->$campo)) {
            $campi_incompleti[] = $campo;
        }
    }
    
    if (!empty($campi_incompleti)) {
        http_response_code(400);
        echo json_encode(array(
            "messaggio" => "Campi incompleti: ",
            "Campi incompleti" => $campi_incompleti ));
        exit;
    }
    
    
    // Popolo l'oggetto Utente
    try {
        $lezione->setNomeUtente($data->nome_utente);
        $lezione->setCognomeUtente($data->cognome_utente);
        $lezione->setDataNascita($data->data_nascita);
        $lezione->setEmail($data->email);
        $lezione->setPassword($data->password);
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(array("messaggio" => "Errore validazione dei dati",
            "errore" => $e->getMessage()));
    }
    
    
    // Creo l'istanza all'interno del database
    if ($lezione->create()) {                   // Invoco il metodo create() che crea un nuovo utente
        http_response_code(201); // response code: created
        echo json_encode(array("messaggio" => "Utente creato con successo"));
    } else {
        http_response_code(503); // response code 503 = service unavailable
        echo json_encode(array("messaggio" => "Impossibile creare l'utente."));
    }