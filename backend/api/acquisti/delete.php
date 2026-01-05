<?php
    
    /**
     * API Delete Acquisto
     *
     * Endpoint che permette di eliminare un acquisto dal database.
     *
     * TODO: La classe acquisti non è attualmente sviluppata in modo definitivo.
     * TODO: La cancellazione di un acquisto deve essere solo permessa ad utenti admin
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/acquisti/delete.php
     * @package api.acquisti
     *
     * @api
     * METHOD: DELETE
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia loggato
    login_necessario();
    
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