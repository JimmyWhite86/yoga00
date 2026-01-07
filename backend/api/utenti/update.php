<?php
    
    /**
     * API Update Utente
     *
     * Endpoint che permette di aggiornare i dati di un utente esistente nel database.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/utenti/update.php
     * @package api.utenti
     *
     * @api
     * method PUT
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Lettura del JSON
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione per la validazione del JSON letto
    isJSONvalid($data);
    
    // Dichiaro i campi obbligatori per la creazione di un utente
    $campi_obbligatori = [
        'utente_id',
        'nome_utente',
        'cognome_utente',
        'data_nascita',
        'email'
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Creo un'istanza di utente
    $utente = new Utente($db);
    
    // Definisco i campi con i rispettivi metodi setter
    $campi_con_setter = [
        'utente_id' => 'setId',
        'nome_utente' => 'setNomeUtente',
        'cognome_utente' => 'setCognomeUtente',
        'data_nascita' => 'setDataNascita',
        'email' => 'setEmail'
    ];
    
    // Richiamo la funzione che esegue l'update
    handlerUpdate($utente, $data, $campi_con_setter);
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */