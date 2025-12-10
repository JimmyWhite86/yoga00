<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che controlla la validazione del JSON in ingresso
    isJSONvalid($data);
    
    // Creo un'istanza di Utente
    $utente = new Utente($db);
    
    
    // Dichiaro i campi obbligatori per la creazione di un utente
    $campi_obbligatori = [
        'nome_utente',
        'cognome_utente',
        'data_nascita',
        'email',
        'password'
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Costruisco il mapping nome_attributo => setter
    $campi_mapping = [
        'nome_utente' => 'setNomeUtente',
        'cognome_utente' => 'setCognomeUtente',
        'data_nascita' => 'setDataNascita',
        'email' => 'setEmail',
        'password' => 'setPassword'
    ];
    
    // Richiamo la funzione per il create
    handlerCreate($utente, $campi_mapping, $data);