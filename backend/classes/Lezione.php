<?php
    
    /* ATTENZIONE: I COMMENTI DI QUESTO FILE SONO UNA PROVA DI DOCUMENTAZIONE */
    
    /**
     * Classe Lezione
     * Rappresenta una lezione di yoga con attributi e metodi per la gestione delle lezioni nel database.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/classes/Lezione.php
     * @package classes
     *
     * @author Bianchi Andrea
     * @version 1.0
     */
    
    class Lezione
    {
        
        // =================================
        // ====== ATTRIBUTI DI CLASSE ======
        // =================================
        
        /**
         * Connessione al database
         * Iniettata tramite costruttore
         *
         * @var PDO|null
         */
        private ?PDO $conn;
        
        /**
         * Nome della tabella nel database
         *
         * @var string
         */
        private string $table_name = "lezioni";
        
        
        // ===============================
        // ====== ATTRIBUTI LEZIONE ======
        // ===============================
        
        /**
         * @var int|null = id della lezione
         */
        private ?int $lezione_id;
        
        /**
         * @var string|null = nome della lezione
         */
        private ?string $nome;
        
        /**
         * @var string|null = descrizione della lezione
         */
        private ?string $descrizione;
        
        /**
         * @var string|null = giorno della settimana in cui si tiene la lezione
         */
        private ?string $giorno_settimana;
        
        /**
         * @var string|null = ora di inizio della lezione (formato HH:MM)
         */
        private ?string $ora_inizio;
        
        /**
         * @var string|null = ora di fine della lezione (formato HH:MM)
         */
        private ?string $ora_fine;
        
        /**
         * @var string|null = nome dell'insegnante della lezione
         */
        private ?string $insegnante;
        
        /**
         * @var int|null = numero totale di posti disponibili per la lezione
         */
        private ?int $posti_totali;
        
        /**
         * @var bool|null = stato di attivazione della lezione
         */
        private ?bool $attiva;
        
        
        
        // =========================
        // ====== COSTRUTTORE ======
        // =========================
        
        /**
         * Costruttore della classe Lezione
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
         *  Restituisce l'id della lezione
         * @return int|null
         */
        public function getId(): ?int { return $this->lezione_id; }
        
        /**  Restituisce il nome della lezione
         * @return string|null
         */
        public function getNome(): ?string { return $this->nome; }
        
        /**  Restituisce la descrizione della lezione
         * @return string|null
         */
        public function getDescrizione(): ?string { return $this->descrizione; }
        
        /**  Restituisce il giorno della settimana della lezione
         * @return string|null
         */
        public function getGiornoSettimana(): ?string { return $this->giorno_settimana; }
       
        /**  Restituisce l'ora di inizio della lezione
         * @return string|null
         */
        public function getOraInizio(): ?string { return $this->ora_inizio; }
        
        /**  Restituisce l'ora di fine della lezione
         * @return string|null
         */
        public function getOraFine(): ?string { return $this->ora_fine; }
        
        /**  Restituisce il nome dell'insegnante della lezione
         * @return string|null
         */
        public function getInsegnante(): ?string { return $this->insegnante; }
        
        /**  Restituisce il numero totale di posti della lezione
         * @return int|null
         */
        public function getPostiTotali(): ?int { return $this->posti_totali; }
        
        /**  Restituisce lo stato di attivazione della lezione
         * @return bool|null
         */
        public function isAttiva(): ?bool { return $this->attiva; }
        
        
        
        // ======================================
        // ====== SETTER (con validazioni) ======
        // ======================================
        
        /**
         * Imposta l'id della lezione
         *
         * @param int $id = id della lezione (deve essere un intero positivo)
         * @throws InvalidArgumentException se l'id non è valido
         */
        public function setId(int $id): void
        {
            if ($id <= 0) {
                throw new InvalidArgumentException("L'ID della lezione deve essere un intero positivo.");
            }
            $this->lezione_id = $id;
        }
        
        /**
         * Imposta il nome della lezione
         * - Rimuove spazi vuoti all'inizio e alla fine della stringa
         * - Verifica che il nome non sia vuoto e abbia almeno 2 caratteri
         * - Protegge da attacchi XSS convertendo i caratteri speciali in entità HTML
         *
         * @param string $nome = nome della lezione (minimo 2 caratteri)
         * @throws InvalidArgumentException se il nome non è valido
         */
        public function setNome(string $nome): void
        {
            $nome = trim($nome);
            if ($nome === '' || strlen($nome) < 2) {
                throw new InvalidArgumentException(
                    "Il nome della lezione deve contenere almeno due caratteri"
                );
            }
            $this->nome = htmlspecialchars($nome);
        }
        
        /**
         * Imposta la descrizione della lezione
         * - Rimuove spazi vuoti all'inizio e alla fine della stringa
         * - Verifica che la descrizione non sia vuota e abbia almeno 5 caratteri
         * - Protegge da attacchi XSS convertendo i caratteri speciali in entità HTML
         *
         * @param string $descrizione = descrizione della lezione (minimo 5 caratteri)
         * @throws InvalidArgumentException se la descrizione non è valida
         */
        public function setDescrizione(string $descrizione): void
        {
            $descrizione = trim($descrizione);
            if ($descrizione === '' || strlen($descrizione) < 5) {
                throw new InvalidArgumentException(
                    "La descrizione della lezione deve contenere almeno cinque caratteri"
                );
            }
            $this->descrizione = htmlspecialchars($descrizione);
        }
        
        /**
         * Imposta il giorno della settimana della lezione
         * - Rimuove spazi vuoti e mette la prima lettera maiuscola
         * - Verifica che il giorno sia uno dei giorni validi (Lunedì, Martedì, Mercoledì, Giovedì, Venerdì, Sabato, Domenica)
         *
         * @param string $giorno_settimana = giorno della settimana della lezione
         * @throws InvalidArgumentException se il giorno della settimana non è valido
         */
        public function setGiornoSettimana(string $giorno_settimana): void
        {
            // Rimuovo spazi vuoti e metto la prima lettera maiuscola
            // https://www.php.net/manual/en/function.strtolower.php
            // https://www.php.net/manual/en/function.ucfirst.php
            $giorno = ucfirst(strtolower(trim($giorno_settimana)));
            
            // Lista dei giorni validi
            $giorni_validi = ['Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato', 'Domenica'];
            
            // Controllo se il giorno inserito dall'utente è nella lista dei giorni validi.
            // true => attiva la strict mode (controlla anche il tipo di dato (come fare ===))
            if (!in_array($giorno, $giorni_validi, true)) {
                throw new InvalidArgumentException("Il giorno della settimana non è valido");
            }
            
            $this->giorno_settimana = $giorno;
        }
        
        /**
         * Imposta l'ora di inizio della lezione
         * - Verifica che l'ora sia nel formato HH:MM (24h)
         *
         * @param string $ora_inizio = ora di inizio della lezione (formato HH:MM)
         * @throws InvalidArgumentException se l'ora di inizio non è valida
         */
        public function setOraInizio($ora_inizio): void
        {
            if (!preg_match('/^(2[0-3]|[01][0-9]):([0-5][0-9])$/', $ora_inizio)) {
                throw new InvalidArgumentException("L'ora di inizio non è valida. Formato corretto HH:MM (24h).");
            }
            $this->ora_inizio = $ora_inizio;
        }
        
        /**
         * Imposta l'ora di fine della lezione
         * - Verifica che l'ora sia nel formato HH:MM (24h)
         * TODO: Verificare che l'ora di fine sia successiva all'ora di inizio
         *
         * @param string $ora_fine = ora di fine della lezione (formato HH:MM)
         * @throws InvalidArgumentException se l'ora di fine non è valida
         */
        public function setOraFine($ora_fine): void
        {
            if (!preg_match('/^(2[0-3]|[01][0-9]):([0-5][0-9])$/', $ora_fine)) {
                throw new InvalidArgumentException("L'ora di fine non è valida. Formato corretto HH:MM (24h).");
            }
            $this->ora_fine = $ora_fine;
        }
        
        /**
         * Imposta il nome dell'insegnante della lezione
         * - Verifica che il nome non sia vuoto e abbia almeno 2 caratteri
         *
         * @param string $insegnante = nome dell'insegnante della lezione (minimo 2 caratteri)
         * @throws InvalidArgumentException se il nome dell'insegnante non è valido
         */
        public function setInsegnante(string $insegnante): void
        {
            if($insegnante === '' || strlen($insegnante) < 2) {
                throw new InvalidArgumentException("Il nome dell'insegnante deve contenere almeno due caratteri");
            }
            $this->insegnante = $insegnante;
        }
        
        /**
         * Imposta il numero totale di posti della lezione
         * - Verifica che il numero di posti sia un intero positivo
         *
         * @param int $posti_totali = numero totale di posti della lezione (intero positivo)
         * @throws InvalidArgumentException se il numero di posti non è valido
         */
        public function setPostiTotali(int $posti_totali): void
        {
            if ($posti_totali <=0 ) {
                throw new InvalidArgumentException("Il numero di posti deve essere un intero positivo");
            }
            $this->posti_totali = $posti_totali;
        }
        
        /**
         * Imposta lo stato di attivazione della lezione
         *
         * @param bool $attiva = stato di attivazione della lezione
         */
        public function setAttiva(bool $attiva): void
        {
            $this->attiva = $attiva;
        }
        
        
        
        // ===========================
        // ====== METODI CRUD =======
        // ===========================
        
        /**
         * SEARCH ALL => Recupera tutte le lezioni dal database
         *
         * @return PDOStatement|false =Restituisce il risultato della query come PDOStatement o false in caso di errore
         * @throws PDOException in caso di errore nella query
         */
        public function searchAll(): PDOStatement|false
        {
            try {
                $query = "SELECT * FROM {$this->table_name} ORDER BY lezione_id;";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Errore Lezione->searchAll(): " . $e->getMessage());
                return false;
            }
        }
        
        /**
         * Create => Aggiunge una nuova lezione al database
         *
         * @return PDOStatement|false = Restituisce il risultato della query come PDOStatement o false in caso di errore
         * @throws PDOException in caso di errore nella query
         */
        public function create(): PDOStatement|false
        {
            try {
                $query = "INSERT INTO {$this->table_name} SET
                          nome=:nome,
                          descrizione=:descrizione,
                          giorno_settimana=:giorno_settimana,
                          ora_inizio=:ora_inizio,
                          ora_fine=:ora_fine,
                          insegnante=:insegnante,
                          posti_totali=:posti_totali,
                          attiva=:attiva;";// Preparo la query
                
                // Invio i valori ai parametri della query
                // I valori della nuova lezione sono nelle variabili di istanza
                $stmt = $this->conn->prepare($query);
                
                // Bind dei parametri
                $stmt->bindParam(':nome', $this->nome);
                $stmt->bindParam(':descrizione', $this->descrizione);
                $stmt->bindParam(':giorno_settimana', $this->giorno_settimana);
                $stmt->bindParam(':ora_inizio', $this->ora_inizio);
                $stmt->bindParam(':ora_fine', $this->ora_fine);
                $stmt->bindParam(':insegnante', $this->insegnante);
                $stmt->bindParam(':posti_totali', $this->posti_totali);
                $stmt->bindParam(':attiva', $this->attiva);// Eseguo la query e restituisco il risultato
                
                // Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Errore Lezione->create(): " . $e->getMessage());
                return false;
            }
        }
        
        /**
         * Read One => Recupera una singola lezione dal database in base all'id
         *
         * La funzione readOne non restituisce un risultato, ma modifica l'oggetto su cui viene invocata
         *
         * @return void
         * @throws PDOException in caso di errore nella query
         */
        public function readOne(): void
        {
            try {
                // Invio i valori ai parametri della query
                $query = "SELECT * FROM {$this->table_name} WHERE lezione_id = :lezione_id;";
                
                // Preparo la query
                $stmt = $this->conn->prepare($query);
                
                // Bind dei parametri
                $stmt->bindParam(':lezione_id', $this->lezione_id);
                
                // Eseguo la query
                $stmt->execute();
                
                // Leggo la prima (e unica) riga del risultato della query
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
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
            } catch (PDOException $e) {
                error_log("Errore Lezione->readOne(): " . $e->getMessage());
            }
        }
        
        /**
         * Update => Aggiorna una lezione esistente nel database
         *
         * @return PDOStatement|false = Restituisce il risultato della query come PDOStatement o false in caso di errore
         * @throws PDOException in caso di errore nella query
         */
        public function update(): PDOStatement|false
        {
            try {
                // preparo la query
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
                // Invio i valori ai parametri della query
                $stmt = $this->conn->prepare($query);
                
                // Bind dei parametri
                $stmt->bindParam(':nome', $this->nome);
                $stmt->bindParam(':descrizione', $this->descrizione);
                $stmt->bindParam(':giorno_settimana', $this->giorno_settimana);
                $stmt->bindParam(':ora_inizio', $this->ora_inizio);
                $stmt->bindParam(':ora_fine', $this->ora_fine);
                $stmt->bindParam(':insegnante', $this->insegnante);
                $stmt->bindParam(':posti_totali', $this->posti_totali);
                $stmt->bindParam(':attiva', $this->attiva);
                $stmt->bindParam(':lezione_id', $this->lezione_id);// Eseguo la query e restituisco il risultato
                
                // Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Errore Lezione->update(): " . $e->getMessage());
                return false;
            }
        }
        
        // Delete
        public function delete()
        {
            try {
                $query = "DELETE FROM {$this->table_name} WHERE lezione_id = :lezione_id;";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':lezione_id', $this->lezione_id);// Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Errore Lezione->delete(): " . $e->getMessage());
                
                // Gestione vincoli di integrità referenziale
                if ($e->getCode() === '23000') {
                    throw new Exception(
                        "Impossibile eliminare la lezione: esistono record collegati a sistema."
                    );
                }
                return false;
            }
        }
        
        
        // ===============================
        // ====== METODI DI RICERCA ======
        // ===============================
        
        // Funzione per cercare per parola chiave
        public function searchByKeyword($keyword)
        {
            try {
                // Preparo la query di ricerca
                // Uso LIKE per la ricerca parziale
                $query = "SELECT * FROM {$this->table_name} WHERE
                            nome LIKE :keyword OR
                            descrizione LIKE :keyword OR
                            insegnante LIKE :keyword";
                
                // Invio i valori ai parametri della query
                $stmt = $this->conn->prepare($query);
                
                // Aggiungo i wildcard % per la ricerca parziale
                $like_keyword = "%{$keyword}%";
                
                // Bind dei parametri
                $stmt->bindParam(':keyword', $like_keyword);// Eseguo la query e restituisco il risultato
                
                // Eseguo la query e restituisco il risultato
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Errore Lezione->searchByKeyword(): " . $e->getMessage());
                return false;
            }
        }
    }