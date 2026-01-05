<?php
    
    /**
     * API Read One Abbonamento
     *
     * Endpoint che permette di leggere un singolo abbonamento dal database.
     *
     * TODO: La classe abbonamento non è attualmente sviluppata in modo definitivo.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/abbonamenti/read.php
     * @package api.abbonamenti
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Abbonamenti.php
    require_once '../../classes/Abbonamento.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Richiamo la funzione idIsValid();
    // Se l'id è valido e presente lo memorizzo nella variabile $id_letto;
    $id_letto = idIsValid('id');
    
    $abbonamento = new Abbonamento($db);       // Creo l'oggetto abbonamento
    $abbonamento->setId($id_letto);            // Setto l'id dell'oggetto

    // Invoco il metodo readOne
    // L'id è già presente nella variabile di $abbonamento
    // La funzione readOne non restituisce un risultato ma modifica l'oggetto su cui viene invocata
    $abbonamento -> readOne();

    if ($abbonamento->getNome() != null) {      // Se il nome è diverso da null allora
        $abbonamento_trovato = [
            "abbonamento_id" => $abbonamento->getAbbonamentoId(),
            "nome" => $abbonamento->getNome(),
            "descrizione" => $abbonamento->getDescrizione(),
            "prezzo" => $abbonamento->getPrezzo(),
            "durata_giorni" => $abbonamento->getDurataGiorni(),
            "durata_lezioni" => $abbonamento->getDurataLezioni()
        ];
        
        http_response_code(200);
        echo json_encode($abbonamento_trovato, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(array("messaggio" => "Abbonamento non trovato"));
    }