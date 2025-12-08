<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che controlla la validità del JSON in input
    isJSONvalid($data);
    
    // Creo un istanza di Lezione
    $lezione = new Lezione($db);
    
    // Dichiaro i campi obbligatori per la creazione di una lezione
    $campi_obbligatori = [
        'nome',
        'descrizione',
        'giorno_settimana',
        'ora_inizio',
        'ora_fine',
        'insegnante',
        'posti_totali',
        'attiva'
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Popolo l'oggetto Lezione
    try {
        $lezione->setNome($data->nome);
        $lezione->setDescrizione($data->descrizione);
        $lezione->setGiornoSettimana($data->giorno_settimana);
        $lezione->setOraInizio($data->ora_inizio);
        $lezione->setOraFine($data->ora_fine);
        $lezione->setInsegnante($data->insegnante);
        $lezione->setPostiTotali($data->posti_totali);
        $lezione->setAttiva($data->attiva);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array("messaggio" => "Errore nei dati forniti: " . $e->getMessage()));
        exit;
    }
    
    // Creo l'istanza all'interno del database
    if ($lezione->create()) {                       // Invoco il metodo create();
        http_response_code(201);      // response code: created
    } else {
        http_response_code(503);
        echo json_encode(array('messaggio' => "Impossibile creare l'utente"));
    }
    
    