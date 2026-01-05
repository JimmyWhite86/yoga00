<?php
    
    /**
     * API Delete Utente
     *
     * Endpoint che permette di eliminare un utente dal database.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/utenti/delete.php
     * @package api.utenti
     *
     * @api
     * METHOD: DELETE
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    $utente = new Utente($db);       // Creo un'istanza di acquisto
    $utente->setId($id_letto);         // Setto l'id dell'oggetto
    
    // Richiamo la funzione delete
    handlerDelete($utente, $id_letto);
    
    