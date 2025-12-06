-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Creato il: Dic 03, 2025 alle 15:33
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
  `nome_abbonamento` varchar(50) NOT NULL,
  `descrizione_abbonamento` text NOT NULL,
  `prezzo_abbonamento` decimal(10,0) NOT NULL,
  `durata_lezioni` int(11) NOT NULL,
  `durata_giorni` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `lezioni`
--

CREATE TABLE `lezioni` (
  `lezione_id` int(11) NOT NULL,
  `nome_lezione` varchar(50) NOT NULL,
  `descrizione_lezione` text NOT NULL,
  `giorno_lezione` enum('lunedi','martedi','mercoledi','giovedi','venerdi','sabato','domenica') NOT NULL,
  `ora_lezione` time NOT NULL,
  `posti_totali` int(11) NOT NULL,
  `posti_liberi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `utente_id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `nome_utente` varchar(50) NOT NULL,
  `cognome_utente` varchar(50) NOT NULL,
  `data_nascita` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `abbonamenti`
--
ALTER TABLE `abbonamenti`
  ADD PRIMARY KEY (`abbonamento_id`);

--
-- Indici per le tabelle `lezioni`
--
ALTER TABLE `lezioni`
  ADD PRIMARY KEY (`lezione_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`utente_id`),
  ADD UNIQUE KEY `mail_unique` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `abbonamenti`
--
ALTER TABLE `abbonamenti`
  MODIFY `abbonamento_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `lezioni`
--
ALTER TABLE `lezioni`
  MODIFY `lezione_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `utente_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
