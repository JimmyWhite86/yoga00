<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    $lezione = new Lezione($db);
    $stmt = $lezione -> searchAll();
    
    $campi_istanza = [
        'lezione_id',
        'nome',
        'descrizione',
        'giorno_settimana',
        'orario_inizio',
        'orario_fine',
        'insegnante',
        'posti_totali',
        'attiva'
    ];
    
    // Richiamo la funzione per la searchAll
    handlerSearchAll($lezione, $campi_istanza);
    
    