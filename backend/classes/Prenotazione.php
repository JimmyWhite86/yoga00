<?php

    // require_once '../database/Database.php';
    require_once __DIR__ . '/../database/Database.php';
    
    class Prenotazione
    {
        private ?PDO $conn;                            // Connessione al DB (inizializzata nel costruttore);
        private string $table_name = "prenotazioni";   // Nome della tabella nel database;
        
        
        // ATTRIBUTI PRENOTAZIONE
        private ?int $prenotazione_id;
        private ?int $utente_id;
        private ?int $lezione_id;
        private $data_prenotata;
        private $stato;
        private ?int $acquistato_con;
        private $prenotato_il;
        
        // Attributi per le join
        private ?string $nome_utente;
        private ?string $cognome_utente;
        private ?string $email_utente;
        private ?string $nome_lezione;
        private ?string $insegnante;
        
        
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        
        // GETTER
        public function getId(): ?int { return $this->prenotazione_id; }    // ? => Può restituire un intero oppure null (nel caso di prenotazione non trovata)
        public function getUtenteId(): int { return $this->utente_id; }
        public function getLezioneId(): int { return $this->lezione_id; }
        public function getDataPrenotata() { return $this->data_prenotata; }
        public function getStato() { return $this->stato; }
        public function getAcquistatoCon(): ?int { return $this->acquistato_con; }
        public function getPrenotatoIl() { return $this->prenotato_il; }
        
        public function getNomeUtente(): ?string { return $this->nome_utente; }
        public function getCognomeUtente(): ?string { return $this->cognome_utente; }
        public function getEmailUtente(): ?string { return $this->email_utente; }
        public function getNomeLezione(): ?string { return $this->nome_lezione; }
        public function getInsegnante(): ?string { return $this->insegnante; }
        
        
        
        // SETTER (con validazioni)
        public function setId(int $id): void
        {
            if ($id <= 0) {
                throw new InvalidArgumentException("L'ID prenotazione deve essere un intero positivo");
            }
            $this->prenotazione_id = $id;
        }
        
        public function setUtenteId(int $utente_id): void
        {
            if ($utente_id <= 0) {
                throw new InvalidArgumentException("L'ID utente deve essere un intero positivo");
            }
            $this->utente_id = $utente_id;
        }
        
        public function setLezioneId(int $lezione_id): void
        {
            if ($lezione_id <= 0) {
                throw new InvalidArgumentException("L'ID lezione deve essere un intero positivo");
            }
            $this->lezione_id = $lezione_id;
        }
        
        public function setDataPrenotata($data_prenotata): void
        {
            // Controllo il formato della data (YYYY-MM-DD)
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_prenotata)) {
                throw new InvalidArgumentException("La data prenotata deve essere nel formato YYYY-MM-DD");
            }
            
            // Verifico che sia una data valida
            $parte_della_data = explode('-', $data_prenotata);
            if (!checkdate($parte_della_data[1], $parte_della_data[2], $parte_della_data[0])) {
                throw new InvalidArgumentException("La data inserita per la prenotazione non è una data valida");
            }
            
            $this->data_prenotata = $data_prenotata;
        }
        
        public function setStato(string $stato): void
        {
            $stati_validi = ['confermata', 'cancellata'];
            
            if (!in_array($stato, $stati_validi)) {
                throw new InvalidArgumentException("Lo stato della prenotazione può essere solo 'confermata' o 'cancellata'");
            }
            $this->stato = $stato;
        }
        
        public function setAcquistatoCon(int $acquistato_con): void
        {
            if ($acquistato_con <= 0) {
                throw new InvalidArgumentException("L'ID dell'acquisto deve essere un intero positivo");
            }
            $this->acquistato_con = $acquistato_con;
        }
        
        public function setPrenotatoIl($prenotato_il): void
        {
            $this->prenotato_il = $prenotato_il;
            // TODO: Aggiungere validazioni
        }
        
        
        // METODI SCRUD
        
        // Search All
        public function searchAll()
        {
            $query = "SELECT
                    p.prenotazione_id,
                    p.utente_id,
                    p.lezione_id,
                    p.data_prenotata,
                    p.stato,
                    p.acquistato_con,
                    p.prenotato_il,
                    -- Dati utente
                    u.nome_utente AS utente_nome,
                    u.cognome_utente AS utente_cognome,
                    u.email AS utente_email,
                    -- Dati lezione
                    l.nome AS lezione_nome,
                    l.insegnante AS lezione_insegnante,
                    l.giorno_settimana AS lezione_giorno,
                    l.ora_inizio AS lezione_ora_inizio,
                    l.ora_fine AS lezione_ora_fine,
                    -- Dati abbonamento (opzionale)
                    abb.nome AS abbonamento_nome
                  FROM {$this->table_name} AS p
                  INNER JOIN utenti AS u ON p.utente_id = u.utente_id
                  INNER JOIN lezioni AS l ON p.lezione_id = l.lezione_id
                  LEFT JOIN acquisti AS acq ON p.acquistato_con = acq.acquisto_id
                  LEFT JOIN abbonamenti AS abb ON acq.abbonamento_id = abb.abbonamento_id
                  ORDER BY p.prenotato_il DESC;";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        
        // Create
        public function create()
        {
            $query = "INSERT INTO {$this->table_name} SET
                        utente_id=:utente_id,
                        lezione_id=:lezione_id,
                        data_prenotata=:data_prenotata,
                        stato=:stato,
                        prenotato_il = NOW();";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Bind dei parametri
            $stmt->bindParam(':utente_id', $this->utente_id);
            $stmt->bindParam(':lezione_id', $this->lezione_id);
            $stmt->bindParam(':data_prenotata', $this->data_prenotata);
            $stmt->bindParam(':stato', $this->stato);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Read One
        public function readOne()
        {
            $query = "SELECT
                    p.prenotazione_id,
                    p.utente_id,
                    p.lezione_id,
                    p.data_prenotata,
                    p.stato,
                    p.acquistato_con,
                    p.prenotato_il,
                    -- Dati utente
                    u.nome_utente AS nome_utente,
                    u.cognome_utente AS cognome_utente,
                    u.email AS email,
                    -- Dati lezione
                    l.nome AS nome_lezione,
                    l.insegnante AS insegnante,
                    l.giorno_settimana AS giorno_settimana
                  FROM {$this->table_name} AS p
                  INNER JOIN utenti AS u ON p.utente_id = u.utente_id
                  INNER JOIN lezioni AS l ON p.lezione_id = l.lezione_id
                  LEFT JOIN acquisti AS acq ON p.acquistato_con = acq.acquisto_id
                  WHERE p.prenotazione_id = :prenotazione_id;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':prenotazione_id', $this->prenotazione_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Leggo la prima (e unica) riga del risultato della query
            
            if ($row) {
                // Inserisco i valori nelle variabili di istanza
                $this->utente_id = $row['utente_id'];
                $this->lezione_id = $row['lezione_id'];
                $this->data_prenotata = $row['data_prenotata'];
                $this->stato = $row['stato'];
                $this->acquistato_con = $row['acquistato_con'];
                $this->prenotato_il = $row['prenotato_il'];
                
                $this->nome_utente = $row['nome_utente'];
                $this->cognome_utente = $row['cognome_utente'];
                $this->email_utente = $row['email'];
                $this->nome_lezione = $row['lezione'];
                $this->insegnante = $row['insegnante'];
            } else {
                // Se non trovo la prenotazione, imposto i valori delle variabili di istanza a null
                $this->utente_id = null;
                $this->lezione_id = null;
                $this->data_prenotata = null;
            }
            
            // La funzione readOne non restituisce nulla, i valori sono nelle variabili di istanza
        }
        
        // Update
        public function update()
        {
            $query = "UPDATE {$this->table_name} SET
                        utente_id=:utente_id,
                        lezione_id=:lezione_id,
                        data_prenotata=:data_prenotata,
                        stato=:stato,
                        acquistato_con=:acquistato_con,
                        prenotato_il=:prenotato_il
                        WHERE
                        prenotazione_id=:prenotazione_id;";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Bind dei parametri
            $stmt->bindParam(':utente_id', $this->utente_id);
            $stmt->bindParam(':lezione_id', $this->lezione_id);
            $stmt->bindParam(':data_prenotata', $this->data_prenotata);
            $stmt->bindParam(':stato', $this->stato, PDO::PARAM_BOOL);
            $stmt->bindParam(':acquistato_con', $this->acquistato_con);
            $stmt->bindParam(':prenotato_il', $this->prenotato_il);
            $stmt->bindParam(':prenotazione_id', $this->prenotazione_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Delete
        public function delete()
        {
            $query = "DELETE FROM {$this->table_name} WHERE prenotazione_id = :prenotazione_id;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':prenotazione_id', $this->prenotazione_id);
            
            $stmt->execute();
            return $stmt;
        }
        
        
        
        
        // METODI AGGIUNTIVI
        // Annulla una prenotazione
        public function annullaPrenotazione()
        {
            $query = "UPDATE {$this->table_name} SET stato='cancellata' WHERE prenotazione_id = :prenotazione_id;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':prenotazione_id', $this->prenotazione_id);
            
            $stmt->execute();
            return $stmt;
        }
        
        
        // Cerca prenotazioni in base all'utente
        // Da usare per mostrare all'utente le sue prenotazioni
        public function searchByUtente($utente_id)
        {
            $query = "SELECT
                    p.*,
                    u.nome_utente AS nome_utente,
                    u.cognome_utente AS cognome_utente,
                    l.nome AS lezione_nome,
                    l.insegnante AS insegnante,
                    l.giorno_settimana AS giorno_settimana
                  FROM {$this->table_name} AS p
                  INNER JOIN utenti AS u ON p.utente_id = u.utente_id
                  INNER JOIN lezioni AS l ON p.lezione_id = l.lezione_id
                  WHERE p.utente_id = :utente_id
                  ORDER BY p.data_prenotata DESC;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':utente_id', $utente_id);
            $stmt->execute();
            return $stmt;
        }
    }
    
    
    
    
    
    
    
 /*
     *      INNER JOIN vs LEFT JOIN:
     *
     * INNER JOIN:
     * - Restituisce SOLO le righe dove esiste una corrispondenza in entrambe le tabelle
     * - Uso: utenti e lezioni (DEVONO esistere per ogni prenotazione)
     *
     * LEFT JOIN:
     * - Restituisce TUTTE le righe della tabella sinistra (prenotazioni)
     * - E le righe corrispondenti della tabella destra (se esistono)
     * - Uso: abbonamenti (potrebbero NON esistere, acquistato_con può essere NULL)
  *
  * */