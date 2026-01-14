<?php
    
    /**
     * API per autenticazione dell'utente
     *
     * Questo endpoint gestisce l'autenticazione deggli utenti tramite credenziali.
     *
     * Se le credenziali sono corrette:
     * - viene inizializzata la sessione
     * - vengono impostati i dati dell'utente nella sessione
     *
     * Se le credenziali sono errate:
     * - viene restituito un messaggio di errore
     * - HTTP status code 401 Unauthorized
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/auth/login.php
     * @package api.auth
     *
     * @author Bianchi Andrea
     * @version 1.0
     */
    
    
    // Richiamo il file che contiene le funzioni condivise con le varie API
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Creo oggetto utente
    // Passo la connessione al database al costruttore
    $utente = new Utente($db);
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che valida il JSON appena letto
    isJSONvalid($data);
    
    
    // Controllo se l'utente ha inserito i dati
    if (empty($data->email) || empty($data->password)) {
        http_response_code(400);    // Bad request
        echo json_encode(array(
            "messaggio" => "Email e password sono obbligatori"
        ));
        exit;
    }
    
    
    // Valido l'email e imposto i dati
    try {
        // Tento di impostare l'email (può lanciare un'eccezione se non valida)
        $utente->setEmail($data->email);
        
        // Imposto la password in chiaro (per il login)
        $utente->setPassword_in_chiaro($data->password);
    } catch (InvalidArgumentException $e) {
        http_response_code(400); // bad request
        echo json_encode(array(
            "messaggio" => "Dati non validi"
        ));
        exit;
    }
    
    
    // Tento il login
    if ($utente->login()) {     // Login riuscito
        
        // Imposto i dati utente per la sessione
        impostaDatiUtenteInSessione(
            $utente->getId(),
            $utente->getNomeUtente(),
            $utente->isAdmin(),
            $utente->getEmail(),
            $utente->getDataNascita()
        );
        
        // Rispondo con i dati dell'utente
        http_response_code(200);    //ok
        echo json_encode(array(
            "messaggio" => "Login effettuato con successo",
            "utente" => array(
                "utente_id" => $utente->getId(),
                "nome_utente" => $utente->getNomeUtente(),
                "admin" => $utente->isAdmin(),
                "email" => $utente->getEmail(),
                "data_nascita" => $utente->getDataNascita()
            )
        ));
    } else {        // Login fallito
        http_response_code(401);    // Unauthorized
        echo json_encode(array(
            "messaggio" => "Credenziali non valide"
        ));
        // Non dico se è la password o l'email errata per motivi di sicurezza
    }
    