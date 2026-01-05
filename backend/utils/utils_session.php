<?php
    
    /**
     * File di utility per la gestione delle sessioni utente
     *
     * Contiene funzioni gestire sessioni, autenticazione e ruoli utente.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/utils/utils_session.php
     * @package backend\utils
     *
     * @author Bianchi Andrea
     * @version 1.0
     */
    

    /**
     * Avvia o riprende una sessione PHP
     *
     * @return void
     */
    function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {        // https://www.php.net/manual/en/function.session-status.php
            session_start();
        }
    }
    
    
    /**
     * Verifica se l'utente è loggato
     *
     * Controlla se esiste una sessione attiva con un utente loggato.
     *  1. Chiama startSession() per avviare o riprendere la sessione PHP.
     *  2. Controlla se esiste $_SESSION['utente_id'].
     *  3. Restituisce true se l'utente è loggato, altrimenti false.
     *
     * @return bool True se l'utente è loggato, false altrimenti.
     */
    function isLoggedIn(): bool
    {
        startSession();
        return isset($_SESSION['utente_id']);
    }
    
    
    /**
     * Verifica se l'utente loggato è un amministratore
     *
     * @return bool True se l'utente è admin, false altrimenti.
     */
    function isAdmin(): bool {
        startSession();
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }
    
    
    /**
     * Setta i dati dell'utente in sessione
     *
     *Salva i dati dell'utente nella variabile di sessione $_SESSION, dopo il login.
     *
     * @param int $utente_id                = ID univoco dell'utente.
     * @param string $nome_utente    = Nome utente dell'utente.
     * @param bool $admin                  = Indica se l'utente ha privilegi di amministratore.
     * @param string $email                 = Indirizzo email dell'utente.
     * @param string $data_nascita     = Data di nascita dell'utente.
     *
     * @return void
     */
    function impostaDatiUtenteInSessione($utente_id, $nome_utente, $admin, $email, $data_nascita ): void
    {
        startSession();
        $_SESSION['utente_id'] = $utente_id;
        $_SESSION['nome_utente'] = $nome_utente;
        $_SESSION['admin'] = $admin;
        $_SESSION['email'] = $email;
        $_SESSION['data_nascita'] = $data_nascita;
        $_SESSION['ora_login'] = time();                            // Memorizzo l'ora del login
    }
    
    
    /**
     * Legge i dati dell'utente dalla sessione
     *
     * Recupera i dati dell'utente dalla variabile di sessione $_SESSION.
     * Se l'utente non è loggato, restituisce null.
     *
     * @return array|null Array associativo con i dati dell'utente se loggato, null altrimenti.
     */
    function leggiDatiUtenteInSessione(): array|null
    {
        startSession();
        if (isLoggedIn()) {
            return [
                'utente_id' => $_SESSION['utente_id'],
                'nome_utente' => $_SESSION['nome_utente'],
                'admin' => $_SESSION['admin'],
                'email' => $_SESSION['email'],
                'ora_login' => $_SESSION['ora_login'],
                'data_nascita' => $_SESSION['data_nascita']
            ];
        }
        return null;
    }
    
    
    /**
     * Distrugge la sessione utente
     *
     * Esegue il logout dell'utente
     *
     * @return void
     */
    function distruggiSessione(): void
    {
        startSession();
        session_unset();
        session_destroy();
    }
    
    
    /**
     * Funzione per pagine che richiedono il login dell'utente
     *
     * Se l'utente non è loggato, restituisce un errore 401 Unauthorized e termina l'esecuzione.
     *
     * @return void
     */
    function login_necessario()
    {
        if(!isLoggedIn()) {
            http_response_code(401); // NOn autorizzato
            echo json_encode(array(
                "messaggio" => "Accesso negato. Devi essere loggato per accedere a questa pagina"
            ));
            exit;
        }
    }
    
    
    /**
     * Funzione per pagine che richiedono privilegi di amministratore
     *
     * Se l'utente non è loggato o non è admin, restituisce un errore 403 Forbidden e termina l'esecuzione.
     *
     * @return void
     */
    function admin_necessario()
    {
        login_necessario();
        if (!isAdmin()) {
            http_response_code(403); // Forbidden
            echo json_encode(array(
                "messaggio" => "Accesso negato. Devi essere admin per accedere a questa pagina"
            ));
            exit;
        }
    }