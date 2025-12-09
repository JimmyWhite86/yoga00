<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Abbonamenti.php
    require_once '../../classes/Abbonamento.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    $abbonamento = new Abbonamento($db);    // Creo l'oggetto abbonamento
    $stmt = $abbonamento -> searchAll();  // Invoco il metodo searchAll()
    $row = $stmt -> rowCount();       // Numero di righe trovate (una per ogni abbonamento presente nel db)

    if ($row > 0) {
        $abbonamenti_lista = [
            "numero di abbonamenti" => $row,
            "abbonamenti" => []
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Costruisco un array che rappresenta ogni singolo abbonamento trovato
            $abbonamento_singolo = [
                'abbonamento_id' => $row['abbonamento_id'],
                'nome' => $row['nome'],
                'descrizione' => $row['descrizione'],
                'prezzo' => $row['prezzo'],
                'durata_giorni' => $row['durata_giorni'],
                'durata_lezioni' => $row['durata_lezioni']
            ];
            
            // array_push($utenti_lista["utenti"], $utente_singolo);
            
            $abbonamenti_lista["abbonamenti"][] = $abbonamento_singolo;
        }
        
        http_response_code(200);
        echo json_encode($abbonamenti_lista, JSON_UNESCAPED_UNICODE);
        
    } else {
        http_response_code(200);
        echo json_encode(array("messaggio" => "Nessun abbonamento trovato"));
    }
    