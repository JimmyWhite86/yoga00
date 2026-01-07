<?php
    
    /**
     * API Endpoint: Aggiorna i dettagli di una lezione esistente nel database
     *
     * Permette ad un utente con privilegi di amministratore di aggiornare i dettagli di una lezione specifica
     *
     * Metodo HTTP: PUT
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/lezioni/update.php
     * @package api.lezioni
     *
     * @api
     * METHOD: GET
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
    
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */