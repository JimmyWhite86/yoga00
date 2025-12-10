<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Acquisto.php
    require_once '../../classes/Acquisto.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Lettura del JSON
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione per la validazione del JSON letto
    isJSONvalid($data);
    
    // Dichiaro i campi obbligatori per la creazione di un acquisto
    $campi_obbligatori = [
        'acquisto_id',
        'utente_id',
        'abbonamento_id',
        'data_acquisto',
        'data_scadenza',
        'lezioni_rimanenti',
        'attivo'
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Creo un'istanza di acquisto
    $acquisto = new Acquisto($db);
    
    $campi = [
        'acquisto_id' => 'setId',
        'utente_id' => 'setUtenteId',
        'abbonamento_id' => 'setAbbonamentoId',
        'data_acquisto' => 'setDataAcquisto',
        'data_scadenza' => 'setDataScadenza',
        'lezioni_rimanenti' => 'setLezioniRimanenti',
        'attivo' => 'setAttivo'
    ];
    
    // Richiamo la funzione che esegue l'update
    handlerUpdate($acquisto, $data, $campi);