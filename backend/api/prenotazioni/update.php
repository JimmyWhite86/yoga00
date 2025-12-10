<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Prenotazione.php
    require_once '../../classes/Prenotazione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Lettura del JSON
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione per la validazione del JSON letto
    isJSONvalid($data);
    
    // Dichiaro i campi obbligatori per la creazione di una prenotazione
    $campi_obbligatori = [
        'prenotazioni_id',
        'utente_id',
        'lezione_id',
        'data_prenotata',
        'stato',
        'acquistato_con',
        'prenotato_il'
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Creo un'istanza di prenotazione
    $prenotazione = new Prenotazione($db);
    
    $campi = [
        'prenotazioni_id' => 'setId',
        'utente_id' => 'setUtenteId',
        'lezione_id' => 'setLezioneId',
        'data_prenotata' => 'setDataPrenotata',
        'stato' => 'setStato',
        'acquistato_con' => 'setAcquistatoCon',
        'prenotato_il' => 'setPrenotatoIl'
    ];
    
    // Richiamo la funzione che esegue l'update
    handleUpdate($prenotazione, $data, $campi);