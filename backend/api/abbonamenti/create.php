<?php
    
    /**
     * API Creazione Abbonamento
     *
     * Endpoint che permette di creare un nuovo abbonamento nel sistema.
     *
     * TODO: La classe abbonamento non è attualmente sviluppata in modo definitivo.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/abbonamenti/create.php
     * @package api.abbonamenti
     *
     * @api
     * METHOD: POST
     *
     * @author Bianchi Andrea
     * @version 1.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Verifico che l'utente sia admin
    admin_necessario();
    
    // Includo la classe Abbonamenti.php
    require_once '../../classes/Abbonamento.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che controlla la validità del JSON in ingresso
    isJSONvalid($data);
    
    
    
    // Dichiaro i campi obbligatori per la creazione di un abbonamento
    $campi_obbligatori = [
        "nome",
        "descrizione",
        "prezzo",
        "durata_lezioni",
        "durata_giorni"
    ];
    
    // Richiamo la funzione che valida la presenza dei campi obbligatori
    validazioneCampiObbligatori($campi_obbligatori, $data);
    
    // Campi mappati
    $campi_con_setter = [
        "nome" => "setNome",
        "descrizione" => "setDescrizione",
        "prezzo" => "setPrezzo",
        "durata_lezioni" => "setDurataLezioni",
        "durata_giorni" => "setDurataGiorni"
    ];
    
    // Creo un'istanza di Abbonamento
    $abbonamento = new Abbonamento($db);
    
    // Richiamo la funzione per creare
    handlerCreate($abbonamento, $campi_con_setter, $data);
    
    
    
    
    
    
    // Popolo l'oggetto abbonamento
    /*try {
        $abbonamento->setNome($data->nome);
        $abbonamento->setDescrizione($data->descrizione);
        $abbonamento->setPrezzo($data->prezzo);
        $abbonamento->setDurataLezioni($data->durata_lezioni);
        $abbonamento->setDurataGiorni($data->durata_giorni);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array(
            "messaggio" => "Errore nei dati forniti: " . $e->getMessage()
        ));
        exit;
    }
    
    // Creo l'istanza all'interno del database
    if ($abbonamento->create()) {
        http_response_code(201);
    } else {
        http_response_code(503);
        echo json_encode(array(
            "messaggio" => "Impossibile creare l'utente"
        ));
    }*/