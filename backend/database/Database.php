<?php

class Database {
    
    
    // Preparo le variabili che conterranno le credenziali contenute un file separato.
    private $host;
    private $db_name;
    private $username;
    private $password;
    
    // Variabile che conterrà l'istanza PDO
    private $conn;
    
    // Costruttore che carica le credenziali da file esterno:
    public function __construct()
    {
        // Percorso in cui è salvato il file che contiene le credenziali di connessione
        $credenziali = require __DIR__ . "/../credenziali.php";
        
        $this->host     = $credenziali['host'];
        $this->db_name  = $credenziali['dbname'];
        $this->username = $credenziali['username'];
        $this->password = $credenziali['password'];
    }
    
    // Metodo che restituisce la connessione
    public function getConnection()
    {
        // Inizializzo la variabile a null
        $this->conn = null;
        
        
        try {
            // Creo la connessione PDO
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
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