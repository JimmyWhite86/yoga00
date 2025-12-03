<?php

    // PROVA GITHUB 20251202.01
    
    require_once '../database/Database.php';
    
    class Utente
    {
        private $conn;                      // Connessione al DB (inizializzata nel costruttore);
        private $table_name = "utenti";     // Nome della tabella nel database;
        
        // ATTRIBUTI UTENTE
        private $utente_id;
        private $admin;
        private $nome_utente;
        private $cognome_utente;
        private $data_nascita;
        private $email;
        private $password;
        
        
        // COSTRUTTORE => Inizializza la variabile per la connessione al PDO
        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        
        // GETTER
        public function getUtenteId(): int { return $this->utente_id; }    // TODO: ?int => Può restituire un intero oppure null (nel caso di utente non trovato)
        public function isAdmin(): bool { return $this->admin; }
        public function getNomeUtente(): string { return $this->nome_utente; }
        public function getCognomeUtente(): string { return $this->cognome_utente; }
        public function getDataNascita() { return $this->data_nascita;}
        public function getEmail() { return $this->email; }
        
        

        // SETTER (con validazioni)
        public function setNomeUtente($nome_utente): void
        {
            $nome_utente = trim($nome_utente);                          // trim => Rimuove spazi all'inizio e alla fine della stringa
            if ($nome_utente === '' || strlen($nome_utente) < 2 ) {     // Controllo che il nome non sia stringa vuota o un solo carattere
                throw new InvalidArgumentException("Il nome deve contenere almeno due caratteri");  // Lancia un'eccezione => obbliga a chi usa la classe a gestire l'errore.
            }
            $this->nome_utente = htmlspecialchars($nome_utente);        // Salva il valore facendo l'escape dei caratteri per prevenire XSS.
        }
        
        public function setCognomeUtente($cognome_utente): void
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
        
        public function setEmail($email): void
        {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL); // vedere => Nota00
            if (!$email) {
                throw new InvalidArgumentException("Email non valida");
            }
            $this->email = $email;
        }
        
        
        public function setPassword($password): void
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
        function searchAll() {
            $query = "SELECT * FROM utenti";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute();
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
