<?php
/**
 * Script per reimpostare la password di un utente (con argomenti)
 * Uso da terminale: php reset_password_args.php email@example.com nuova_password
 */

require_once __DIR__ . '/database/Database.php';
require_once __DIR__ . '/classes/Utente.php';

// Verifico che lo script sia eseguito da CLI
if (php_sapi_name() !== 'cli') {
    die("Questo script può essere eseguito solo da terminale\n");
}

// Verifico che ci siano gli argomenti necessari
if ($argc < 3) {
    echo "Uso: php reset_password_args.php <email> <nuova_password>\n";
    echo "Esempio: php reset_password_args.php mario@test.it Password123\n";
    exit(1);
}

$email = $argv[1];
$nuova_password = $argv[2];

// Validazione base
if (strlen($nuova_password) < 6) {
    die("Errore: la password deve contenere almeno 6 caratteri\n");
}

try {
    // Connessione al database
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        die("Errore: impossibile connettersi al database\n");
    }
    
    // Cerco l'utente
    $query = "SELECT utente_id, nome_utente, email FROM utenti WHERE email = :email LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $utente_trovato = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$utente_trovato) {
        die("Errore: nessun utente trovato con l'email '$email'\n");
    }
    
    // Aggiorno la password
    $query_update = "UPDATE utenti SET password = :password WHERE utente_id = :utente_id";
    $stmt_update = $db->prepare($query_update);
    $password_hash = password_hash($nuova_password, PASSWORD_DEFAULT);
    $stmt_update->bindParam(':password', $password_hash);
    $stmt_update->bindParam(':utente_id', $utente_trovato['utente_id']);
    
    if ($stmt_update->execute()) {
        echo "✓ Password aggiornata con successo per '{$utente_trovato['nome_utente']}' ({$email})\n";
    } else {
        echo "✗ Errore durante l'aggiornamento\n";
    }
    
} catch (Exception $e) {
    die("Errore: " . $e->getMessage() . "\n");
}
