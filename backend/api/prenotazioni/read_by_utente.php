<?php
    
    /**
     * API Endpoint: Recupera le prenotazioni di un utente specifico
     *
     * Permette di ottenere tutte le prenotazioni associate a un utente specifico
     * identificato dal suo ID.
     *
     * Metodo HTTP: GET
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/prenotazioni/read_by_utente.php
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
    
    // Controllo se l'id dell'utente e presente e valido.
    $id_utente = idIsValid('id');
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Creo un istanza di prenotazione
    $prenotazione = new Prenotazione($db);
    
    // Invoco la funzione per cercare le prenotazioni relative ad un utente
    $stmt = $prenotazione -> searchByUtente($id_utente);
    $row_count = $stmt->rowCount();
    
    if ($row_count > 0) {
        $prenotazioni_trovate = [
            "numero di prenotazioni" => $row_count,
            "Prenotazione" => []
        ];
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $prenotazione_singola = [
                'prenotazione_id' => $row['prenotazione_id'],
                'utente_id' => $row['utente_id'],
                'lezione_id' => $row['lezione_id'],
                'data_prenotata' => $row['data_prenotata'],
                'stato' => $row['stato'],
                'prenotato_il' => $row['prenotato_il'],
                
                'nome_utente' => $row['nome_utente'],
                'cognome_utente' => $row['cognome_utente'],
                'lezione_nome' => $row['lezione_nome'],
                'insegnante' => $row['insegnante'],
                'giorno_settimana' => $row['giorno_settimana']
            ];
            
            $prenotazioni_trovate["Prenotazione"][] = $prenotazione_singola;
        }
        
        http_response_code(200);
        echo json_encode($prenotazioni_trovate, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(200);
        echo json_encode(array(
            "messaggio" => "Nessuna prenotazione trovata per l'utente con id {$id_utente}"
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
    
    