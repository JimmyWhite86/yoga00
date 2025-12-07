<?php
    
    require_once __DIR__ . '/../database/Database.php';
    
    class Utente
    {
        private ?PDO $conn;                      // Connessione al DB (inizializzata nel costruttore);
        private string $table_name = "utenti";     // Nome della tabella nel database;
        
        
        // ATTRIBUTI UTENTE
        private ?int $utente_id;
        private bool $admin;
        private ?string $nome_utente;
        private ?string $cognome_utente;
        private $data_nascita;
        private ?string $email;
        private ?string $password;
        
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        
        // GETTER
        public function getUtenteId(): ?int { return $this->utente_id; }    // ? => Può restituire un intero oppure null (nel caso di utente non trovato)
        public function isAdmin(): bool { return $this->admin; }
        public function getNomeUtente(): string { return $this->nome_utente; }
        public function getCognomeUtente(): string { return $this->cognome_utente; }
        public function getDataNascita() { return $this->data_nascita;}
        public function getEmail(): string { return $this->email; }
        
        
        // SETTER (con validazioni)
        public function setNomeUtente(string $nome_utente): void
        {
            $nome_utente = trim($nome_utente);                          // trim => Rimuove spazi all'inizio e alla fine della stringa
            if ($nome_utente === '' || strlen($nome_utente) < 2 ) {     // Controllo che il nome non sia stringa vuota o un solo carattere
                throw new InvalidArgumentException("Il nome deve contenere almeno due caratteri");  // Lancia un'eccezione => obbliga a chi usa la classe a gestire l'errore.
            }
            $this->nome_utente = htmlspecialchars($nome_utente);        // Salva il valore facendo l'escape dei caratteri per prevenire XSS.
        }
        
        public function setCognomeUtente(string $cognome_utente): void
        {
            $cognome_utente = trim($cognome_utente);
            if ($cognome_utente === '' || strlen($cognome_utente) < 2) {
                throw new InvalidArgumentException("Il cognome deve contenere almeno due caratteri");
            }
            $this->cognome_utente = htmlspecialchars($cognome_utente);
        }
        
        public function setDataNascita($data_nascita): void
        {
            // TODO: Inserire regular regex per validare la data.
            // https://stackoverflow.com/questions/15491894/regex-to-validate-date-formats-dd-mm-yyyy-dd-mm-yyyy-dd-mm-yyyy-dd-mmm-yyyy
            // if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $data))
            $this->data_nascita = $data_nascita;
        }
        
        public function setEmail(string $email): void
        {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL); // vedere => Nota00
            if (!$email) {
                throw new InvalidArgumentException("Email non valida");
            }
            $this->email = $email;
        }
        
        
        public function setPassword(string $password): void
        {
            if (strlen($password) < 6) {        // Verifico che la password abbia almeno 6 caratteri.
                throw new InvalidArgumentException("Password troppo corta. Almeno 6 caratteri.");
            }
            $this->password = password_hash($password, PASSWORD_DEFAULT);
            // Trasformo la password in chiaro in una stringa criptata.
            // PASSWORD_DEFAULT = Dice quale algoritmo usare per la criptatura. Viene aggiornato quando è disponibile un algoritmo piu sicuro.
            // https://www.php.net/manual/en/function.password-hash.php
        }
        
        
        // METODI SCRUD
        
        // Search All
        function searchAll(): false|PDOStatement
        {
            $query = "SELECT * FROM {$this->table_name}";   // Scrivo la query per interrogare il db
            $stmt = $this->conn->prepare($query);           // Preparo la query
            $stmt->execute();                               // Eseguo la query
            return $stmt;                                   // Restituisco il risultato della query
        }
        
        // Create
        function create()
        {
            $query = "INSERT INTO {$this->table_name} SET
                       nome_utente=:nome_utente,
                       cognome_utente=:cognome_utente,
                       data_nascita=:data_nascita,
                       email=:email,
                       password=:password";
            
            // Preparo la query
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori per i parametri (i valori del nuovo prodotto sono nelle variabili di istanza).
            $stmt->bindParam(":nome_utente", $this->nome_utente);
            $stmt->bindParam(":cognome_utente", $this->cognome_utente);
            $stmt->bindParam(":data_nascita", $this->data_nascita);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $this->password);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Read One
        function readOne()
        {
            $query = "SELECT * FROM {$this->table_name} WHERE utente_id = :utente_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':utente_id', $this->utente_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Leggo la prima (e unica) riga del risultato della query
            
            if ($row) {
                // Inserisco i valori nelle variabili di istanza
                $this->admin = $row['admin'];
                $this->nome_utente = $row['nome_utente'];
                $this->cognome_utente = $row['cognome_utente'];
                $this->data_nascita = $row['data_nascita'];
                $this->email = $row['email'];
                $this->password = $row['password'];
            } else {
                // Se non trovo l'utente, imposto i valori delle variabili di istanza a null
                $this->admin = null;
                $this->nome_utente = null;
                $this->cognome_utente = null;
                $this->data_nascita = null;
                $this->email = null;
                $this->password = null;
            }
            
            // la funzione readOne non restituisce un risultato, bensì modifica l'oggetto su cui viene invocata (cioè l'utente)
        }
        
        // Update
        function update()
        {
            $query = "UPDATE {$this->table_name} SET
                      nome_utente = :nome_utente,
                      cognome_utente = :cognome_utente,
                      data_nascita = :data_nascita,
                      email = :email,
                      password = :password
                      WHERE
                      utente_id = :utente_id";
            
            $stmt = $this->conn->prepare($query);
            
            // Invio i valori per i parametri (i nuovi valori dell'utente sono nelle variabili di istanza).
            $stmt->bindParam(':nome_utente', $this->nome_utente);
            $stmt->bindParam(':cognome_utente', $this->cognome_utente);
            $stmt->bindParam(':data_nascita', $this->data_nascita);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':utente_id', $this->utente_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        // Delete
        function delete()
        {
            $query = "DELETE FROM {$this->table_name} WHERE utente_id = :utente_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':utente_id', $this->utente_id);
            
            // Eseguo la query e restituisco il risultato
            $stmt->execute();
            return $stmt;
        }
        
        
        // ALTRI METODI
        
        // Funzione per cercare utenti per keyword
        function searchByKeyword($keyword)
        {
            // Cerco gli utenti
            $query = "SELECT * FROM {$this->table_name} WHERE
                         nome_utente LIKE :keyword OR
                         cognome_utente LIKE :keyword OR
                         email LIKE :keyword";
            $stmt = $this->conn->prepare($query);
            $keyword = "%{$keyword}%";  // Aggiungo i caratteri jolly per la ricerca parziale (SQL)
            $stmt->bindParam(':keyword', $keyword);
            $stmt->execute();
            return $stmt;               // Restituisco il risultato della query (in questo caso un recordset)
        }
    }
   
    
    
 /*
           NOTE
    Nota00
- filter_var() = Funzione nativa di PHP che si usa per filtrare i dati
- FILTER_VALIDATE_EMAIL = valida la stringa in base ai criteri di un indirizzo valido (per esempio presenza di '@').
      Se l'email è valida => restituisce la stringa stessa (che in questo caso viene riassegnata alla variabile email)
      Se l'email non è valida => restituisce false.
- FILTER_SANITAZE_EMAIL = rimuove caratteri illegali dalla mail
 https://www.w3schools.com/php/filter_validate_email.asp

 */
