<?php
    
    /**
     * File di utility per API REST
     *
     * Contiene funzioni riutilizzabili per le API REST
     * come helper riutilizzabili, validazioni e gestione degli errori
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/utils/utils_api.php
     * @package backend\utils
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    
    // =======================================================
    // ======= INTESTAZIONE DI OGNI CLASSE CRUD DI OGNI ENTITÀ =======
    // =======================================================
    
    /**
     * CORS (Cross-Origin Resource Sharing)
     * Includo il file per gestire le CORS
     * Permette richieste da domini diversi (es: frontend su porta diversa)
     * Necessario per API REST moderne con frontend separato
     *
     * TODO: Inserire spiegazione da lezione 04.12.2025
     */
    require_once __DIR__ . '/../utils/cors.php';
    
    
    /**
     * Gestione Sessioni
     * Include la classi che contiene funzioni per autenticazione e autorizzazione utenti
     */
    require_once __DIR__ . '/../utils/utils_session.php';
    
    
    /**
     * Header Content-Type
     * Specifica che tutte le risposte saranno in formato JSON
     * charset=UTF-8 per supporto caratteri internazionali (accenti, emoji, ecc.)
     */
    header("Content-Type: application/json; charset=UTF-8");
    
    
    /**
     * Database
     * Include la classe Database per connessione al DBMS
     */
    require_once __DIR__ . '/../database/Database.php';
    // ========================================================
    
    
    
    // ===============================
    // ======= FUNZIONI DI UTILITY  =======
    // ===============================
    
    /**
     * Connessione al Database
     *
     * Crea e restituisce una connessione PDO al database.
     * In caso di errore, termina l'esecuzione con messaggio di errore JSON.
     *
     * @return PDO Connessione attiva al database
     * @throws void Termina con exit in caso di errore
     *
     * @example
     * $db = connessioneDatabase();
     * // Ora puoi usare $db per query
     */
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
    
    
    /**
     * GET Nome della Classe
     *
     * Restituisce il nome della classe di un'istanza oggetto.
     *
     * @param object $istanza Istanza di una classe (Utente, Lezione, ecc.)
     * @return string Nome della classe (es: "Utente", "Lezione")
     *
     * @example
     * $utente = new Utente($db);
     * $nome = getClasseOggetto($utente); // "Utente"
     */
    function getClasseOggetto(object $istanza): string
    {
        $nome_classe = get_class($istanza);
        return $nome_classe;
    }
    
    
    /**
     * Validazione ID dalla Query String
     *
     * Estrae e valida un ID dalla richiesta GET ($_GET).
     * Verifica che sia presente, numerico e positivo.
     *
     * @param string $id Nome del parametro GET (default: 'id')
     * @return int ID validato
     * @throws void Termina con exit se ID non valido
     */
    function idIsValid(string $id = 'id'): int
    {
        // Verifico la presenza del parametro
        if (!isset($_GET[$id])) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "ID non presente nella richiesta"));
            exit;
        }
        
        // Verifico che sia un numero intero e positivo
        if (!is_numeric($_GET[$id]) || $_GET[$id] <= 0) {
            http_response_code(400);
            echo json_encode(array("messaggio" => "ID non valido"));
            exit;
        }
        
        return (int)$_GET[$id]; // Cast esplicito
    }
    
    
    /**
     * Validazione JSON
     *
     * Verifica che i dati ricevuti siano un JSON valido.
     *
     * @param mixed $data Dati decodificati da json_decode()
     * @return void Termina con exit se JSON non valido
     *
     * @example
     * $json = file_get_contents("php://input");
     * $data = json_decode($json);
     * isJSONvalid($data);  // Valida o termina
     */
    function isJSONvalid($data): void
    {
        // $data === null => json_decode ha fallito
        // json_last_error() !== JSON_ERROR_NONE => c'è stato un errore di decodifica
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array("Messaggio" => "JSON non valido: " . json_last_error_msg()));
            exit;
        }
    }
    
    
    /**
     * Validazione Campi Obbligatori
     *
     * Verifica che tutti i campi obbligatori siano presenti e non vuoti
     * nel JSON ricevuto.
     *
     * @param array $campi_obbligatori Array con nomi dei campi richiesti
     * @param object $data Oggetto JSON decodificato
     * @return void Termina con exit se campi mancanti.
     */
    function validazioneCampiObbligatori($campi_obbligatori, $data): void
    {
        $campi_incompleti = [];
        
        // Cicla tutti i campi obbligatori
        foreach ($campi_obbligatori as $campo) {
            // Verifico se il campo è assente o vuoto
            if (empty($data->$campo)) {
                // Se risulta vuoto lo aggiungo all'array dei campi incompleti
                $campi_incompleti[] = $campo;
            }
        }
        
        // Se dopo il foreach sopra l'arrey non è vuoto, significa che mancano campi obbligatori
        // Termino con errore e lista dei campi mancanti
        if (!empty($campi_incompleti)) {
            http_response_code(400);
            echo json_encode(array(
                "messaggio" => "Campi incompleti: ",
                "Campi incompleti" => $campi_incompleti));
            exit;
        }
    }
    
    
    /**
     * Handler per DELETE
     *
     * Gestisce l'eliminazione di un'entità dal database.
     *
     * @param object $istanza Istanza della classe (Utente, Lezione, ecc.)
     * @param int $id_letto ID dell'entità da eliminare
     * @return void Echo JSON response
     *
     * @example
     * $utente = new Utente($db);
     * $utente->setId($id);
     * handlerDelete($utente, $id);
     */
    function handlerDelete($istanza, $id_letto): void
    {
        // Recupero il nome della classe
        $nome_classe = getClasseOggetto($istanza);
        
        try {
            // Tento la cancellazione
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
        } catch (InvalidArgumentException $e) {
            // Gestione vincoli di integrità
            http_response_code(400); // Bad Request
            echo json_encode(array(
                "messaggio" => "Errore di validazione dei dati",
                "errore" => $e->getMessage()
            ));
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(array(
                "messaggio" => "Errore del server",
                "errore" => $e->getMessage()
            ));
        }
    }
    
    
    /**
     * Handler per UPDATE
     *
     * Gestisce l'aggiornamento di un'entità nel database.
     * Mappa i campi JSON ai setter della classe e gestisce validazioni.
     *
     * @param object $istanza Istanza della classe da aggiornare
     * @param object $data Dati JSON con i nuovi valori
     * @param array $campi_mapping
     * @return void Echo JSON response
     */
    function handlerUpdate($istanza, $data, $campi_mapping): void
    {
        // Recupero il nome della classe
        $nome_classe = getClasseOggetto($istanza);
        
        try {
            
            // Ciclo sui campi mapping per settare i valori
            foreach ($campi_mapping as $campo => $setter) {
                if (isset($data->$campo)) {
                    $istanza->$setter($data->$campo);
                }
            }
            
            // Eseguo l'update
            if ($istanza->update()) {
                http_response_code(200);
                echo json_encode(array(
                    "messaggio" => "{$nome_classe} con id {$istanza->getId()} aggiornato con successo"
                ));
            } else {
                http_response_code(503);        // Service Unavailable
                echo json_encode(array(
                    "messaggio" => "{$nome_classe} non trovato o impossibile da aggiornare."
                ));
            }
            
        } catch (InvalidArgumentException $e) {
            // Errore di validazione dei dati
            // Es: mail non valida, campo nome troppo corto...)
            http_response_code(400);
            echo json_encode(array(
                "messaggio" => "Errore validazione dei dati",
                "errore" => $e->getMessage()
            ));
        } catch (Exception $e) {
            // Errore generico del server
            http_response_code(500);        // Internal Server Error
            echo json_encode(array(
                "messaggio" => "Errore del server",
                "errore" => $e->getMessage()
            ));
        }
    }
    
    
    /**
     * Handler per SEARCH ALL (READ ALL)
     *
     * Recupera tutte le entità di un tipo dal database.
     * Formatta la risposta JSON con conteggio e array di oggetti.
     *
     * @param object $istanza Istanza della classe da cercare
     * @param array $campi Array con nomi dei campi da includere nella risposta
     * @return void Echo JSON response
     *
     * @example
     * $lezione = new Lezione($db);
     * $campi = ['lezione_id', 'nome', 'descrizione', 'giorno_settimana',
     *           'ora_inizio', 'ora_fine', 'insegnante', 'posti_totali', 'attiva'];
     * handlerSearchAll($lezione, $campi);
     *
     * // Output JSON:
     * {
     *   "success": true,
     *   "count": 5,
     *   "Lezione": [
     *     {"lezione_id": 1, "nome": "Hatha Yoga", ...},
     *     {"lezione_id": 2, "nome": "Vinyasa", ...}
     *   ]
     * }
     */
    function handlerSearchAll($istanza, $campi): void
    {
        // Recupero il nome della classe
        $nome_classe = getClasseOggetto($istanza);
        
        try {
            // Invoco il metodo searchAll sull'istanza che viene passata dalla classe che lo chiama
            $stmt = $istanza->searchAll();
            
            // Numero di righe trovate. Una per ogni istanza presente nel db
            /*$row = $stmt->rowCount();*/
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $numero_righe = count($rows);
            
            // Se le righe sono maggiori di 0, significa che ha trovato delle istanze
            if ($numero_righe > 0) {
                // Creo l'array principale che conterrà tutti gli oggetti trovati
                $oggetti_trovati = [
                    "numero di" . $nome_classe => $numero_righe,
                    $nome_classe => []
                ];
                
                // Ciclo sui risultati della query
                /*while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {*/
                foreach ($rows as $row) {
                    // Costruisco un array che rappresenta ogni singolo record trovato nel db
                    $oggetto_singolo = [];
                    
                    // Costruisco il singolo oggetto con i campi richiesti
                    foreach ($campi as $campo) {
                        if (isset($row[$campo])) {
                            $oggetto_singolo[$campo] = $row[$campo];
                        }
                    }
                    
                    // Aggiungo il singolo oggetto all'array principale
                    $oggetti_trovati[$nome_classe][] = $oggetto_singolo;
                }
                
                http_response_code(200);        // Ok
                echo json_encode($oggetti_trovati, JSON_UNESCAPED_UNICODE);
            } else {
                // Numero di righe = 0 => non ha trovato nessuna istanza nel db
                http_response_code(200);        // Ok (Anche se non trova nessun record, non significa che ci sia un errore)
                echo json_encode(array(
                    "messaggio" => "Non sono presenti {$nome_classe} nel database"
                ));
            }
        } catch (Exception $e) {
            echo json_encode(array(
                "messaggio" => "Errore del server",
                "errore" => $e->getMessage()
            ));
        }
    }
    
    
    /**
     * Handler per CREATE
     *
     * Gestisce la creazione di una nuova entità nel database.
     * Mappa i campi JSON ai setter della classe e gestisce validazioni.
     *
     * @param object $istanza Istanza della classe da creare
     * @param array $campi_mapping Mappa campo => metodo setter
     * @param object $data Dati JSON con i valori da inserire
     * @return void Echo JSON response
     *
     * @example
     * $lezione = new Lezione($db);
     *
     * $mapping = [
     *     'nome' => 'setNome',
     *     'descrizione' => 'setDescrizione',
     *     'giorno_settimana' => 'setGiornoSettimana',
     *     'ora_inizio' => 'setOraInizio',
     *     'ora_fine' => 'setOraFine',
     *     'insegnante' => 'setInsegnante',
     *     'posti_totali' => 'setPostiTotali',
     *     'attiva' => 'setAttiva'
     * ];
     *
     * handlerCreate($lezione, $mapping, $data);
     */
    function handlerCreate($istanza, $campi_mapping, $data)
    {
        // Recupero il nome della classe
        $nome_classe = getClasseOggetto($istanza);
        
        try {
            // Ciclo sui campi mapping per settare i valori
            foreach ($campi_mapping as $campo => $setter) {
                // Se il campo è presente nel JSON ricevuto, lo setto
                if (isset($data->$campo)) {
                    // Setto il valore usando il setter chiamato in maniera "dinamica"
                    $istanza->$setter($data->$campo);
                }
            }
            
            // Tento la creazione
            if ($istanza->create()) {
                http_response_code(201);        // Created
                echo json_encode(array(
                    "messaggio" => "creazione avvenuta con successo"
                ));
            } else {
                http_response_code(503);        // Service Unavailable
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


    /**
     * Handler per READ ONE
     *
     * Funzione da implementare
     */
    function handlerReadOne($istanza, $campi_mapping)
    {
        // TODO: Funzione da definire
        // Problema: Acquisti non ha nome
    }