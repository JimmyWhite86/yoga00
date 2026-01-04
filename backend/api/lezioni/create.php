<?php
    
    /**
     * API Endpoint: Creazione di una nuova lezione
     *
     * Permette agli amministratori di creare una nuova lezione nel sistema.
     * Richiede l'autenticazione dell'utente come amministratore.
     * Valida i dati ricevuti e gestisce errori di validazione
     *
     * METODO: POST
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/lezioni/create.php
     * @method POST
     * @access Admin
     *
     * @request_body {
     *   "nome": "Nome della lezione",
     *   "descrizione": "Descrizione della lezione",
     *   "giorno_settimana": "Lunedì",
     *   "ora_inizio": "10:00",
     *  "ora_fine": "11:00",
     *  "insegnante": "Nome Insegnante",
     *  "posti_totali": 20,
     *  "attiva": true
     * }
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    
    /**
     * Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
     */
    require_once '../../utils/utils_api.php';
    
    /**
     * Verifico che l'utente sia un amministratore
     *
     * Controlli effettuati:
     *  - Utente deve essere loggato
     *  - Utente deve essere un amministratore
     *
     *  Solo gli amministratori possono creare lezioni
     */
    admin_necessario();
    
    /**
     * Include la classe lezione
     *
     * La classe gestisce:
     * - Connessione al database
     * - Validazione dei dati
     * - Logica di business
     */
    require_once '../../classes/Lezione.php';
    
    /**
     * Funzione per la connessione al database
     */
    $db = connessioneDatabase();
    
    /**
     * Legge il corpo della richiesta HTTP POST
     *
     * file_get_contents("php://input") legge tutto il corpo della richiesta
     * json_decode converte la stringa JSON in un oggetto PHP
     *
     * @var  object|null $data = Oggetto PHP con i dati decodificati
     */
    $data = json_decode(file_get_contents("php://input"));
    
    /**
     * Richiamo la funzione per verificare che il JSON sia corretto
     */
    isJSONvalid($data);
    
    /**
     * Campi obbligatori per creare una lezione
     *
     * Creo un array che contiene tutti i campi che devono essere presenti nel JSON
     * e non possono essere vuoti.
     *
     * @var array $campi_obbligatori = Array con i nomi dei campi obbligatori
     */
    $campi_obbligatori = [
        'nome',
        'descrizione',
        'giorno_settimana',
        'ora_inizio',
        'ora_fine',
        'insegnante',
        'posti_totali',
        'attiva'
    ];
    
    /**
     * Richiamo la funzione per validare i campi obbligatori
     *
     * Per ogni campo dell'array:
     * - Verifico se esiste in $data
     * - Verifico se non è vuoto
     */
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    /** Campi mappati
     *
     * Mappa i campi JSON ai metodi setter della classe Lezione
     *
     * @var array $campi_con_setter = Array associativo con i campi e i relativi setter
     */
    $campi_con_setter = [
        'nome' => 'setNome',
        'descrizione' => 'setDescrizione',
        'giorno_settimana' => 'setGiornoSettimana',
        'ora_inizio' => 'setOraInizio',
        'ora_fine' => 'setOraFine',
        'insegnante' => 'setInsegnante',
        'posti_totali' => 'setPostiTotali',
        'attiva' => 'setAttiva'
    ];
    
    /**
     * Creo una nuova istanza della classe Lezione
     *
     * @var Lezione $lezione = Istanza vuota della classe lezione
     */
    $lezione = new Lezione($db);
    
    /**
     * Richiamo la funzione per creare la lezione
     */
    handlerCreate($lezione, $campi_con_setter, $data);
    
    
    
    
    
    