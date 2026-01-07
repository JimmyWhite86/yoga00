<?php
    
    /**
     * API Endpoint: Legge i dettagli di una lezione specifica dal database
     *
     * Permette di recuperare le informazioni di una lezione specifica
     *
     * Metodo HTTP: GET
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/lezioni/read.php
     * @package api.lezioni
     *
     * @param int id - L'ID della lezione da leggere (fornito come parametro di query)
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    
    $lezione = new Lezione($db);    // Creo l'oggetto lezione
    $lezione->setId($id_letto);     // Setto l'id dell'oggetto
    
    
    // Invoco il metodo readOne
    // L'id è già presente nella variabile di $lezione
    // La funzione readOne non restituisce un risultato ma modifica l'oggetto su cui viene invocata
    $lezione -> readOne();
    
    
    if ($lezione->getNome() != null) {             // Se il nome è diverso da null allora la lezione cercata esiste
        $lezione_trovata = [
            "lezione_id" => $lezione->getId(),
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
    
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */