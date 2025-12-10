<?php

    //require_once '../database/Database.php';
    require_once __DIR__ . '/../database/Database.php';
    
    class Abbonamento
    {
        private ?PDO $conn;                           // Connessione al DB (inizializzata nel costruttore);
        private string $table_name = "abbonamenti";     // Nome della tabella nel database;
        
        // ATTRIBUTI ABBONAMENTO
        private ?int $abbonamento_id;
        private ?string $nome;
        private ?string $descrizione;
        private ?float $prezzo;
        private ?int $durata_giorni;
        private ?int $durata_lezioni;
        
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        // GETTER
        public function getAbbonamentoId(): ?int { return $this->abbonamento_id; }    // ? => Può restituire un intero oppure null (nel caso di abbonamento non trovato)
        public function getNome(): ?string { return $this->nome; }
        public function getDescrizione(): ?string { return $this->descrizione; }
        public function getPrezzo(): ?float { return $this->prezzo; }
        public function getDurataGiorni(): ?int { return $this->durata_giorni; }
        public function getDurataLezioni(): ?int { return $this->durata_lezioni; }
        
        
        // SETTER (con validazioni)
        public function setId(int $id): void
        {
            if ($id <= 0) {
                throw new InvalidArgumentException("L'ID abbonamento deve essere un intero positivo");
            }
            $this->abbonamento_id = $id;
        }
        
        public function setNome(string $nome): void
        {
            $nome = trim($nome);                          // trim => Rimuove spazi all'inizio e alla fine della stringa
            if ($nome === '' || strlen($nome) < 2 ) {     // Controllo che il nome non sia stringa vuota o un solo carattere
                throw new InvalidArgumentException("Il nome deve contenere almeno due caratteri");  // Lancia un'eccezione => obbliga a chi usa la classe a gestire l'errore.
            }
            $this->nome = htmlspecialchars($nome);        // Salva il valore facendo l'escape dei caratteri per prevenire XSS.
        }
        
        public function setDescrizione(string $descrizione): void
        {
            $descrizione = trim($descrizione);
            if ($descrizione === '' || strlen($descrizione) < 5) {
                throw new InvalidArgumentException("La descrizione deve contenere almeno cinque caratteri");
            }
            $this->descrizione = htmlspecialchars($descrizione);
        }
        
        public function setPrezzo(float $prezzo): void
        {
            if (!is_numeric($prezzo) || $prezzo < 0) {
                throw new InvalidArgumentException("Il prezzo deve essere un numero non negativo");
            }
            $this->prezzo = $prezzo;
        }
        
        public function setDurataGiorni(int $durata_giorni): void
        {
            if ($durata_giorni !== null && $durata_giorni <= 0) {
                throw new InvalidArgumentException("La durata in giorni deve essere un intero positivo o null");
            }
            $this->durata_giorni = $durata_giorni;
        }
        
        public function setDurataLezioni(int $durata_lezioni): void
        {
            if ($durata_lezioni !== null && $durata_lezioni <= 0) {
                throw new InvalidArgumentException("La durata in lezioni deve essere un intero positivo");
            }
            $this->durata_lezioni = $durata_lezioni;
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
                        nome=:nome,
                        descrizione=:descrizione,
                        prezzo=:prezzo,
                        durata_giorni=:durata_giorni,
                        durata_lezioni=:durata_lezioni;";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori
            $stmt->bindParam(":nome", $this->nome);
            $stmt->bindParam(":descrizione", $this->descrizione);
            $stmt->bindParam(":prezzo", $this->prezzo);
            $stmt->bindParam(":durata_giorni", $this->durata_giorni);
            $stmt->bindParam(":durata_lezioni", $this->durata_lezioni);
            
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
                $this->nome = $row['nome'];
                $this->descrizione = $row['descrizione'];
                $this->prezzo = $row['prezzo'];
                $this->durata_giorni = $row['durata_giorni'];
                $this->durata_lezioni = $row['durata_lezioni'];
            } else {
                // Se non trovo l'abbonamento, imposto i valori delle variabili di istanza a null
                $this->nome = null;
                $this->descrizione = null;
                $this->prezzo = null;
                $this->durata_giorni = null;
                $this->durata_lezioni = null;
            }
            // La funzione readOne non restituisce un risultato, ma modifica l'oggetto su cui viene invocata
        }
        
        // Update
        function update()
        {
            $query = "UPDATE {$this->table_name} SET
                      nome=:nome,
                      descrizione=:descrizione,
                      prezzo = :prezzo,
                      durata_giorni=:durata_giorni,
                      durata_lezioni=:durata_lezioni
                      WHERE
                      abbonamento_id = :abbonamento_id;";
            
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori ai parametri della query
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':descrizione', $this->descrizione);
            $stmt->bindParam(':prezzo', $this->prezzo);
            $stmt->bindParam(':durata_giorni', $this->durata_giorni);
            $stmt->bindParam(':durata_lezioni', $this->durata_lezioni);
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
            $query = "SELECT * FROM {$this->table_name}
                      WHERE nome LIKE :keyword
                      OR descrizione LIKE :keyword";
            
            $stmt = $this->conn->prepare($query);
            $like_keyword = "%{$keyword}%";                             // Aggiungo i wildcard % per la ricerca parziale
            $stmt->bindParam(':keyword', $like_keyword);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
    }