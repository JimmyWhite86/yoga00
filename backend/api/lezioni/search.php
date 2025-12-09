<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Lezione.php
    require_once '../../classes/Lezione.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo la keyword nella richiesta GET
    $keyword = isset($_GET["s"]) ? $_GET["s"] : "";
    
    // Creo un istanza di Lezione
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