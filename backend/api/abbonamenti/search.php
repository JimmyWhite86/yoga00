<?php
    
    /**
     * API Search Abbonamenti
     *
     * Endpoint che permette di cercare abbonamenti nel database in base a una keyword.
     *
     * TODO: La classe abbonamento non è attualmente sviluppata in modo definitivo.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/abbonamenti/search.php
     * @package api.abbonamenti
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Abbonamenti.php
    require_once '../../classes/Abbonamento.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo la keyword nella richiesta GET
    $keyword = isset($_GET["s"]) ? $_GET["s"] : "";
    
    // Creo un'istanza di Abbonamento
    $abbonamento = new Abbonamento($db);
    
    // Invoco il metodo searchByKeyword();
    $stmt = $abbonamento->searchByKeyword($keyword);
    
    $lista_abbonamenti = array();
    $lista_abbonamenti["abbonamenti"] = array();
    
    foreach ($stmt as $item) {
        $abbonamento_singolo = array(
            "abbonamento_id" => $item['abbonamento_id'],
            "nome" => $item['nome'],
            "descrizione" => $item['descrizione'],
            "prezzo" => $item['prezzo'],
            "durata_giorni" => $item['durata_giorni'],
            "durata_lezioni" => $item['durata_lezioni']
        );
        array_push($lista_abbonamenti["abbonamenti"], $abbonamento_singolo);
    }
    
    
    // Trasformo l'array in oggetto JSON vero e proprio
    echo json_encode($lista_abbonamenti);
