-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Creato il: Dic 10, 2025 alle 09:54
-- Versione del server: 5.7.39
-- Versione PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yoga00`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `abbonamenti`
--

CREATE TABLE `abbonamenti` (
  `abbonamento_id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descrizione` text,
  `prezzo` decimal(10,2) NOT NULL,
  `durata_lezioni` int(11) DEFAULT NULL,
  `durata_giorni` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `abbonamenti`
--

INSERT INTO `abbonamenti` (`abbonamento_id`, `nome`, `descrizione`, `prezzo`, `durata_lezioni`, `durata_giorni`) VALUES
(1, 'Open Card 10 ingressi', '10 lezioni valide 6 mesi', '120.00', 10, 180),
(2, 'Open Card 20 ingressi', '20 lezioni valide 12 mesi', '220.00', 20, 365),
(3, 'Mensile Illimitato', 'Accesso libero per 30 giorni', '89.00', NULL, 30),
(4, 'Trimestrale Illimitato', '3 mesi di yoga senza limiti', '240.00', NULL, 90),
(5, 'Annuale Illimitato', '1 anno completo di yoga', '799.00', NULL, 365),
(6, 'Annuale Plus', 'Annuale + visita medica inclusa', '899.00', NULL, 365),
(7, 'Bimestrale 8 ingressi', '8 lezioni in 60 giorni', '95.00', 8, 60),
(8, 'Settimanale Illimitato', '7 giorni di yoga intensivo', '45.00', NULL, 7),
(9, 'Prova 3 lezioni', 'Pacchetto prova per nuovi iscritti', '30.00', 3, 30),
(10, 'Famiglia 2 persone', 'Annuale per due persone', '1290.00', NULL, 365),
(11, 'Prova primo inserimento', 'Terza prova di modifica', '120.00', 10, 180),
(12, 'Prova secondo inserimento', '10 lezioni valide 6 mesi', '120.00', 10, 180);

-- --------------------------------------------------------

--
-- Struttura della tabella `acquisti`
--

CREATE TABLE `acquisti` (
  `acquisto_id` int(11) NOT NULL,
  `utente_id` int(11) NOT NULL,
  `abbonamento_id` int(11) NOT NULL,
  `data_acquisto` date NOT NULL DEFAULT '2025-01-01',
  `data_scadenza` date DEFAULT NULL,
  `lezioni_rimanenti` int(11) DEFAULT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `acquisti`
--

INSERT INTO `acquisti` (`acquisto_id`, `utente_id`, `abbonamento_id`, `data_acquisto`, `data_scadenza`, `lezioni_rimanenti`, `attivo`) VALUES
(1, 2, 1, '2025-11-01', '2026-04-30', 7, 1),
(2, 1, 3, '2025-11-15', '2027-12-15', 2, 1),
(4, 5, 2, '2025-10-20', '2026-10-20', 18, 1),
(5, 6, 4, '2025-11-25', '2026-02-25', NULL, 1),
(6, 7, 1, '2025-12-01', '2026-05-31', 4, 1),
(7, 8, 9, '2025-11-28', '2025-12-28', 2, 1),
(8, 9, 3, '2025-12-01', '2026-01-01', NULL, 1),
(9, 10, 6, '2025-06-01', '2026-06-01', NULL, 1),
(10, 1, 1, '2025-11-01', '2026-04-30', 666, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `lezioni`
--

CREATE TABLE `lezioni` (
  `lezione_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descrizione` text,
  `giorno_settimana` enum('lunedi','martedi','mercoledi','giovedi','venerdi','sabato','domenica') NOT NULL,
  `ora_inizio` time NOT NULL,
  `ora_fine` time NOT NULL,
  `insegnante` varchar(100) DEFAULT NULL,
  `posti_totali` int(11) NOT NULL DEFAULT '20',
  `attiva` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `lezioni`
--

INSERT INTO `lezioni` (`lezione_id`, `nome`, `descrizione`, `giorno_settimana`, `ora_inizio`, `ora_fine`, `insegnante`, `posti_totali`, `attiva`) VALUES
(1, 'Hatha Yoga', 'Lezioni tradizionali di Hatha', 'lunedi', '18:30:00', '19:45:00', 'Laura', 20, 1),
(2, 'Vinyasa Flow', 'Sequenze dinamiche e fluide', 'martedi', '19:00:00', '20:15:00', 'Marco', 18, 1),
(3, 'Yin Yoga', 'Yoga lento e profondo', 'mercoledi', '18:00:00', '19:15:00', 'Giulia', 22, 1),
(4, 'Ashtanga', 'Serie primaria guidata', 'giovedi', '07:00:00', '08:30:00', 'Alessandro', 15, 1),
(5, 'Power Yoga', 'Yoga dinamico e tonificante', 'venerdi', '18:30:00', '19:45:00', 'Sofia', 20, 1),
(6, 'Yoga Dolce', 'Perfetto per principianti e senior', 'sabato', '10:00:00', '11:15:00', 'Chiara', 25, 1),
(7, 'Meditazione Guidata', 'Mindfulness e respirazione', 'domenica', '09:00:00', '10:00:00', 'Davide', 30, 1),
(8, 'Prenatal Yoga', 'Yoga per donne in gravidanza', 'martedi', '10:30:00', '11:45:00', 'Valentina', 12, 1),
(9, 'Restorative Yoga', 'Rilassamento profondo con supporti', 'venerdi', '20:00:00', '21:15:00', 'Lorenzo', 18, 1),
(10, 'Kundalini Yoga', 'Risveglio dell’energia', 'sabato', '18:00:00', '19:30:00', 'Marco', 16, 1),
(11, 'Yoga della risata', 'Prova update', 'lunedi', '18:30:00', '19:45:00', 'Tinti e Rapone', 20, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE `prenotazioni` (
  `prenotazione_id` int(11) NOT NULL,
  `utente_id` int(11) NOT NULL,
  `lezione_id` int(11) NOT NULL,
  `data_prenotata` date NOT NULL,
  `stato` enum('confermata','cancellata') NOT NULL DEFAULT 'confermata',
  `acquistato_con` int(11) DEFAULT NULL,
  `prenotato_il` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`prenotazione_id`, `utente_id`, `lezione_id`, `data_prenotata`, `stato`, `acquistato_con`, `prenotato_il`) VALUES
(1, 2, 1, '2025-12-09', 'confermata', 1, '2025-12-06 16:08:44'),
(2, 3, 2, '2025-12-10', 'confermata', 2, '2025-12-06 16:08:44'),
(4, 5, 4, '2025-12-12', 'confermata', 4, '2025-12-06 16:08:44'),
(5, 6, 5, '2025-12-13', 'confermata', 5, '2025-12-06 16:08:44'),
(6, 2, 6, '2025-12-14', 'confermata', 1, '2025-12-06 16:08:44'),
(7, 8, 7, '2025-12-15', 'confermata', 7, '2025-12-06 16:08:44'),
(8, 3, 1, '2025-12-16', 'confermata', 2, '2025-12-06 16:08:44'),
(9, 9, 2, '2025-12-17', 'confermata', 8, '2025-12-06 16:08:44'),
(10, 10, 8, '2025-12-10', 'confermata', 9, '2025-12-06 16:08:44'),
(11, 2, 1, '2027-12-09', 'confermata', 1, '2025-12-10 09:51:30');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `utente_id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `nome_utente` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cognome_utente` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_nascita` date NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`utente_id`, `admin`, `nome_utente`, `cognome_utente`, `data_nascita`, `email`, `password`, `created_at`) VALUES
(1, 1, 'Admin', 'Yoga', '1980-05-15', 'admin@yoga.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-12-06 16:08:43'),
(2, 0, 'Laura', 'Bianchi', '1992-03-22', 'laura.bianchi@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(3, 0, 'Marco', 'Rossi', '1988-11-10', 'marco.rossi@email.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(4, 0, 'Giulia', 'Verdi', '1995-07-30', 'giulia.verdi@gmail.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(5, 0, 'Mario', 'Verdi', '1990-05-15', 'mario.verdi@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(6, 0, 'Sofia', 'Ricci', '1993-09-05', 'sofia.ricci@email.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(7, 0, 'Davide', 'Moretti', '1985-12-12', 'davide.moretti@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(8, 0, 'Chiara', 'Lombardi', '1997-04-25', 'chiara.lombardi@gmail.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(9, 0, 'Matteo', 'Conti', '1991-08-08', 'matteo.conti@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(10, 0, 'Valentina', 'Martini', '1994-06-17', 'valentina.martini@email.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q', '2025-12-06 16:08:43'),
(13, 0, 'Admin', 'Yoga', '1980-05-15', 'prova01@yoga.it', '$2y$10$XkbJ5mAZuyC2Z3xNOp3Z1.B/.ja0t.2bmeBZGFbloA0m/HD.l.fHS', '2025-12-07 14:14:36');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `abbonamenti`
--
ALTER TABLE `abbonamenti`
  ADD PRIMARY KEY (`abbonamento_id`);

--
-- Indici per le tabelle `acquisti`
--
ALTER TABLE `acquisti`
  ADD PRIMARY KEY (`acquisto_id`),
  ADD KEY `abbonamento_id` (`abbonamento_id`),
  ADD KEY `idx_utente` (`utente_id`),
  ADD KEY `idx_scadenza` (`data_scadenza`);

--
-- Indici per le tabelle `lezioni`
--
ALTER TABLE `lezioni`
  ADD PRIMARY KEY (`lezione_id`);

--
-- Indici per le tabelle `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`prenotazione_id`),
  ADD UNIQUE KEY `unica_prenotazione` (`utente_id`,`lezione_id`,`data_prenotata`),
  ADD KEY `acquistato_con` (`acquistato_con`),
  ADD KEY `idx_data` (`data_prenotata`),
  ADD KEY `idx_lezione_data` (`lezione_id`,`data_prenotata`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`utente_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `abbonamenti`
--
ALTER TABLE `abbonamenti`
  MODIFY `abbonamento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT per la tabella `acquisti`
--
ALTER TABLE `acquisti`
  MODIFY `acquisto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `lezioni`
--
ALTER TABLE `lezioni`
  MODIFY `lezione_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  MODIFY `prenotazione_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `utente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `acquisti`
--
ALTER TABLE `acquisti`
  ADD CONSTRAINT `acquisti_ibfk_1` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`utente_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acquisti_ibfk_2` FOREIGN KEY (`abbonamento_id`) REFERENCES `abbonamenti` (`abbonamento_id`);

--
-- Limiti per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`utente_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`lezione_id`) REFERENCES `lezioni` (`lezione_id`),
  ADD CONSTRAINT `prenotazioni_ibfk_3` FOREIGN KEY (`acquistato_con`) REFERENCES `acquisti` (`acquisto_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
