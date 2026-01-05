<?php
    
    /**
     * API Search All Acquisti
     *
     * Endpoint che permette di ottenere tutti gli acquisti presenti nel database.
     *
     * TODO: La classe acquisti non è attualmente sviluppata in modo definitivo.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/acquisti/search_all.php
     * @package api.acquisti
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Acquisto.php
    require_once '../../classes/Acquisto.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    $acquisto = new Acquisto($db);
    $stmt = $acquisto -> searchAll();
    
    $campi_istanza = [
        'acquisto_id',
        'utente_id',
        'abbonamento_id',
        'data_acquisto',
        'data_scadenza',
        'lezioni_rimanenti',
        'attivo'
    ];
    
    // Richiamo la funzione per la searchAll
    handlerSearchAll($acquisto, $campi_istanza);
    
    