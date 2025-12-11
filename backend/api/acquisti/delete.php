<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Acquisti.php
    require_once '../../classes/Acquisto.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    $acquisto = new Acquisto($db);       // Creo un'istanza di acquisto
    $acquisto->setId($id_letto);         // Setto l'id dell'oggetto
    
    // Richiamo la funzione delete
    handlerDelete($acquisto, $id_letto);