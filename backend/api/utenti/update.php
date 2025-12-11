<?php
    
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