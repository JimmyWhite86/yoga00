<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
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
    
    