<?php
    
    require_once '../database/Database.php';
    
    class Acquisti
    {
        private ?PDO $conn;                          // Connessione al DB (inizializzata nel costruttore);
        private string $table_name = "acquisti";       // Nome della tabella nel database;
        
        
        // ATTRIBUTI ACQUISTO
        private ?int $acquisto_id;
        private bool $utente_id;
        private ?string $abbonamento_id;
        private ?string $data_acquisto;
        private ?string $data_scadenza;
        private ?string $lezioni_rimanenti;
        private ?string $attivo;
        
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        
        // GETTER
        public function getAcquistoId(): ?int { return $this->acquisto_id; }    // ? => Può restituire un intero oppure null (nel caso di acquisto non trovato)
        public function getUtenteId(): ?int { return $this->utente_id; }
        public function getAbbonamentoId(): ?int { return $this->abbonamento_id; }
        public function getDataAcquisto() { return $this->data_acquisto; }
        public function getDataScadenza() { return $this->data_scadenza; }
        public function getLezioniRimanenti(): ?int { return $this->lezioni_rimanenti; }
        public function isAttivo(): bool { return $this->attivo; }
        
        
        // SETTER (con validazioni)
        public function setUtenteId(int $utente_id): void
        {
            if (!is_int($utente_id) || $utente_id <= 0) {
                throw new InvalidArgumentException("L'ID utente deve essere un intero positivo");
            }
            $this->utente_id = $utente_id;
        }
        
        public function setAbbonamentoId(int $abbonamento_id): void
        {
            if (!is_int($abbonamento_id) || $abbonamento_id <= 0) {
                throw new InvalidArgumentException("L'ID abbonamento deve essere un intero positivo");
            }
            $this->abbonamento_id = $abbonamento_id;
        }
        
        public function setLezioniRimanenti(int $lezioni_rimanenti): void
        {
            if (!is_int($lezioni_rimanenti) || $lezioni_rimanenti < 0) {
                throw new InvalidArgumentException("Le lezioni rimanenti devono essere un intero non negativo");
            }
            $this->lezioni_rimanenti = $lezioni_rimanenti;
        }
        
        public function setAttivo(bool $attivo): void
        {
            $this->attivo = $attivo;
        }
        
        
        // METODI SCRUD
        
        // Search All
        function searchAll()
        {
            $query = "SELECT * FROM {$this->table_name}";   // Scrivo la query per interrogare il db
            $stmt = $this->conn->prepare($query);           // Preparo la query
            $stmt->execute();                               // Eseguo la query
            return $stmt;                                   // Restituisco il risultato
        }
        
        // Create
        public function create()
        {
            $query = "INSERT INTO {$this->table_name} SET
                       utente_id=:utente_id,
                       abbonamento_id=:abbonamento_id,
                       data_acquisto=:data_acquisto,
                       data_scadenza=:data_scadenza,
                       lezioni_rimanenti=:lezioni_rimanenti,
                       attivo=:attivo;";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori per i parametri (i valori del nuovo acquisto sono nelle variabili di istanza).
            $stmt->bindParam(":utente_id", $this->utente_id);
            $stmt->bindParam(":abbonamento_id", $this->abbonamento_id);
            $stmt->bindParam(":data_acquisto", $this->data_acquisto);
            $stmt->bindParam(":data_scadenza", $this->data_scadenza);
            $stmt->bindParam(":lezioni_rimanenti", $this->lezioni_rimanenti);
            $stmt->bindParam(":attivo", $this->attivo, PDO::PARAM_BOOL);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Read One
        function readOne()
        {
            $query = "SELECT * FROM {$this->table_name} WHERE acquisto_id = :acquisto_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':acquisto_id', $this->acquisto_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Leggo la prima (e unica) riga del risultato della query
            
            if ($row) {
                // Inserisco i valori nelle variabili di istanza
                $this->utente_id = $row['utente_id'];
                $this->abbonamento_id = $row['abbonamento_id'];
                $this->data_acquisto = $row['data_acquisto'];
                $this->data_scadenza = $row['data_scadenza'];
                $this->lezioni_rimanenti = $row['lezioni_rimanenti'];
                $this->attivo = (bool)$row['attivo'];
            } else {
                // Se non trovo l'acquisto, imposto i valori delle variabili di istanza a null
                $this->utente_id = null;
                $this->abbonamento_id = null;
                $this->data_acquisto = null;
                $this->data_scadenza = null;
                $this->lezioni_rimanenti = null;
                $this->attivo = null;
            }
            
            // La funzione readOne non restituisce un risultato, ma modifica l'oggetto su cui viene invocata
        }
        
        // Update
        function update()
        {
            $query = "UPDATE {$this->table_name} SET
                      utente_id = :utente_id,
                      abbonamento_id = :abbonamento_id,
                      data_acquisto = :data_acquisto,
                      data_scadenza = :data_scadenza,
                      lezioni_rimanenti = :lezioni_rimanenti,
                      attivo = :attivo
                      WHERE
                      acquisto_id = :acquisto_id;";
            
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori ai parametri della query
            $stmt->bindParam(':utente_id', $this->utente_id);
            $stmt->bindParam(':abbonamento_id', $this->abbonamento_id);
            $stmt->bindParam(':data_acquisto', $this->data_acquisto);
            $stmt->bindParam(':data_scadenza', $this->data_scadenza);
            $stmt->bindParam(':lezioni_rimanenti', $this->lezioni_rimanenti);
            $stmt->bindParam(':attivo', $this->attivo, PDO::PARAM_BOOL);
            $stmt->bindParam(':acquisto_id', $this->acquisto_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Delete
        function delete()
        {
            $query = "DELETE FROM {$this->table_name} WHERE acquisto_id = :acquisto_id;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':acquisto_id', $this->acquisto_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
    }