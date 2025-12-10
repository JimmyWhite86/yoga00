<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Acquisto.php
    require_once '../../classes/Acquisto.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    
    $acquisto = new Acquisto($db);      // Creo un istanza di acquisto
    $acquisto->setId($id_letto);        // Setto l'id dell'oggetto
    
    // Invoco il metodo readOne
    // L'id è già presente nella variabile di $acquisto
    // La funzione readOne non restituisce un risultato ma modifica l'oggetto su cui viene invocata
    $acquisto -> readOne();
    
    if ($acquisto->getDataAcquisto() != null) {     // Se è presente una data di acquisto vuol dire che l'oggetto è stato trovato
        $acquisto_trovato = [
            "acquisto_id" => $acquisto->getId(),
            "utente_id" => $acquisto->getUtenteId(),
            "abbonamento_id" => $acquisto->getAbbonamentoId(),
            "data_acquisto" => $acquisto->getDataAcquisto(),
            "data_scadenza" => $acquisto->getDataScadenza(),
            "lezioni_rimanenti" => $acquisto->getLezioniRimanenti(),
            "attivo" => $acquisto->isAttivo()
        ];
        
        http_response_code(200);
        echo json_encode($acquisto_trovato, JSON_UNESCAPED_UNICODE );
    } else {
        http_response_code(404);
        echo json_encode(array(
            "messaggio" => "Acquisto non trovato"
        ));
    }
    