<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia admin
    admin_necessario();
    
    // Includo la classe Abbonamenti.php
    require_once '../../classes/Abbonamento.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    $abbonamento = new Abbonamento($db);    // Creo un'istanza di lezione
    $abbonamento->setId($id_letto);         // Setto l'id dell'oggetto

    // Richiamo la funzione delete
    handlerDelete($abbonamento, $id_letto);
    
    // TODO: Risolvere chiave esterna