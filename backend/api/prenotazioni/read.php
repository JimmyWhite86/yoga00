<?php
    
    /**
     * API Read One Prenotazione
     *
     * Endpoint che permette di ottenere una singola prenotazione in base all'id fornito.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/prenotazioni/read.php
     * @package api.prenotazioni
     *
     * @api
     * METHOD: GET
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
    
    
    $prenotazione = new Prenotazione($db);      // Creo un istanza di prenotazione
    $prenotazione->setId($id_letto);        // Setto l'id dell'oggetto
    
    // Invoco il metodo readOne
    // L'id è già presente nella variabile di $abbonamento
    // La funzione readOne non restituisce un risultato ma modifica l'oggetto su cui viene invocata
    $prenotazione -> readOne();
    
    if ($prenotazione->getDataPrenotata() != null) { // Se è presente una data di prenotazione vuol dire che l'oggetto è stato trovato
        $prenotazione_trovata = [
            'prenotazioni_id' => $prenotazione->getId(),
            'utente_id' => $prenotazione->getUtenteId(),
            'lezione_id' => $prenotazione->getLezioneId(),
            'data_prenotata' => $prenotazione->getDataPrenotata(),
            'stato' => $prenotazione->getStato(),
            'acquistato_con' => $prenotazione->getAcquistatoCon(),
            'prenotato_il' => $prenotazione->getPrenotatoIl()
        ];
        
        http_response_code(200);
        echo json_encode($prenotazione_trovata, JSON_UNESCAPED_UNICODE );
    } else {
        http_response_code(404);
        echo json_encode(array(
            "messaggio" => "Prenotazione non trovata"
        ));
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