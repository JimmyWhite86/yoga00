    <?php
        
        /**
         * Database
         *
         * Classe Database per la gestione della connessione al database MySQL utilizzando PDO.
         * Le credenziali di connessione sono caricate da un file esterno per motivi di sicurezza.
         *
         * @path /Applications/MAMP/htdocs/yoga00/backend/database/Database.php
         * @package backend\database
         *
         * @author Bianchi Andrea
         * @version 1.0
         */
        
    class Database {
        
        
        // Preparo le variabili che conterranno le credenziali contenute un file separato.
        /** @var string */
        private string $host;
        
        /** @var string */
        private string $db_name;
        
        /** @var string */
        private string $username;
        
        /** @var string */
        private string $password;
        
        /**
         * Variabile che conterrà l'istanza PDO
         * @var PDO|null
         */
        private $conn;
        
        
        /**
         * Costruttore della classe database
         *
         * Carica le credenziali di connessione dal file esterno.
         * Inizilizza le variabili con i valori delle credenziali.
         *
         * @return void
         */
        public function __construct()
        {
            // Percorso in cui è salvato il file che contiene le credenziali di connessione
            $credenziali = require __DIR__ . "/../credenziali.php";
            
            $this->host     = $credenziali['host'];
            $this->db_name  = $credenziali['dbname'];
            $this->username = $credenziali['username'];
            $this->password = $credenziali['password'];
        }
        
        
        /** Metodo che restituisce la connessione
         *
         * Crea una connessione al database MySQL utilizzando PDO.
         * Gestisce eventuali errori di connessione tramite eccezioni.
         *
         * @return PDO|null Restituisce l'oggetto PDO se la connessione ha successo, altrimenti null.
         */
        public function getConnection()
        {
            // Inizializzo la variabile a null
            $this->conn = null;
            
            try {
                // Creo la connessione PDO
                $this->conn = new PDO(
                    "mysql:host={$this->host};
                    dbname={$this->db_name};
                    charset=utf8mb4",
                    $this->username,
                    $this->password
                );
            } catch (PDOException $exception) {
                // Catturo eventuali errori e stampo il relativo messaggio
                // TODO: Togliere stampa dell'errore in produzione.
                echo "Errore di connessione al database: " . $exception->getMessage();
            }
            
            // Restituisce il PDO object o null se fallisce.
            return $this->conn;
            
        }
    }