<?php
    
    /**
     * API Endpoint: Cerca lezioni nel database in base a una keyword
     *
     * Permette di cercare lezioni in base a una keyword fornita come parametro di query
     *
     * Metodo HTTP: GET
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/lezioni/search.php
     * @package api.lezioni
     *
     * @param string s - La keyword da cercare (fornita come parametro di query)
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo la keyword nella richiesta GET
    $keyword = isset($_GET["s"]) ? $_GET["s"] : "";
    
    // Creo un'istanza di Lezione
    $lezione = new Lezione($db);
    
    // Invoco il metodo searchByKeyword
    $stmt = $lezione->searchByKeyword($keyword);
    
    $lista_lezioni = array();
    $lista_lezioni["lezioni"] = array();
    
    foreach ($stmt as $item) {
        $lezione_singola = array(
            "lezione_id" => $item['lezione_id'],
            "nome" => $item['nome'],
            "descrizione" => $item['descrizione'],
            "giorno_settimana" => $item['giorno_settimana'],
            "ora_inizio" => $item['ora_inizio'],
            "ora_fine" => $item['ora_fine'],
            "insegnante" => $item['insegnante'],
            "posti_totali" => $item['posti_totali'],
            "attiva" => $item['attiva']
        );
        array_push($lista_lezioni["lezioni"], $lezione_singola);
    }
    
    
    // Trasformo l'array in un oggetto JSON vero e proprio
    echo json_encode($lista_lezioni, JSON_UNESCAPED_UNICODE);
    
    
    // Chiudo la connessione
    $db = null;
    /*
     * Se la connessione non viene chiusa esplicitamente, viene comunque
     * chiusa dall'interprete PHP quando lo script termina, ma è considerata
     * buona pratica inserire un'esplicita istruzione di chiusura quando le
     * operazioni sul database sono terminate.
     * [Slide 06_PHPDB n18]
     */