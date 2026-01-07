<?php
    
    /**
     * API Delete Prenotazione
     *
     * Endpoint che permette di eliminare una prenotazione dal database.
     *
     * TODO: Integrare vincolo che utente può eliminare solo le proprie prenotazioni.
     * TODO: Integrare permesso ad admin di eliminare le prenotazioni di tutti gli utenti.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/prenotazioni/delete.php
     * @package api.prenotazioni
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
    handlerDelete($prenotazione, $id_letto);
    
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */