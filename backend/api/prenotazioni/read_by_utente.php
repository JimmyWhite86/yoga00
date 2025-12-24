<?php

    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia loggato
    login_necessario();
    
    // Includo la classe Prenotazione.php
    require_once '../../classes/Prenotazione.php';
    
    // Controllo se l'id dell'utente e presente e valido.
    $id_utente = idIsValid('id');
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Creo un istanza di prenotazione
    $prenotazione = new Prenotazione($db);
    
    // Invoco la funzione per cercare le prenotazioni relative ad un utente
    $stmt = $prenotazione -> searchByUtente($id_utente);
    
    $campi_istanza = [
        'prenotazione_id',
        'utente_id',
        'lezione_id',
        'data_prenotata',
        'stato',
        'acquistato_con',
        'prenotato_il'
    ];
    
    // Richiamo la funzione per la searchALl
    handlerSearchAll($prenotazione, $campi_istanza);
    
    