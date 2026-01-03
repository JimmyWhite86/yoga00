<?php
    
    /* ATTENZIONE: I COMMENTI DI QUESTO FILE SONO UNA PROVA DI DOCUMENTAZIONE */
    
    /**
     * Classe Utente => Gestione utente
     * Rappresenta una utente con attributi e metodi per la gestione nel database.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/classes/Utente.php
     *
     * @author Bianchi Andrea
     * @version 1.0
     */
    
    
    require_once __DIR__ . '/../database/Database.php';
    
    class Utente
    {
        
        // =================================
        // ====== ATTRIBUTI DI CLASSE ======
        // =================================
        
        /**
         * @var PDO|null $conn = connessione al database (iniettato tramite costruttore)
         */
        private ?PDO $conn;

        /**
         * @var string $table_name = Nome della tabella nel database
         */
        private string $table_name = "utenti";
        
        
        
        // ==============================
        // ====== ATTRIBUTI UTENTE ======
        // ==============================
        
        /**
         * @var int|null $utente_id = ID univoco dell'utente
         */
        private ?int $utente_id;
        
        /**
         * @var bool $admin = Indica se l'utente è un amministratore
         */
        private bool $admin;
        
        /**
         * @var string|null $nome_utente = Nome dell'utente
         */
        private ?string $nome_utente;
        
        /**
         * @var string|null $cognome_utente = Cognome dell'utente
         * */
        private ?string $cognome_utente;
        
        /**
         * @var string|null $data_nascita = Data di nascita dell'utente
         *
         * è stato scelto di usare stringa per facilitare la gestione del formato della data.
         * Si potrebbe usare DateTime per una gestione piu avanzata.
         */
        private ?string $data_nascita;
        
        /**
         * @var string|null $email = Email dell'utente
         */
        private ?string $email;
        
        /**
         * @var string|null $password = Password criptata dell'utente
         */
        private ?string $password;
        
        /**
         * @var string|null $password_in_chiaro = Password in chiaro (usata solo in fase di login)
         * Non viene salvata nel database.
         */
        private ?string $password_in_chiaro;
        
        
        
        // =========================
        // ====== COSTRUTTORE ======
        // =========================
        
        /**
         * Costruttore della classe Utente
         * Inizializza la connessione al database
         *
         * @param PDO $db = Connessione al database
         * */
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        
        
        // ====================
        // ====== GETTER ======
        // ====================
        
        /**
         * Restituisce l'ID dell'utente
         * @return int|null
         */
        public function getId(): ?int { return $this->utente_id; }    // ? => Può restituire un intero oppure null (nel caso di utente non trovato)
        
        /**
         * Verifica se l'utente è amministratore
         * @return bool
         */
        public function isAdmin(): bool { return $this->admin; }
        
        /**
         * Restituisce il nome dell'utente
         * @return string|null
         */
        public function getNomeUtente(): ?string { return $this->nome_utente; }
        
        /**
         * Restituisce il cognome dell'utente
         * @return string|null
         */
        public function getCognomeUtente(): ?string { return $this->cognome_utente; }
        
        /**
         * Restituisce la data di nascita dell'utente
         * @return string|null
         */
        public function getDataNascita(): ?string { return $this->data_nascita;}
        
        /**
         * Restituisce l'email dell'utente
         * @return string|null
         */
        public function getEmail(): ?string { return $this->email; }
        
        // TODO: Verificare se posso togliere questo getter (al momento non viene usato) [Potrebbe essere un problema di sicurezza]
        public function getPassword(): string {return $this->password; }
        
        
        
        // ======================================
        // ====== SETTER (con validazioni) ======
        // ======================================
        
        /**
         * Imposta l'ID dell'utente
         *
         * @param int $utente_id = ID dell'utente (deve essere un intero positivo)
         * @throws InvalidArgumentException se l'ID non è valido
         */
        public function setId(int $utente_id)
        {
            if ($utente_id <= 0) {
                throw new InvalidArgumentException("L'ID utente deve essere un intero positivo");
            }
            $this->utente_id = $utente_id;
        }
        
        /**
         * Imposta il nome dell'utente
         * - Rimuove spazi all'inizio e alla fine della stringa
         * - Verifica che il nome non sia vuoto e abbia almeno 2 caratteri
         * - Previene XSS con htmlspecialchars()
         *
         * @param string $nome_utente = Nome dell'utente
         * @throws InvalidArgumentException se il nome non è valido
         */
        public function setNomeUtente(string $nome_utente): void
        {
            // trim => Rimuove spazi all'inizio e alla fine della stringa
            $nome_utente = trim($nome_utente);
            
            // Controllo che il nome non sia stringa vuota o un solo carattere
            if ($nome_utente === '' || strlen($nome_utente) < 2 ) {
                // Lancia un'eccezione => obbliga a chi usa la classe a gestire l'errore.
                throw new InvalidArgumentException("Il nome deve contenere almeno due caratteri");
            }
            // Salva il valore facendo l'escape dei caratteri per prevenire XSS.
            $this->nome_utente = htmlspecialchars($nome_utente);
        }
        
        /**
         * Imposta il cognome dell'utente
         * - Rimuove spazi all'inizio e alla fine della stringa
         * - Verifica che il cognome non sia vuoto e abbia almeno 2 caratteri
         * - Previene XSS con htmlspecialchars()
         *
         * @param string $cognome_utente = Cognome dell'utente
         * @throws InvalidArgumentException se il cognome non è valido
         */
        public function setCognomeUtente(string $cognome_utente): void
        {
            // trim => Rimuove spazi all'inizio e alla fine della stringa
            $cognome_utente = trim($cognome_utente);
            
            // Controllo che il cognome non sia stringa vuota o un solo carattere
            if ($cognome_utente === '' || strlen($cognome_utente) < 2) {
                // Lancia un'eccezione => obbliga a chi usa la classe a gestire l'errore.
                throw new InvalidArgumentException("Il cognome deve contenere almeno due caratteri");
            }
            // Salva il valore facendo l'escape dei caratteri per prevenire XSS.
            $this->cognome_utente = htmlspecialchars($cognome_utente);
        }
        
        /**
         * Imposta la data di nascita dell'utente
         * @param string $data_nascita = Data di nascita dell'utente (formato YYYY-MM-DD)
         */
        public function setDataNascita($data_nascita): void
        {
            // TODO: Inserire regular regex per validare la data.
            // TODO: Inserire verifica che la data non sia futura.
            // TODO: Inserire verifica età minima (es. 13 anni)
            // https://stackoverflow.com/questions/15491894/regex-to-validate-date-formats-dd-mm-yyyy-dd-mm-yyyy-dd-mm-yyyy-dd-mmm-yyyy
            // if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $data))
            $this->data_nascita = $data_nascita;
        }
        
        /**
         * Imposta l'email dell'utente
         * - Sanifica l'email rimuovendo caratteri illegali
         * - Valida l'email secondo i criteri di un indirizzo valido
         * - rende la stringa tutta minuscola
         *
         * Vedere nota 00 in fondo al file per maggiori dettagli su filter_var()
         *
         * @param string $email = Email dell'utente
         * @throws InvalidArgumentException se l'email non è valida
         */
        public function setEmail(string $email): void
        {
            // Rimuove caratteri illegali dalla mail
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            
            // Valida il formato dell'email (restituisce false se non è valida)
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            
            if (!$email) {
                throw new InvalidArgumentException("Email non valida");
            }
            // Setto in minuscolo per evitare problemi di case sensitivity e avere piu uniformità
            $this->email = strtolower($email);
        }
        
        /**
         * Imposto la password con hashing
         * - Verifica che la password abbia almeno 6 caratteri
         * - Cripta la password usando password_hash()
         *
         * @param string $password = Password in chiaro
         */
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
        
        /**
         * Imposto la password in chiaro (usata solo per il login)
         * - Verifica che la password abbia almeno 6 caratteri
         *
         * @param string $password = Password in chiaro
         */
        public function setPassword_in_chiaro (string $password): void
        {
            if (strlen($password) < 6) {        // Verifico che la password abbia almeno 6 caratteri.
                throw new InvalidArgumentException("Password troppo corta. Almeno 6 caratteri.");
            }
            $this->password_in_chiaro = $password;
        }
        
        
        
        // ===========================
        // ====== METODI CRUD =======
        // ===========================
        
        /**
         * SEARCH ALL => Recupera tutti gli utenti dal database
         *
         * @return false|PDOStatement => Restituisce il risultato della query come PDOStatement o false in caso di errore
         * @throws PDOException in caso di errore nella query
         */
        function searchAll(): false|PDOStatement
        {
            try {
                $query = "SELECT
                        utente_id,
                        admin,
                        nome_utente,
                        cognome_utente,
                        data_nascita,
                        email
                      FROM {$this->table_name}
                      ORDER BY utente_id";
                // Specifico le colonne per non includere la password nel risultato della query
                // Il risultato viene inoltre ordinato in base all'ID crescente
                
                // Invio i valori ai parametri della query
                // I valori del nuovo utente sono nelle variabili di istanza.
                $stmt = $this->conn->prepare($query);
                
                // Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Errore Utente->searchAll(): " . $e->getMessage());
                return false;
            }
        }
        
        /**
         * CREATE => Crea un nuovo utente nel database
         * - Usa i valori presenti nelle variabili di istanza per creare il nuovo utente
         * - Gestisce l'eccezione in caso di email duplicata
         *
         * @return false|PDOStatement => Restituisce il risultato della query come PDOStatement o false in caso di errore
         * @throws InvalidArgumentException => in caso di errore del database (es. email duplicata)
         */
        function create(): false|PDOStatement
        {
            try {
                // Preparo la query
                $query = "INSERT INTO {$this->table_name} SET
                           nome_utente=:nome_utente,
                           cognome_utente=:cognome_utente,
                           data_nascita=:data_nascita,
                           email=:email,
                           password=:password";
                
                // Invio i valori ai parametri della query
                $stmt = $this->conn->prepare($query);
                
                // Bind dei parametri
                $stmt->bindParam(":nome_utente", $this->nome_utente);
                $stmt->bindParam(":cognome_utente", $this->cognome_utente);
                $stmt->bindParam(":data_nascita", $this->data_nascita);
                $stmt->bindParam(":email", $this->email);
                $stmt->bindParam(":password", $this->password);// Eseguo la query e restituisco il risultato
                
                // Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                // Gestione della mail duplicata
                // 23000 => codice di errore SQLSTATE per violazione di vincoli di integrità (es. chiave unica duplicata)
                if ($e->getCode() == 23000) {
                    throw new InvalidArgumentException("Email già presente a sistema");
                }
                error_log("Errore utente->create(): " . $e->getMessage());
                return false;
            }
        }
        
        /**
         * READ ONE => Recupera un utente dal database in base all'ID
         * - Usa l'ID presente nella variabile di istanza $utente_id
         * - Popola le altre variabili di istanza con i valori recuperati dal database
         *
         * @return void
         * @throws PDOException in caso di errore nella query
         */
        function readOne(): void
        {
            
            try {
                $query = "SELECT * FROM {$this->table_name} WHERE utente_id = :utente_id";
                
                // Invio i valori ai parametri della query
                $stmt = $this->conn->prepare($query);
                
                // Bind del parametro
                $stmt->bindParam(':utente_id', $this->utente_id);
                
                // Eseguo la query
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);// Leggo la prima (e unica) riga del risultato della query
                
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
            } catch (PDOException $e) {
                error_log("Errore utente->readOne(): " . $e->getMessage());
            }
            // la funzione readOne non restituisce un risultato, bensì modifica l'oggetto su cui viene invocata (cioè l'utente)
        }
        
        /**
         * UPDATE => Aggiorna i dati di un utente nel database
         * - Usa l'ID presente nella variabile di istanza $utente_id per identificare l'utente da aggiornare
         * - Usa i valori presenti nelle altre variabili di istanza per aggiornare i dati dell'utente
         * - Gestisce l'eccezione in caso di email duplicata
         * - Non è possibile aggiornare la password con questo metodo
         * TODO: Implementare un metodo separato per l'aggiornamento della password
         *
         * @return false|PDOStatement => Restituisce il risultato della query come PDOStatement o false in caso di errore
         * @throws InvalidArgumentException => in caso di errore del database (es. email duplicata)
         */
        function update(): false|PDOStatement
        {
            
            try {
                // Preparo la query
                $query = "UPDATE {$this->table_name} SET
                          nome_utente = :nome_utente,
                          cognome_utente = :cognome_utente,
                          data_nascita = :data_nascita,
                          email = :email
                          WHERE
                          utente_id = :utente_id";
                
                // Invio i valori per i parametri (i nuovi valori dell'utente sono nelle variabili di istanza).
                $stmt = $this->conn->prepare($query);
                
                // Bind dei parametri
                $stmt->bindParam(':nome_utente', $this->nome_utente);
                $stmt->bindParam(':cognome_utente', $this->cognome_utente);
                $stmt->bindParam(':data_nascita', $this->data_nascita);
                $stmt->bindParam(':email', $this->email);// $stmt->bindParam(':password', $this->password);
                $stmt->bindParam(':utente_id', $this->utente_id);// Eseguo la query e restituisco il risultato
                
                // Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                // Gestione della mail duplicata
                // 23000 => codice di errore SQLSTATE per violazione di vincoli di integrità (es. chiave unica duplicata)
                if ($e->getCode() == 23000) {
                    throw new InvalidArgumentException("Email già presente a sistema");
                }
                error_log("Errore utente->update(): " . $e->getMessage());
                return false;
            }
        }
        
        /**
         * DELETE => Elimina un utente dal database
         * - Usa l'ID presente nella variabile di istanza $utente_id per identificare l'utente da eliminare
         *
         * @return bool => Restituisce true se l'eliminazione è andata a buon fine, false in caso di errore
         */
        function delete(): bool
        {
            
            try {
                $query = "DELETE FROM {$this->table_name} WHERE utente_id = :utente_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':utente_id', $this->utente_id);// Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                // Log dell'errore
                error_log("Errore utente->delete(): " . $e->getMessage());
                
                // Se ci sono vincoli di chiave esterna (per esempio l'utente ha dei record collegati in altre tabelle)
                if ($e->getCode() == 23000) {
                    throw new InvalidArgumentException(
                        "Impossibile eliminare l'utente: esistono record collegati a sistema"
                    );
                }
                return false;
            }
        }
        
        
        
        // ===============================
        // ====== METODI DI RICERCA ======
        // ===============================
        
        /**
         * SEARCH BY KEYWORD => Cerca utenti in base a una parola chiave
         * - La ricerca viene effettuata sui campi: nome_utente, cognome_utente, email
         * - Usa il parametro $keyword per la ricerca
         *
         * @param string $keyword = Parola chiave per la ricerca
         * @return false|PDOStatement => Restituisce il risultato della query come PDOStatement o false in caso di errore
         * @throws PDOException in caso di errore nella query
         */
        function searchByKeyword($keyword)
        {
            try {
                // Preparo la query di ricerca
                $query = "SELECT * FROM {$this->table_name} WHERE
                             nome_utente LIKE :keyword OR
                             cognome_utente LIKE :keyword OR
                             email LIKE :keyword";
                
                $stmt = $this->conn->prepare($query);
                
                // Aggiungo i caratteri jolly per la ricerca parziale (SQL)
                $keyword = "%{$keyword}%";
                
                // Bind del parametro
                $stmt->bindParam(':keyword', $keyword);
                
                // Eseguo la query
                $stmt->execute();
                
                // Restituisco il risultato della query (in questo caso un recordset)
                return $stmt;
            } catch (PDOException $e) {
                error_log("Errore utente->searchByKeyword(): " . $e->getMessage());
                return false;
            }
        }
        
        
        
        // ======================================
        // ====== METODI DI AUTENTICAZIONE ======
        // ======================================
        
        // Funzione per effettuare il login;
        public function login(): bool
        {
            
            try {
                // Preparo la query per recuperare l'utente in base all'email
                $query = "SELECT utente_id, nome_utente, password, admin, email, data_nascita
                             FROM {$this->table_name}
                             WHERE email = :email
                             LIMIT 1";// Limito il risultato a 1 riga (non ha senso avere piu utenti con la stessa email)
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':email', $this->email);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verifico se l'utente è stato trovato
                // Verifico se la password in chiaro corrisponde a quella criptata nel database
                if ($row && password_verify($this->password_in_chiaro, $row['password'])) {
                    $this->utente_id = $row['utente_id'];
                    $this->nome_utente = $row['nome_utente'];
                    $this->admin = $row['admin'];
                    $this->email = $row['email'];
                    $this->data_nascita = $row['data_nascita'];
                    
                    // Pulisco la password in chiaro dopo averla usata
                    $this->password_in_chiaro = null;
                    
                    // Log di successo
                    error_log("Login riuscito per l'utente: " . $this->email);
                    
                    return true;
                }
                error_log("Login fallito per email: " . $this->email);
                return false;
            } catch (PDOException $e) {
                error_log("Errore utente->login(): " . $e->getMessage());
                return false;
            }
        }
    }
   
    
    
 /*                 NOTE
    Nota00
- filter_var() = Funzione nativa di PHP che si usa per filtrare i dati
- FILTER_VALIDATE_EMAIL = valida la stringa in base ai criteri di un indirizzo valido (per esempio presenza di '@').
      Se l'email è valida => restituisce la stringa stessa (che in questo caso viene riassegnata alla variabile email)
      Se l'email non è valida => restituisce false.
- FILTER_SANITAZE_EMAIL = rimuove caratteri illegali dalla mail
 https://www.w3schools.com/php/filter_validate_email.asp  */
