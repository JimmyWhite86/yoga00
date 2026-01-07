<?php
    
    /**
     * API Create Prenotazione
     *
     * Endpoint che permette di creare una nuova prenotazione nel database.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/prenotazioni/create.php
     * @package api.prenotazioni
     *
     * @api
     * METHOD: POST
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia loggato
    login_necessario();
    
    // Includo la classe Prenotazione.php
    require_once '../../classes/Prenotazione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che controlla la validità del JSON in ingresso
    isJSONvalid($data);
    
    // Dichiaro i campi obbligatori per la creazione di una prenotazione
    $campi_obbligatori = [
        "utente_id",
        "lezione_id",
        "data_prenotata",
        "stato"
        // "acquistato_con",
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Campi mappati
    $campi_con_setter = [
        "utente_id" => "setUtenteId",
        "lezione_id" => "setLezioneId",
        "data_prenotata" => "setDataPrenotata",
        "stato" => "setStato"
        // "acquistato_con" => "setAcquistatoCon",
    ];
    
    // Creo un'istanza di Prenotazione
    $prenotazione = new Prenotazione($db);
    
    // Richiamo la funzione per creare
    handlerCreate($prenotazione, $campi_con_setter, $data);
    
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */