<?php
    
    /**
     * API Search All Utenti
     *
     * Endpoint che permette di ottenere tutti gli utenti presenti nel database.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/utenti/search_all.php
     * @package api.utenti
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    $utente = new Utente($db);
    $stmt = $utente -> searchAll();
    
    $campi_istanza = [
        'utente_id',
        'admin',
        'nome_utente',
        'cognome_utente',
        'data_nascita',
        'email'
    ];
    
    // Richiamo la funzione per la searchAll
    handlerSearchAll($utente, $campi_istanza);
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */