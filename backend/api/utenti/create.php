<?php
    
    require_once '../cors.php';
    
    // Viene specificato il formato della risposta (JSON)
    header("Content-Type: application/json; charset=UTF-8");
    
    // Includo le classi per la gestione dei dati
    require_once '../../database/DatabaseBase.php';
    require_once '../../classes/Utente.php';
    
    // Creo una connessione al DBMS
    $database = new DatabaseBase();
    $db = $database->getConnection();
    
    // Controllo la connessione al database => utile in fase di debug
    if (!$db)
    {
        http_response_code(500);    // response code 500 = internal server error
        echo json_encode(array("Messaggio" => "Errore connessione al server"));
    }
    
    // Creo un'istanza di Utente
    $utente = new Utente($db);
    
    // Leggo i dati nel body della request (metodo POST)
    $data = json_decode(file_get_contents("php://input"));
    
    // Controllo la presenza dei dati
    if (
        !empty($data->nome_utente) &&
        !empty($data->cognome_utente) &&
        !empty($data->data_nascita) &&
        !empty($data->email) &&
        !empty($data->password) )
    {
        // Inserisco le variabili di istanza dell'oggetto Utente
        $utente -> setNomeUtente($data->nome_utente);
        $utente -> setCognomeUtente($data->cognome_utente);
        $utente -> setDataNascita($data->data_nascita);
        $utente -> setEmail($data->email);
        $utente -> setPassword($data->password);
        
        // Invoco il metodo create che crea un nuovo utente
        if ($utente->create()) {        // Caso in cui la creazione va a buon fine
            http_response_code(201);    // response code 201 = created
            echo json_encode(array("Messaggio" => "Utente creato"));
        } else {                        // Caso in cui la creazione fallisce
            http_response_code(503);    // response code 503 = service unavailable
            echo json_encode(array("Messaggio" => "Errori durante la creazione dell'utente"));
        }
    } else {        // Caso in cui i dati siano incompleti
        http_response_code(400);        // Response code 400 = Bad request
        // Creo un oggetto JSON costituito dalla coppia messaggio : testo del messaggio
        // Uso l'operatore ternario con empty() per evitare l'errore sulla stamap di un valore inesistente
        echo json_encode(array("Messaggio" => "Impossibile creare il prodotto. I dati sono incompleti: "
                . "nome = " . (empty($data->nome_utente) ? "null" : $data->nome_utente)
                . "cognome = " . (empty($data->cognome_utente) ? "null" : $data->cognome_utente)
                . "data nascita = " . (empty($data->data_nascita) ? "null" : $data->data_nascita)
                . "email = " . (empty($data->email) ? "null" : $data->data_nascita)
                . "password = " . (empty($data->password) ? "null" : $data->password)));
    }