<?php

    class Lezione
    {
        private $conn;                     // Connessione al DB (inizializzata nel costruttore);
        private $table_name = "lezioni";   // Nome della tabella nel database;
        
        // ATTRIBUTI LEZIONE
        private $lezione_id;
        private $nome;
        private $descrizione;
        private $giorno_settimana;
        private $ora_inizio;
        private $ora_fine;
        private $insegnante;
        private $posti_totali;
        private $attiva;
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        
        // GETTER
        public function getLezioneId(): ?int
        {
            return $this->lezione_id;
        }    // ? => Può restituire un intero oppure null (nel caso di lezione non trovata)
        
        public function getNome(): string
        {
            return $this->nome;
        }
        
        public function getDescrizione(): string
        {
            return $this->descrizione;
        }
        
        public function getGiornoSettimana(): string
        {
            return $this->giorno_settimana;
        }
        
        public function getOraInizio(): string
        {
            return $this->ora_inizio;
        }
        
        public function getOraFine(): string
        {
            return $this->ora_fine;
        }
        
        public function getInsegnante(): string
        {
            return $this->insegnante;
        }
        
        public function getPostiTotali(): int
        {
            return $this->posti_totali;
        }
        
        public function isAttiva(): bool
        {
            return $this->attiva;
        }
        
        // SETTER (con validazioni)
        public function setNome($nome): void
        {
            $nome = trim($nome);
            if ($nome === '' || strlen($nome) < 2) {
                throw new InvalidArgumentException("Il nome della lezione deve contenere almeno due caratteri");
            }
            $this->nome = htmlspecialchars($nome);
        }
        
        public function setDescrizione($descrizione): void
        {
            $descrizione = trim($descrizione);
            if ($descrizione === '' || strlen($descrizione) < 5) {
                throw new InvalidArgumentException("La descrizione della lezione deve contenere almeno cinque caratteri");
            }
            $this->descrizione = htmlspecialchars($descrizione);
        }
        
        public function setGiornoSettimana($giorno_settimana): void
        {
            // Rimuovo spazi vuoti e metto la prima lettera maiuscola
            // https://www.php.net/manual/en/function.strtolower.php
            // https://www.php.net/manual/en/function.ucfirst.php
            $giorno = ucfirst(strtolower(trim($giorno_settimana)));
            
            // Lista dei giorni validi
            $giorni_validi = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];
            
            // Controllo se il giorno inserito dall'utente è nella lista dei giorni validi.
            // true => attiva la strict mode (controlla anche il tipo di dato (come fare ===))
            if (!in_array($giorno, $giorni_validi, true)) {
                throw new InvalidArgumentException("Il giorno della settimana non è valido");
            }
            $this->giorno_settimana = $giorno_settimana;
        }
        
        public function setOraInizio($ora_inizio): void
        {
            $this->ora_inizio = $ora_inizio;
        }
        
        public function setOraFine($ora_fine): void
        {
            $this->ora_fine = $ora_fine;
        }
        
        public function setInsegnante($insegnante): void
        {
            $this->insegnante = $insegnante;
        }
        
        public function setPostiTotali($posti_totali): void
        {
            $this->posti_totali = $posti_totali;
        }
        
        public function setAttiva($attiva): void
        {
            $this->attiva = $attiva;
        }
        
        
        // METODI SCRUD
        
        // Search All
        public function searchAll()
        {
            $query = "SELECT * FROM {$this->table_name} ORDER BY lezione_id;";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        
        // Create
        public function create()
        {
            $query = "INSERT INTO {$this->table_name} SET
                      nome=:nome,
                      descrizione=:descrizione,
                      giorno_settimana=:giorno_settimana,
                      ora_inizio=:ora_inizio,
                      ora_fine=:ora_fine,
                      insegnante=:insegnante,
                      posti_totali=:posti_totali,
                      attiva=:attiva;";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori ai parametri della query (i valori del nuovo prodotto sono nelle variabili di istanza)
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':descrizione', $this->descrizione);
            $stmt->bindParam(':giorno_settimana', $this->giorno_settimana);
            $stmt->bindParam(':ora_inizio', $this->ora_inizio);
            $stmt->bindParam(':ora_fine', $this->ora_fine);
            $stmt->bindParam(':insegnante', $this->insegnante);
            $stmt->bindParam(':posti_totali', $this->posti_totali);
            $stmt->bindParam(':attiva', $this->attiva);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Read One
        public function readOne()
        {
            $query = "SELECT * FROM {$this->table_name} WHERE lezione_id = :lezione_id;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':lezione_id', $this->lezione_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Leggo la prima (e unica) riga del risultato della query
            
            if ($row) {
                // Inserisco i valori nelle variabili di istanza
                $this->nome = $row['nome'];
                $this->descrizione = $row['descrizione'];
                $this->giorno_settimana = $row['giorno_settimana'];
                $this->ora_inizio = $row['ora_inizio'];
                $this->ora_fine = $row['ora_fine'];
                $this->insegnante = $row['insegnante'];
                $this->posti_totali = $row['posti_totali'];
                $this->attiva = $row['attiva'];
            } else {
                // Se non trovo la lezione, imposto i valori delle variabili di istanza a null
                $this->nome = null;
                $this->descrizione = null;
                $this->giorno_settimana = null;
                $this->ora_inizio = null;
                $this->ora_fine = null;
                $this->insegnante = null;
                $this->posti_totali = null;
                $this->attiva = null;
            }
            
            // La funzione readOne non restituisce un risultato, ma modifica l'oggetto su cui viene invocata
        }
        
        // Update
        public function update()
        {
            $query = "UPDATE {$this->table_name} SET
                      nome = :nome,
                      descrizione = :descrizione,
                      giorno_settimana = :giorno_settimana,
                      ora_inizio = :ora_inizio,
                      ora_fine = :ora_fine,
                      insegnante = :insegnante,
                      posti_totali = :posti_totali,
                      attiva = :attiva
                      WHERE
                      lezione_id = :lezione_id;";
            
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori ai parametri della query
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':descrizione', $this->descrizione);
            $stmt->bindParam(':giorno_settimana', $this->giorno_settimana);
            $stmt->bindParam(':ora_inizio', $this->ora_inizio);
            $stmt->bindParam(':ora_fine', $this->ora_fine);
            $stmt->bindParam(':insegnante', $this->insegnante);
            $stmt->bindParam(':posti_totali', $this->posti_totali);
            $stmt->bindParam(':attiva', $this->attiva);
            $stmt->bindParam(':lezione_id', $this->lezione_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Delete
        public function delete()
        {
            $query = "DELETE FROM {$this->table_name} WHERE lezione_id = :lezione_id;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':lezione_id', $this->lezione_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        
        // ALTRI METODI
        
        // Funzione per cercare per parola chiave
        public function searchByKeyword($keyword)
        {
            $query = "SELECT * FROM {$this->table_name} WHERE
                        nome LIKE :keyword OR
                        descrizione LIKE :keyword OR
                        insegnante LIKE :keyword";
            
            $stmt = $this->conn->prepare($query);
            $like_keyword = "%{$keyword}%";                     // Aggiungo i wildcard % per la ricerca parziale
            $stmt->bindParam(':keyword', $like_keyword);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
    }