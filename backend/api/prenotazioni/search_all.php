<?php
    
    /**
     * API Endpoint: Recupera tutte le prenotazioni dal database
     *
     * Permette di ottenere una lista di tutte le prenotazioni presenti nel database
     *
     * TODO: Integrare filtro per stato prenotazione (es. attive, cancellate, completate)
     * TODO: Solo admin può accedere a tutte le prenotazioni di tutti gli utenti.
     *
     * Metodo HTTP: GET
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/prenotazioni/search_all.php
     * @package api.prenotazioni
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Prenotazione.php
    require_once '../../classes/Prenotazione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    $prenotazione = new Prenotazione($db);
    $stmt = $prenotazione -> searchAll();
    
    $campi_istanza = [
        'prenotazione_id',
        'utente_id',
        'lezione_id',
        'data_prenotata',
        'stato',
        'acquistato_con',
        'prenotato_il'
    ];
    
    // Richiamo la funzione per la searchAll
    handlerSearchAll($prenotazione, $campi_istanza);