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
    
    
    $utente = new Utente($db);
    
    // Leggo l'id nella richiesta GET e lo inserisco nella variabile di istanza utente_id dell'oggetto utente
    $id_letto = isset($_GET['id']) ? $_GET['id'] : die();
    $utente->setId($id_letto);
    
    // Invoco il metodo readOne
    // L'id è presente nella variabile utente_id di $utente
    // readOne non restituisce un risultato ma modifica l'oggetto su cui viene invocato
    $utente -> readOne();
    
    if ($utente->getNomeUtente() != null) {  // Se il nome è diversione da null vuol dire che il prodotto esiste
        // Costruisco un array che rappresenta l'utente
        $utente_trovato = array(
            "utente_id" => $utente->getUtenteId(),
            "admin" => $utente->isAdmin(),
            "nome_utente" => $utente->getNomeUtente(),
            "cognome_utente" => $utente->getCognomeUtente(),
            "data_nascita" => $utente->getDataNascita(),
            "email" => $utente->getEmail()
        );
        http_response_code(200);    // response code 200 = ok
        echo json_encode($utente_trovato);       // trasformo l'array associativo in oggetto json e lo restituisco nella response
    } else {
        http_response_code(404);
        echo json_encode(array("messaggio" => "Utente non trovato"));
    }
    
    
    
