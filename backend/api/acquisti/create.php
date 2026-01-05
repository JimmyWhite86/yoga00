<?php
    
    /**
     * API Create Acquisto
     *
     * Endpoint che permette di creare un nuovo acquisto nel database.
     *
     * TODO: La classe acquisti non è attualmente sviluppata in modo definitivo.
     * TODO: Gestire le date di acquisto e scadenza
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/acquisti/create.php
     * @package api.acquisti
     *
     * @api
     * METHOD: POST
     *
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia loggato
    login_necessario();
    
    // Includo la classe Acquisto.php
    require_once '../../classes/Acquisto.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che controlla la validazione del JSON in ingresso
    isJSONvalid($data);
    
    // Dichiaro i campi obbligatori per la creazione di un acquisto
    $campi_obbligatori = [
        'utente_id',
        'abbonamento_id',
        'lezioni_rimanenti',
        'attivo'
    ];
    /* $campi_obbligatori = [
        'utente_id',
        'abbonamento_id',
        'data_acquisto',
        'data_scadenza',
        'lezioni_rimanenti',
        'attivo'
    ];*/
    
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Costruisco il mapping nome_attributo => setter
    $campi_con_setter = [
        'utente_id' => 'setUtenteId',
        'abbonamento_id' => 'setAbbonamentoId',
        'data_acquisto' => 'setDataAcquisto',
        'data_scadenza' => 'setDataScadenza',
        'lezioni_rimanenti' => 'setLezioniRimanenti',
        'attivo' => 'setAttivo'
    ];
   /* $campi_con_setter = [
        'utente_id' => 'setUtenteId',
        'abbonamento_id' => 'setAbbonamentoId',
        'lezioni_rimanenti' => 'setLezioniRimanenti',
        'attivo' => 'setAttivo'
    ];*/
    
    // Creo un'istanza di acquisto
    $acquisto = new Acquisto($db);
    
    // Richiamo la funzione per il create
    handlerCreate($acquisto, $campi_con_setter, $data);