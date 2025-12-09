<?php

    require_once '../database/Database.php';
    
    class Prenotazione
    {
        private ?PDO $conn;                          // Connessione al DB (inizializzata nel costruttore);
        private string $table_name = "prenotazioni";   // Nome della tabella nel database;
        
        
        // ATTRIBUTI PRENOTAZIONE
        private ?int $prenotazione_id;
        private ?int $utente_id;
        private ?int $lezione_id;
        private $data_prenotazione;
        
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        
        // GETTER
        public function getPrenotazioneId(): ?int { return $this->prenotazione_id; }    // ? => Può restituire un intero oppure null (nel caso di prenotazione non trovata)
        public function getUtenteId(): int { return $this->utente_id; }
        public function getLezioneId(): int { return $this->lezione_id; }
        public function getDataPrenotazione() { return $this->data_prenotazione; }
        
        
        // SETTER (con validazioni)
        public function setUtenteId(int $utente_id): void
        {
            if (!is_int($utente_id) || $utente_id <= 0) {
                throw new InvalidArgumentException("L'ID utente deve essere un intero positivo");
            }
            $this->utente_id = $utente_id;
        }
        
        public function setLezioneId(int $lezione_id): void
        {
            if (!is_int($lezione_id) || $lezione_id <= 0) {
                throw new InvalidArgumentException("L'ID lezione deve essere un intero positivo");
            }
            $this->lezione_id = $lezione_id;
        }
        
        
        // METODI SCRUD
        
        // Search All
        public function searchAll()
        {
            $query = "SELECT * FROM {$this->table_name}";
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
                        data_prenotazione = NOW();";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Bind dei parametri
            $stmt->bindParam(':utente_id', $this->utente_id);
            $stmt->bindParam(':lezione_id', $this->lezione_id);
            
            // Eseguo la query e restistuisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Read One
        public function readOne()
        {
            $query = "SELECT * FROM {$this->table_name} WHERE prenotazione_id = :prenotazione_id;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':prenotazione_id', $this->prenotazione_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Leggo la prima (e unica) riga del risultato della query
            
            if ($row) {
                // Inserisco i valori nelle variabili di istanza
                $this->utente_id = $row['utente_id'];
                $this->lezione_id = $row['lezione_id'];
                $this->data_prenotazione = $row['data_prenotazione'];
            } else {
                // Se non trovo la prenotazione, imposto i valori delle variabili di istanza a null
                $this->utente_id = null;
                $this->lezione_id = null;
                $this->data_prenotazione = null;
            }
            
            // La funzione readOne non restituisce nulla, i valori sono nelle variabili di istanza
        }
        
        // Update
        public function update()
        {
            $query = "UPDATE {$this->table_name} SET
                        utente_id=:utente_id,
                        lezione_id=:lezione_id
                        WHERE
                        prenotazione_id=:prenotazione_id;";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Bind dei parametri
            $stmt->bindParam(':utente_id', $this->utente_id);
            $stmt->bindParam(':lezione_id', $this->lezione_id);
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
        
    }