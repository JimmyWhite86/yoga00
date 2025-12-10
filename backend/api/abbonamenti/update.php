<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Abbonamenti.php
    require_once '../../classes/Abbonamento.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Lettura del JSON
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione per la validazione del JSON letto
    isJSONvalid($data);
    
    // Dichiaro i campi obbligatori per la creazione di una lezione
    $campi_obbligatori = [
      'abbonamento_id',
      'nome',
      'descrizione',
      'prezzo',
      'durata_giorni',
      'durata_lezioni'
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Creo un'istanza di abbonamento
    $abbonamento = new Abbonamento($db);
    
    $campi = [
      "abbonamento_id"  =>  "setId",
      "nome"            =>  "setNome",
      "descrizione"     =>  "setDescrizione",
      "prezzo"          =>  "setPrezzo",
      "durata_giorni"   =>  "setDurataGiorni",
      "durata_lezioni"  =>  "setDurataLezioni",
    ];
    
    // Richiamo la funzione che esegue l'update
    handlerUpdate($abbonamento, $data, $campi);
    
