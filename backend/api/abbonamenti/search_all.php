<?php

    /**
     * API Search All Abbonamenti
     *
     * Endpoint che permette di ottenere tutti gli abbonamenti presenti nel database.
     *
     * TODO: La classe abbonamento non è attualmente sviluppata in modo definitivo.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/abbonamenti/search_all.php
     * @package api.abbonamenti
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Abbonamento.php
    require_once '../../classes/Abbonamento.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    $abbonamento = new Abbonamento($db);
    $stmt = $abbonamento -> searchAll();
    
    $campi_istanza = [
        'abbonamento_id',
        'nome',
        'descrizione',
        'prezzo',
        'durata_giorni',
        'numero_lezioni'
    ];
    
    // Richiamo la funzione per la searchAll
    handlerSearchAll($abbonamento, $campi_istanza);
    
    
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */