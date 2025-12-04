<?php

    require_once '../database/Database.php';
    
    class Abbonamenti
    {
        private $conn;                           // Connessione al DB (inizializzata nel costruttore);
        private $table_name = "abbonamenti";     // Nome della tabella nel database;
        
        // ATTRIBUTI ABBONAMENTO
        private $abbonamento_id;
        private $tipo_abbonamento;
        private $prezzo;
        private $durata_mesi;
        
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        // GETTER
        public function getAbbonamentoId(): ?int { return $this->abbonamento_id; }    // ? => Può restituire un intero oppure null (nel caso di abbonamento non trovato)
        public function getTipoAbbonamento(): string { return $this->tipo_abbonamento; }
        public function getPrezzo(): float { return $this->prezzo; }
        public function getDurataMesi(): int { return $this->durata_mesi; }
        
        
        // SETTER (con validazioni)
        public function setTipoAbbonamento($tipo_abbonamento): void
        {
            // TODO: Aggiungere validazioni se necessario
            $this->tipo_abbonamento = htmlspecialchars($tipo_abbonamento);
        }
        
        public function setPrezzo($prezzo): void
        {
            if (!is_numeric($prezzo) || $prezzo < 0) {
                throw new InvalidArgumentException("Il prezzo deve essere un numero positivo");
            }
            $this->prezzo = floatval($prezzo);
        }
        
        public function setDurataMesi($durata_mesi): void
        {
            if (!is_int($durata_mesi) || $durata_mesi <= 0) {
                throw new InvalidArgumentException("La durata deve essere un intero positivo");
            }
            $this->durata_mesi = $durata_mesi;
        }
        
        
        // METODI SCRUD
        
        // Search All
        public function searchAll()
        {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY tipo_abbonamento;";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
    
        // Create
        public function create()
        {
            $query = "INSERT INTO {$this->table_name} SET
                      tipo_abbonamento=:tipo_abbonamento,
                      prezzo=:prezzo,
                      durata_mesi=:durata_mesi;";
            
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori ai parametri della query
            $stmt->bindParam(':tipo_abbonamento', $this->tipo_abbonamento);
            $stmt->bindParam(':prezzo', $this->prezzo);
            $stmt->bindParam(':durata_mesi', $this->durata_mesi);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Read One
        function readOne()
        {
            $query = "SELECT * FROM {$this->table_name} WHERE abbonamento_id = :abbonamento_id;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':abbonamento_id', $this->abbonamento_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Leggo la prima (e unica) riga del risultato della query
            
            if ($row) {
                // Inserisco i valori nelle variabili di istanza
                $this->tipo_abbonamento = $row['tipo_abbonamento'];
                $this->prezzo = $row['prezzo'];
                $this->durata_mesi = $row['durata_mesi'];
            } else {
                // Se non trovo l'abbonamento, imposto i valori delle variabili di istanza a null
                $this->tipo_abbonamento = null;
                $this->prezzo = null;
                $this->durata_mesi = null;
            }
            
            // La funzione readOne non restituisce un risultato, ma modifica l'oggetto su cui viene invocata
        }
        
        // Update
        function update()
        {
            $query = "UPDATE {$this->table_name} SET
                      tipo_abbonamento = :tipo_abbonamento,
                      prezzo = :prezzo,
                      durata_mesi = :durata_mesi
                      WHERE
                      abbonamento_id = :abbonamento_id;";
            
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori ai parametri della query
            $stmt->bindParam(':tipo_abbonamento', $this->tipo_abbonamento);
            $stmt->bindParam(':prezzo', $this->prezzo);
            $stmt->bindParam(':durata_mesi', $this->durata_mesi);
            $stmt->bindParam(':abbonamento_id', $this->abbonamento_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Delete
        function delete()
        {
            $query = "DELETE FROM {$this->table_name} WHERE abbonamento_id = :abbonamento_id;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':abbonamento_id', $this->abbonamento_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        
        // ALTRI METODI
        
        // Funzione per cercare per parola chiave
        function searchByKeyword($keyword)
        {
            $query = "SELECT * FROM {$this->table_name} WHERE
                        tipo_abbonamento LIKE :keyword
                            ORDER BY tipo_abbonamento;";
            
            $stmt = $this->conn->prepare($query);
            $like_keyword = "%{$keyword}%";                     // Aggiungo i wildcard % per la ricerca parziale
            $stmt->bindParam(':keyword', $like_keyword);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
    }