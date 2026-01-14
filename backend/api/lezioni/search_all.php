<?php
    
    /**
     * API Endpoint: Recupera tutte le lezioni dal database
     *
     * Permette di ottenere una lista di tutte le lezioni presenti nel database
     *
     * Metodo HTTP: GET
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/lezioni/search_all.php
     * @package api.lezioni
     *
     * @api
     *
     * @author Bianchi Andrea
     * @version 1.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Creo l'istanza della classe Lezione
    // passando come parametro la connessione al database
    $lezione = new Lezione($db);
    
    // Richiamo il metodo searchAll della classe Lezione
    // $stmt = $lezione -> searchAll();
    
    $campi_istanza = [
        'lezione_id',
        'nome',
        'descrizione',
        'giorno_settimana',
        'ora_inizio',
        'ora_fine',
        'insegnante',
        'posti_totali',
        'attiva'
    ];
    
    // Richiamo la funzione per la searchAll
    handlerSearchAll($lezione, $campi_istanza);
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */