<?php
    // yoga00/backend/utils/utils_api.php
    
    // --------------------------------------------------
    // INTESTAZIONE DI OGNI CLASSE SCRUD DI OGNI ENTITÀ
    // --------------------------------------------------
    
    // Includo il file cors.php
    // TODO: Inserire spiegazione da lezione 04.12.2025
    require_once __DIR__ . '/../api/cors.php';
    
    // Includo il file per gestire le sessioni
    require_once __DIR__ . '/../utils/utils_session.php';
    
    // Viene specificato il formato della risposta (JSON)
    header("Content-Type: application/json; charset=UTF-8");
    
    
    // Includo le classi per la gestione dei dati
    require_once __DIR__ . '/../database/Database.php';
    // --------------------------------------------------
    
    
    // --------------------------------------------------
    // connessioneDatabase
    // Funzione che crea e ritorna una connessione al database
    function connessioneDatabase(): PDO
    {
        // Creo una connessione al DBMS
        $database = new Database();
        $db = $database->getConnection();
        
        // Controllo la connessione al database => utile in fase di debug
        if (!$db) {
            http_response_code(500);    // response code 500 = internal server error
            echo json_encode(array("messaggio" => "Errore connessione al server"));
            exit;
        }
        
        return $db;
    }
    
    // --------------------------------------------------
    
    
    // --------------------------------------------------
    // getClasseOggetto();
    // Restituisce il nome della classe a cui appartiene quell'oggetto.
    // Viene usata per mandare messaggio puntuali
    function getClasseOggetto($istanza): string
    {
        $nome_classe = get_class($istanza);
        return $nome_classe;
    }
    
    // --------------------------------------------------
    
    // --------------------------------------------------
    // Funzione per scegliere la classe da includere in base al tipo di istanza
    // TODO: Eliminare se non la uso (è stata durante delle prove quando ho provato a fare una sola funzione per update)
    function selezionaInclusioneClasse($istanza): void
    {
        $nome_da_inserire = getClasseOggetto($istanza);
        $file_classe = __DIR__ . '/../classes/' . $nome_da_inserire . '.php';
    }
    
    
    // --------------------------------------------------
    // idIsValid
    // Prende l'id dalla richiesta GET, controlla la presenza e lo valida
    function idIsValid(string $id = 'id'): int
    {
        if (!isset($_GET[$id])) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "ID non presente nella richiesta"));
            exit;
        }
        
        if (!is_numeric($_GET[$id]) || $_GET[$id] <= 0) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "ID non valido"));
        }
        
        return (int)$_GET[$id]; // Cast esplicito
    }
    
    // --------------------------------------------------
    
    
    // --------------------------------------------------
    // isJSONvalid
    // Controlla la validità del JSON ricevuto
    function isJSONvalid($data): void
    {
        // Controllo la presenza dei dati ed eventuali errori
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "JSON non valido: " . json_last_error_msg()));
            exit;
        }
    }
    
    // --------------------------------------------------
    
    
    // --------------------------------------------------
    // validazioneCampiObbligatori
    // Controlla la presenza dei campi obbligatori nel JSON ricevuto
    function validazioneCampiObbligatori($campi_obbligatori, $data): void
    {
        $campi_incompleti = [];
        
        foreach ($campi_obbligatori as $campo) {
            if (empty($data->$campo)) {
                $campi_incompleti[] = $campo;
            }
        }
        
        if (!empty($campi_incompleti)) {
            http_response_code(400);
            echo json_encode(array(
                "messaggio" => "Campi incompleti: ",
                "Campi incompleti" => $campi_incompleti));
            exit;
        }
    }
    
    // --------------------------------------------------
    
    
    // --------------------------------------------------
    // delete()
    function handlerDelete($istanza, $id_letto): void
    {
        $nome_classe = getClasseOggetto($istanza);
        if ($istanza->delete()) {
            http_response_code(200);
            echo json_encode(array(
                "messaggio" => $nome_classe . " " . $id_letto . " eliminato con successo"
            ));
        } else {
            http_response_code(503); // Service unavailable
            echo json_encode(array(
                "messaggio" => "Impossibile eliminare" . $nome_classe . " con id " . $id_letto
            ));
        }
    }
    
    // --------------------------------------------------
    
    
    // --------------------------------------------------
    // handleUpdate();
    function handlerUpdate($istanza, $data, $campi_mapping): void
    {
        $nome_classe = getClasseOggetto($istanza);
        
        try {
            foreach ($campi_mapping as $campo => $setter) {
                if (isset($data->$campo)) {
                    $istanza->$setter($data->$campo);
                }
            }
            
            if ($istanza->update()) {
                http_response_code(200);
                echo json_encode(array(
                    "messaggio" => "{$nome_classe} con id {$istanza->getId()} aggiornato con successo"
                ));
            } else {
                http_response_code(503);
                echo json_encode(array(
                    "messaggio" => "{$nome_classe} non trovato o impossibile da aggiornare."
                ));
            }
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(array(
                "messaggio" => "Errore validazione dei dati",
                "errore" => $e->getMessage()
            ));
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array(
                "messaggio" => "Errore del server",
                "errore" => $e->getMessage()
            ));
        }
    }
    
    // --------------------------------------------------


    // --------------------------------------------------
    // handlerSearchAll
    // Riutilizzo lo stesso codice per tutte le classi
    function handlerSearchAll($istanza, $campi): void
    {
        $nome_classe = getClasseOggetto($istanza);
        
        $stmt = $istanza->searchAll();    // Invoco il metodo searchAll sull'istanza che viene passata dalla classe che lo chiama
        $row = $stmt->rowCount();         // Numero di righe trovate. Una per ogni istanza presente nel db
        
        if ($row > 0) {
            $oggetti_trovati = [
                "numero di" . $nome_classe => $row,
                $nome_classe => []
            ];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Costruisco un array che rappresenta ogni singolo record trovato nel db
                $oggetto_singolo = [];
                
                foreach ($campi as $campo) {
                    if (isset($row[$campo])) {
                        $oggetto_singolo[$campo] = $row[$campo];
                    }
                }
                
                $oggetti_trovati[$nome_classe][] = $oggetto_singolo;
            }
            
            http_response_code(200);
            echo json_encode($oggetti_trovati, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(200);
            echo json_encode(array(
                "messaggio" => "Nessun {$nome_classe} trovato"
            ));
        }
    }
    
    // --------------------------------------------------
    
    // --------------------------------------------------
    // handlerCreate
    function handlerCreate($istanza, $campi_mapping, $data)
    {
        $nome_classe = getClasseOggetto($istanza);
        
        try {
            foreach ($campi_mapping as $campo => $setter) {
                if (isset($data->$campo)) {
                    $istanza->$setter($data->$campo);
                }
            }
            
            if ($istanza->create()) {
                http_response_code(201);
                echo json_encode(array(
                    "messaggio" => "creazione avvenuta con successo"
                ));
            } else {
                http_response_code(503);
                echo json_encode(array(
                    "messaggio" => "Impossibile creare {$nome_classe}"
                ));
            }
        } catch (InvalidArgumentException $e) {
            http_response_code(400); // response code = Bad Request
            echo json_encode(array(
                "messaggio" => "Errore di validazione dei dati",
                "errore" => $e->getMessage()
            ));
        } catch (Exception $e) {
            http_response_code(500); // response code = internal server error
            echo json_encode(array(
                "messaggio" => "Errore del server",
                "errore" => $e->getMessage()
            ));
        }
    }
    // --------------------------------------------------


// --------------------------------------------------
// handlerReadOne
function handlerReadOne($istanza, $campi_mapping)
{
    // TODO: Funzione da definire
    // Acquisti non ha nome
}