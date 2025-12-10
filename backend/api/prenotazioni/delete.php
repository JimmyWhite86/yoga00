<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Prenotazione.php
    require_once '../../classes/Prenotazione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    $prenotazione = new Prenotazione($db);      // Creo un'istanza di prenotazione
    $prenotazione->setId($id_letto);            // Setto l'id dell'oggetto
    
    // Richiamo la funzione delete
    delete($prenotazione, $id_letto);