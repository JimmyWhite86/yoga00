<?php
    
    /**
     * API Endpoint: Elimina una lezione dal database
     *
     * Permette ad un utente con privilegi di amministratore di eliminare una lezione specifica
     *
     * TODO: Gestire la chiave esterna per evitare errori di integrità referenziale
     * TODO: Gestire casi in cui la lezione è associata a prenotazioni o altri record.
     *
     * Metodo HTTP: DELETE
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/lezioni/delete.php
     * @package api.lezioni
     *
     * @param int id - L'ID della lezione da eliminare (fornito come parametro di query)
     *
     * @api
     * METHOD: DELETE
     *
     * @access Admin
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia admin
    admin_necessario();
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    $lezione = new Lezione($db);      // Creo un istanza di lezione
    $lezione->setId($id_letto);      // Setto l'id dell'oggetto con l'id letto

    // Invoco il metodo delete() per cancellare la lezione selezionata
    if ($lezione->delete()) {
        http_response_code(200);
        echo json_encode(array("messaggio" => "Lezione " . $id_letto . " eliminata con successo"));
    } else {
        http_response_code(503); // Service unavailable
        echo json_encode(array("messaggio" => "Impossibile eliminare la lezione id " . $id_letto));
    }
    
    
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */