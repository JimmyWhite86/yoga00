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
    
    
    $lezione = new Lezione($db);    // Creo l'oggetto lezione
    $lezione->setId($id_letto);
    
    // Invoco il metodo readOne
    // L'id è già presente nella variabile di $lezione
    // La funzione readOne non un risultato ma modifica l'oggetto su cui viene invocata
    $lezione -> readOne();
    
    
    if ($lezione->getNome() != null) {             // Se il nome è diverso da null allora la lezione cercata esiste
        $lezione_trovata = [
            "lezione_id" => $lezione->getLezioneId(),
            "nome" => $lezione->getNome(),
            "descrizione" => $lezione->getDescrizione(),
            "giorno_settimana" => $lezione->getGiornoSettimana(),
            "ora_inizio" => $lezione->getOraInizio(),
            "ora_fine" => $lezione->getOraFine(),
            "insegnante" => $lezione->getInsegnante(),
            "posti_totali" => $lezione->getPostiTotali(),
            "attiva" => $lezione->isAttiva()
        ];
        
        http_response_code(200);
        echo json_encode($lezione_trovata, JSON_UNESCAPED_UNICODE);
    } else {                                            // Caso in cui la lezione cercata non viene trovata
        http_response_code(404);
        echo json_encode(array("messaggio" => "Utente non trovato"));
    }