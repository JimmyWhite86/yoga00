<?php

    // FUNZIONI PER SESSIONE UTENTE
    
    // Avvia la sessione se non è già attiva
    function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {        // https://www.php.net/manual/en/function.session-status.php
            session_start();
        }
    }
    
    
    // Verifico se l'utente è loggato
    function isLoggedIn(): bool
    {
        startSession();
        return isset($_SESSION['utente_id']);
    }
    
    
    function isAdmin(): bool {
        startSession();
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }
    
    
    // Imposto i dati della sessione dopo il login
    function impostaDatiUtenteInSessione($utente_id, $nome_utente, $admin, $email ): void
    {
        startSession();
        $_SESSION['utente_id'] = $utente_id;
        $_SESSION['nome_utente'] = $nome_utente;
        $_SESSION['admin'] = $admin;
        $_SESSION['email'] = $email;
        $_SESSION['ora_login'] = time(); // Memorizzo l'ora del login
    }
    
    
    // Recupero i dati utente memorizzati in sessione
    function leggiDatiUtenteInSessione()
    {
        startSession();
        if (isLoggedIn()) {
            return [
                'utente_id' => $_SESSION['utente_id'],
                'nome_utente' => $_SESSION['nome_utente'],
                'admin' => $_SESSION['admin'],
                'email' => $_SESSION['email'],
                'ora_login' => $_SESSION['ora_login']
            ];
        }
        return null;
    }
    
    
    // Logout (distruggo la sessione)
    function distruggiSessione(): void
    {
        startSession();
        session_unset();
        session_destroy();
    }
    
    
    // Pagine che richiedono che l'utente sia loggato
    function login_necessario()
    {
        if(!isLoggedIn()) {
            http_response_code(401); // NOn autorizzato
            echo json_encode(array(
                "messaggio" => "Accesso negato. Devi essere loggato per accedere a questa pagina"
            ));
        }
    }
    
    
    // Pagine che richiedono privilegi da admin
    function admin_necessario()
    {
        login_necessario();
        if (!isAdmin()) {
            http_response_code(403); // Forbidden
            echo json_encode(array(
                "messaggio" => "Accesso negato. Devi essere admin per accedere a questa pagina"
            ));
        }
    }