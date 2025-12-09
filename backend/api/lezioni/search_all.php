<?php
    
    require_once '../cors.php';
    
    
    // Viene specificato il formato della risposta
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once '../../database/Database.php';
    require_once '../../classes/Lezione.php';
    
    
    // Creo una connessione al DBMS
    $database = new Database();
    $db = $database->getConnection();
    
    // Controllo la connessione al database => Utile in fase di debug
    if (!$db) {
        http_response_code(500);  // response code 500 = internal server error
        echo json_encode(array("messaggio" => "Errore di connessione al server"));
        exit;
    }
    
    
    $lezione = new Lezione($db);      // Creo un'istanza di Lezione
    $stmt = $lezione -> searchAll();  // Invoco il metodo searchAll()
    $row = $stmt -> rowCount();       // Numero di righe trovate (una per ogni lezione presente nel db)
    
    if ($row > 0) {
        $lezioni_lista = [
            "numero di lezioni" => $row,
            "lezioni" => []
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Costruisco un array che rappresenta ogni singola lezione trovata
            $lezione_singola = [
                'lezione_id' => $row['lezione_id'],
                'nome' => $row['nome'],
                'descrizione' => $row['descrizione'],
                'giorno_settimana' => $row['giorno_settimana'],
                'ora_inizio' => $row['ora_inizio'],
                'ora_fine' => $row['ora_fine'],
                'insegnante' => $row['insegnante'],
                'posti_totali' => $row['posti_totali'],
                'attiva' => $row['attiva']
            ];
            
            // array_push($utenti_lista["utenti"], $utente_singolo);
            
            $lezioni_lista["lezioni"][] = $lezione_singola;
        }
        
        http_response_code(200);
        echo json_encode($lezioni_lista, JSON_UNESCAPED_UNICODE);
        
    } else {
        http_response_code(200);
        echo json_encode([
            'numero di lezioni' => 0,
            "messaggio" => "Nussun utente trovato"
        ], JSON_UNESCAPED_UNICODE);
    }