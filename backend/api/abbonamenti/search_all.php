<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Abbonamento.php
    require_once '../../classes/Abbonamento.php.php';
    
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
    
    