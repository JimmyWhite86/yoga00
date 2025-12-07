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
    
    // Leggo i dati nel body della request (metodo PUT/POST/PATCH)
    $data = json_decode(file_get_contents("php://input"));
    
    
    // Creo un'istanza di Utente
    $utente = new Utente($db);
    
  
    
    // Inserisco i valori delle variabili dell'oggetto utente
    // Inserisco anche l'id perchè indica l'utente da aggiornare
$utente -> setId($data->utente_id);
$utente -> setNomeUtente($data->nome_utente);
$utente -> setCognomeUtente($data->cognome_utente);
$utente -> setDataNascita($data->data_nascita);
$utente -> setEmail($data->email);

// Invoco il metodo update che aggiorni i dati dell'utente
if ($utente -> update()) {
    http_response_code(200);
    echo json_encode(array("messaggio" => "Utente aggiornato con successo"));
} else {
    http_response_code(503); // Service unavailable
    echo json_encode(array("messaggio" => "Non è stato possibile aggiornare l'utente {$utente->getUtenteId()}"));
}