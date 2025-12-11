<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo la keyword nella richiesta GET
    $keyword = isset($_GET["s"]) ? $_GET["s"] : "";
    
    // Creo un'istanza di Lezione
    $utente = new Utente($db);
    
    // Invoco il metodo searchByKeyword
    $stmt = $utente->searchByKeyword($keyword);
    
    $lista_utenti = array();
    $lista_utenti["utente"] = array();
    
    foreach ($stmt as $item) {
        $utente_singolo = array(
            "utente_id" => $item['utente_id'],
            "nome_utente" => $item['nome_utente'],
            "cognome_utente" => $item['cognome_utente'],
            "data_nascita" => $item['data_nascita'],
            "email" => $item['email']
        );
        array_push($lista_utenti["utente"], $utente_singolo);
    }
    
    // Trasformo l'array in un oggetto JSON vero e proprio
    echo json_encode($lista_utenti, JSON_UNESCAPED_UNICODE);
    
    
    
    
    
    
    
    
    /*require_once '../cors.php';
    require_once '../../utils/utils_api.php';
    
    // Viene specificato il formato della risposta
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once "../../database/Database.php";
    require_once "../../classes/Utente.php";
    
    
    // Creo una connessione al DBMS
    $database = new Database();
    $db = $database->getConnection();
    
    // Controllo la connessione al database
    if (!$db) {
        http_response_code(500); // Internal server error
        echo json_encode(array("messaggio" => "Errore di connessione al server"));
        exit;
    }
    
    
    // Leggo la keyword nella richiesta GET
    $keyword = isset($_GET["s"]) ? $_GET["s"] : "";
    
    // Creo un istanza di Utente
    $utente = new Utente($db);
    
    // Invoco il metodo searchByKeyword()
    $stmt = $utente->searchByKeyword($keyword);
    
    $lista_utenti = array();
    $lista_utenti["utente"] = array();
    foreach ($stmt as $row) {
        $utente_singolo = array(
            "utente_id" => $row['utente_id'],
            "nome_utente" => $row['nome_utente'],
            "cognome_utente" => $row['cognome_utente'],
            "data_nascita" => $row['data_nascita'],
            "email" => $row['email']
        );
        array_push($lista_utenti["utente"], $utente_singolo);
    }
    
    // Trasformo l'array in un oggetto json vero e proprio
    echo json_encode($lista_utenti);*/