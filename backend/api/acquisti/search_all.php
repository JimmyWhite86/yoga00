<?php
    
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
    
    