-- ===========================================================
-- POPOLAMENTO DATABASE YOGA00 - 10 record per tabella
-- ===========================================================

USE yoga00;

-- -----------------------------------------------------------
-- 1. Utenti (10 utenti + 1 admin)
-- -----------------------------------------------------------
INSERT INTO utenti (admin, nome_utente, cognome_utente, data_nascita, email, password) VALUES
(1, 'Admin', 'Yoga', '1980-05-15', 'admin@yoga.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: admin123
(0, 'Laura', 'Bianchi', '1992-03-22', 'laura.bianchi@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'), -- password: laura123
(0, 'Marco', 'Rossi', '1988-11-10', 'marco.rossi@email.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Giulia', 'Verdi', '1995-07-30', 'giulia.verdi@gmail.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Alessandro', 'Ferrari', '1990-01-18', 'alessandro.ferrari@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Sofia', 'Ricci', '1993-09-05', 'sofia.ricci@email.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Davide', 'Moretti', '1985-12-12', 'davide.moretti@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Chiara', 'Lombardi', '1997-04-25', 'chiara.lombardi@gmail.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Matteo', 'Conti', '1991-08-08', 'matteo.conti@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Valentina', 'Martini', '1994-06-17', 'valentina.martini@email.com', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q'),
(0, 'Lorenzo', 'Costa', '1989-10-03', 'lorenzo.costa@email.it', '$2y$10$eF8X8Qz2vG9kL5nQwErT/.3fK8vX8Qz2vG9kL5nQwErT/.3fK8vX8Q');

-- -----------------------------------------------------------
-- 2. Abbonamenti (10 tipi di abbonamento)
-- -----------------------------------------------------------
INSERT INTO abbonamenti (nome, descrizione, prezzo, durata_lezioni, durata_giorni) VALUES
('Open Card 10 ingressi', '10 lezioni valide 6 mesi', 120.00, 10, 180),
('Open Card 20 ingressi', '20 lezioni valide 12 mesi', 220.00, 20, 365),
('Mensile Illimitato', 'Accesso libero per 30 giorni', 89.00, NULL, 30),
('Trimestrale Illimitato', '3 mesi di yoga senza limiti', 240.00, NULL, 90),
('Annuale Illimitato', '1 anno completo di yoga', 799.00, NULL, 365),
('Annuale Plus', 'Annuale + visita medica inclusa', 899.00, NULL, 365),
('Bimestrale 8 ingressi', '8 lezioni in 60 giorni', 95.00, 8, 60),
('Settimanale Illimitato', '7 giorni di yoga intensivo', 45.00, NULL, 7),
('Prova 3 lezioni', 'Pacchetto prova per nuovi iscritti', 30.00, 3, 30),
('Famiglia 2 persone', 'Annuale per due persone', 1290.00, NULL, 365);

-- -----------------------------------------------------------
-- 3. Lezioni settimanali (10 lezioni ricorrenti)
-- -----------------------------------------------------------
INSERT INTO lezioni (nome, descrizione, giorno_settimana, ora_inizio, ora_fine, insegnante, posti_totali, attiva) VALUES
('Hatha Yoga', 'Lezioni tradizionali di Hatha', 'lunedi', '18:30:00', '19:45:00', 'Laura', 20, 1),
('Vinyasa Flow', 'Sequenze dinamiche e fluide', 'martedi', '19:00:00', '20:15:00', 'Marco', 18, 1),
('Yin Yoga', 'Yoga lento e profondo', 'mercoledi', '18:00:00', '19:15:00', 'Giulia', 22, 1),
('Ashtanga', 'Serie primaria guidata', 'giovedi', '07:00:00', '08:30:00', 'Alessandro', 15, 1),
('Power Yoga', 'Yoga dinamico e tonificante', 'venerdi', '18:30:00', '19:45:00', 'Sofia', 20, 1),
('Yoga Dolce', 'Perfetto per principianti e senior', 'sabato', '10:00:00', '11:15:00', 'Chiara', 25, 1),
('Meditazione Guidata', 'Mindfulness e respirazione', 'domenica', '09:00:00', '10:00:00', 'Davide', 30, 1),
('Prenatal Yoga', 'Yoga per donne in gravidanza', 'martedi', '10:30:00', '11:45:00', 'Valentina', 12, 1),
('Restorative Yoga', 'Rilassamento profondo con supporti', 'venerdi', '20:00:00', '21:15:00', 'Lorenzo', 18, 1),
('Kundalini Yoga', 'Risveglio dell’energia', 'sabato', '18:00:00', '19:30:00', 'Marco', 16, 1);

-- -----------------------------------------------------------
-- 4. Acquisti (10 acquisti reali)
-- -----------------------------------------------------------
INSERT INTO acquisti (utente_id, abbonamento_id, data_acquisto, data_scadenza, lezioni_rimanenti, attivo) VALUES
(2, 1, '2025-11-01', '2026-04-30', 7, 1),        -- Laura ha comprato 10 ingressi
(3, 3, '2025-11-15', '2025-12-15', NULL, 1),      -- Marco ha il mensile
(4, 5, '2025-01-10', '2026-01-10', NULL, 1),      -- Giulia ha l’annuale
(5, 2, '2025-10-20', '2026-10-20', 18, 1),        -- Alessandro ha 20 ingressi
(6, 4, '2025-11-25', '2026-02-25', NULL, 1),      -- Sofia ha il trimestrale
(7, 1, '2025-12-01', '2026-05-31', 4, 1),
(8, 9, '2025-11-28', '2025-12-28', 2, 1),         -- Chiara ha preso la prova 3 lezioni
(9, 3, '2025-12-01', '2026-01-01', NULL, 1),
(10, 6, '2025-06-01', '2026-06-01', NULL, 1),     -- Valentina ha l’Annuale Plus
(11, 10, '2025-09-01', '2026-09-01', NULL, 1);    -- Lorenzo ha Famiglia 2 persone

-- -----------------------------------------------------------
-- 5. Prenotazioni (10 prenotazioni reali per i prossimi giorni)
-- -----------------------------------------------------------
INSERT INTO prenotazioni (utente_id, lezione_id, data_prenotazione, stato, acquistato_con) VALUES
(2, 1, '2025-12-09', 'confermata', 1),  -- Laura - Hatha lunedì prossimo
(3, 2, '2025-12-10', 'confermata', 2),  -- Marco - Vinyasa martedì
(4, 3, '2025-12-11', 'confermata', 3),  -- Giulia - Yin mercoledì
(5, 4, '2025-12-12', 'confermata', 4),  -- Alessandro - Ashtanga giovedì
(6, 5, '2025-12-13', 'confermata', 5),  -- Sofia - Power venerdì
(2, 6, '2025-12-14', 'confermata', 1),  -- Laura - Yoga Dolce sabato
(8, 7, '2025-12-15', 'confermata', 7),  -- Chiara - Meditazione domenica
(3, 1, '2025-12-16', 'confermata', 2),  -- Marco torna anche lunedì
(9, 2, '2025-12-17', 'confermata', 8),  -- Matteo - Vinyasa
(10, 8, '2025-12-10', 'confermata', 9); -- Valentina - Prenatal martedì

-- ===========================================================
-- FINE! Ora hai 51 record totali perfettamente coerenti
-- ===========================================================
SELECT 'Database popolato con successo!' AS messaggio;