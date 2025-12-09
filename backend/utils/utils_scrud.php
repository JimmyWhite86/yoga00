<?php
    
    // --------------------------------------------------
    // INTESTAZIONE DI OGNI CLASSE SCRUD DI OGNI ENTITÀ
    require_once '../cors.php';
    
    
    // Viene specificato il formato della risposta (JSON)
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once '../../database/Database.php';
    require_once '../../classes/Utente.php';
    // --------------------------------------------------
    
    
    
    // --------------------------------------------------
    // connessioneDatabase
    // Funzione che crea e ritorna una connessione al database
    function connessioneDatabase(): PDO
    {
        // Creo una connessione al DBMS
        $database = new Database();
        $db = $database->getConnection();
        
        // Controllo la connessione al database => utile in fase di debug
        if (!$db) {
            http_response_code(500);    // response code 500 = internal server error
            echo json_encode(array("messaggio" => "Errore connessione al server"));
            exit;
        }
        
        return $db;
    }
    // --------------------------------------------------
    
    
    
    // --------------------------------------------------
    // idIsValid
    // Prende l'id dalla richiesta GET, controlla la presenza e lo valida
    function idIsValid(string $id = 'id'): int
    {
        if (!isset($_GET[$id])) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "ID non presente nella richiesta"));
            exit;
        }
        
        if (!is_numeric($_GET[$id]) || $_GET[$id] <= 0) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "ID non valido"));
        }
        
        return (int)$_GET[$id]; // Cast esplicito
    }
    
    
    
    // --------------------------------------------------
    // isJSONvalid
    // Controlla la validità del JSON ricevuto
    function isJSONvalid($data): void
    {
        // Controllo la presenza dei dati ed eventuali errori
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "JSON non valido: " . json_last_error_msg()));
            exit;
        }
    }
    // --------------------------------------------------
    
    
    
    // --------------------------------------------------
    // validazioneCampiObbligatori
    // Controlla la presenza dei campi obbligatori nel JSON ricevuto
    function validazioneCampiObbligatori($campi_obbligatori, $data): void
    {
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
                "Campi incompleti" => $campi_incompleti));
            exit;
        }
    }
    // --------------------------------------------------
