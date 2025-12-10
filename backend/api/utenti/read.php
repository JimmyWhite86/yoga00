<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    
    $utente = new Utente($db);      // Creo un istanza di utente
    $utente->setId($id_letto);      // Setto l'id dell'oggetto
    
    // Invoco il metodo readOne
    // L'id è già presente nella variabile di $utente
    // La funzione readOne non restituisce un risultato ma modifica l'oggetto su cui viene invocata
    $utente -> readOne();
    
    if ($utente->getNomeUtente() != null) {     // Se è presente un nome utente vuol dire che l'oggetto è stato trovato
        $utente_trovato = [
            "utente_id" => $utente->getId(),
            "admin" => $utente->isAdmin(),
            "nome_utente" => $utente->getNomeUtente(),
            "cognome_utente" => $utente->getCognomeUtente(),
            "data_nascita" => $utente->getDataNascita(),
            "email" => $utente->getEmail()
        ];
        
        http_response_code(200);
        echo json_encode($utente_trovato, JSON_UNESCAPED_UNICODE );
    } else {
        http_response_code(404);
        echo json_encode(array(
            "messaggio" => "Utente non trovato"
        ));
    }