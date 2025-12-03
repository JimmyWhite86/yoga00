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
        
        
    }