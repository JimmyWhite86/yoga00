-- phpMyAdmin SQL Dump -
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS yoga00;
CREATE DATABASE yoga00 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yoga00;

-- ===================================================================
-- 1. Utenti
-- ===================================================================
CREATE TABLE utenti (
                        utente_id INT AUTO_INCREMENT PRIMARY KEY,
                        admin TINYINT(1) NOT NULL DEFAULT 0,
                        nome VARCHAR(50) NOT NULL,
                        cognome VARCHAR(50) NOT NULL,
                        data_nascita DATE NOT NULL,
                        email VARCHAR(100) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        data_scadenza_visita_medica DATE NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- 2. Abbonamenti (i pacchetti venduti)
-- ===================================================================
CREATE TABLE abbonamenti (
                             abbonamento_id INT AUTO_INCREMENT PRIMARY KEY,
                             nome VARCHAR(50) NOT NULL,
                             descrizione TEXT,
                             prezzo DECIMAL(10,2) NOT NULL,
                             durata_lezioni INT NULL,        -- NULL = illimitato
                             durata_giorni INT NULL          -- NULL = illimitato nel tempo
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================================
-- 3. Acquisto (storico acquisti abbonamenti)
-- ===================================================================
CREATE TABLE acquisti (
                          acquisto_id INT AUTO_INCREMENT PRIMARY KEY,
                          utente_id INT NOT NULL,
                          abbonamento_id INT NOT NULL,
                          data_acquisto DATE NOT NULL DEFAULT (CURRENT_DATE),
                          data_scadenza DATE NULL,
                          lezioni_rimanenti INT NULL,     -- NULL = illimitato
                          attivo TINYINT(1) NOT NULL DEFAULT 1,

                          FOREIGN KEY (utente_id) REFERENCES utenti(utente_id) ON DELETE CASCADE,
                          FOREIGN KEY (abbonamento_id) REFERENCES abbonamenti(abbonamento_id) ON DELETE RESTRICT,

                          INDEX idx_utente (utente_id),
                          INDEX idx_scadenza (data_scadenza)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================================
-- 4. Lezioni ricorrenti settimanali
-- ===================================================================
CREATE TABLE lezioni (
                         lezione_id INT AUTO_INCREMENT PRIMARY KEY,
                         nome VARCHAR(100) NOT NULL,
                         descrizione TEXT,
                         giorno_settimana ENUM('lunedi','martedi','mercoledi','giovedi','venerdi','sabato','domenica') NOT NULL,
                         ora_inizio TIME NOT NULL,
                         ora_fine TIME NOT NULL,
                         insegnante VARCHAR(100),
                         posti_totali INT NOT NULL DEFAULT 20,
                         attiva TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================================
-- 5. Prenotazione (per una data specifica)
-- ===================================================================
CREATE TABLE prenotazioni (
                              prenotazione_id INT AUTO_INCREMENT PRIMARY KEY,
                              utente_id INT NOT NULL,
                              lezione_id INT NOT NULL,
                              data_prenotazione DATE NOT NULL,                    -- la data in cui viene a fare lezione
                              stato ENUM('confermata','cancellata') NOT NULL DEFAULT 'confermata',
                              acquistato_con INT NULL,                            -- FK opzionale a acquisti.acquisto_id (per tracciare consumo)
                              creato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                              FOREIGN KEY (utente_id) REFERENCES utenti(utente_id) ON DELETE CASCADE,
                              FOREIGN KEY (lezione_id) REFERENCES lezioni(lezione_id) ON DELETE RESTRICT,
                              FOREIGN KEY (acquistato_con) REFERENCES acquisti(acquisto_id) ON DELETE SET NULL,

                              UNIQUE KEY unica_prenotazione (utente_id, lezione_id, data_prenotazione), -- una persona non può prenotare 2 volte lo stesso giorno
                              INDEX idx_data (data_prenotazione),
                              INDEX idx_lezione_data (lezione_id, data_prenotazione)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;