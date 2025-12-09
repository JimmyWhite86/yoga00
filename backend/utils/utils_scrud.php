<?php
    
    // --------------------------------------------------
    // INTESTAZIONE DI OGNI CLASSE SCRUD DI OGNI ENTITÀ
    require_once __DIR__ . '/../api/cors.php';
    
    
    // Viene specificato il formato della risposta (JSON)
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once __DIR__ . '/../database/Database.php';
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
    // getClasseOggetto();
    // Restituisce il nome della classe a cui appartiene quell'oggetto.
    // Viene usata per mandare messaggio puntuali
    function getClasseOggetto($istanza): string
    {
        $nome_classe = get_class($istanza);
        return $nome_classe;
    }
    // --------------------------------------------------
    
    // --------------------------------------------------
    // Funzione per scegliere la classe da includere in base al tipo di istanza
    function includiClassi($istanza): string
    {
        $nome_classe = getClasseOggetto($istanza);
        
        switch ($nome_classe) {
            case $nome_classe === "Abbonamento":
                $percorso_classe = "'../../classes/Abbonamento.php';";
                break;
            case $nome_classe === "Acquisto":
                $percorso_classe = "'../../classes/Acquisto.php';";
                break;
            case $nome_classe === "Lezione":
                $percorso_classe = "'../../classes/Lezione.php';";
                break;
            case $nome_classe === "Prenotazione":
                $percorso_classe = "'../../classes/Prenotazione.php';";
                break;
            case $nome_classe === "Utente":
                $percorso_classe = "'../../classes/Lezione.php';";
                break;
        }
        return $percorso_classe;
    }
    
    
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


    // --------------------------------------------------
    // delete()
    function delete($istanza, $id_letto): void
    {
        $nome_classe = getClasseOggetto($istanza);
        if ($istanza->delete()) {
            http_response_code(200);
            echo json_encode(array(
                "messaggio" => $nome_classe . $id_letto . " eliminata con successo"
            ));
        } else {
            http_response_code(503); // Service unavailable
            echo json_encode(array(
                "messaggio" => "Impossibile eliminare" . $nome_classe . " con id " . $id_letto
            ));
        }
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // handleUpdate();
    function handleUpdate($istanza, $data, $campi): void
    {
        $nome_classe = getClasseOggetto($istanza);
        
        try {
            foreach ($campi as $campo => $setter) {
                if (isset($data->$campo)) {
                    $istanza->$setter($data->$campo);
                }
            }
            
            if ($istanza->update()) {
                http_response_code(200);
                echo json_encode(array(
                    "messaggio" => "{$nome_classe} con id {$istanza->getId()} aggiornata con successo"
                ));
            } else {
                http_response_code(503);
                echo json_encode(array(
                    "messaggio" => "{$nome_classe} non trovata o impossibile da aggiornare."
                ));
            }
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(array(
                "messaggio" => "Errore validazione dei dati",
                "errore" => $e->getMessage()
            ));
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array(
                "messaggio" => "Errore del server",
                "errore" => $e->getMessage()
            ));
        }
    }