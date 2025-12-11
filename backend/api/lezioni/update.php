<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia admin
    admin_necessario();
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Lettura del JSON
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione per la validazione del JSON letto
    isJSONvalid($data);
    
    // Dichiaro i campi obbligatori per la creazione di una lezione
    $campi_obbligatori = [
        'lezione_id',
        'nome',
        'descrizione',
        'giorno_settimana',
        'ora_inizio',
        'ora_fine',
        'insegnante',
        'posti_totali',
        'attiva'
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Creo un'istanza di lezione
    $lezione = new Lezione($db);
    
    // Definisco i campi con i rispettivi metodi setter
    $campi_con_setter = [
        'lezione_id' => 'setId',
        'nome' => 'setNome',
        'descrizione' => 'setDescrizione',
        'giorno_settimana' => 'setGiornoSettimana',
        'ora_inizio' => 'setOraInizio',
        'ora_fine' => 'setOraFine',
        'insegnante' => 'setInsegnante',
        'posti_totali' => 'setPostiTotali',
        'attiva' => 'setAttiva'
    ];
    
    // Richiamo la funzione che esegue l'update
    handlerUpdate($lezione, $data, $campi_con_setter);
    
    
    
    /*try {
        $lezione -> setId($data->lezione_id);
        $lezione -> setNome($data->nome);
        $lezione -> setDescrizione($data->descrizione);
        $lezione -> setGiornoSettimana($data->giorno_settimana);
        $lezione -> setOraInizio($data->ora_inizio);
        $lezione -> setOraFine($data->ora_fine);
        $lezione -> setInsegnante($data->insegnante);
        $lezione -> setPostiTotali($data->posti_totali);
        $lezione -> setAttiva($data->attiva);
        
        if ($lezione -> update()) {
            http_response_code(200);
            echo json_encode(array("messaggio" => "Lezione ID {$lezione->getLezioneId()} aggiornata con successo"));
        } else {
            http_response_code(503);
            echo json_encode(array("messaggio" => "Lezione non trovata o impossibile da aggiornare"));
        }
        
    } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(array(
            "messaggio" => "Errore validazione dati",
            "errore" => $e->getMessage()
        ));
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array(
            "messaggio" => "Errore interno al server",
            "errore" => $e->getMessage()
        ));
    }*/