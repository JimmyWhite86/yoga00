<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
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
    
    
    
    // TODO: Risolvere chiave esterna