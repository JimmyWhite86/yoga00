<?php
    // yoga00/backend/api/auth/login.php
    
    
    // Richiamo il file che contiene le funzioni condivise con le varie API
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Utente.php
    require_once '../../classes/Utente.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Creo oggetto utente
    $utente = new Utente($db);
    
    // Leggo i dati JSON dal body della richiesta HTTP
    $data = json_decode(file_get_contents("php://input"));
    
    // Richiamo la funzione che valida il JSON appena letto
    isJSONvalid($data);
    
    // Controllo se l'utente ha inserito i dati
    if (empty($data->email) && empty($data->password)) {
        http_response_code(400);    // Bad request
        echo json_encode(array(
            "messaggio" => "Email e password sono obbligatori"
        ));
    }
    
    
    // Valido l'email e imposto i dati
    try {
        // Tento di impostare l'email (può lanciare un'eccezione se non valida)
        $utente->setEmail($data->email);
        
        // Imposto la password in chiaro (per il login)
        $utente->setPassword_in_chiaro($data->password);
    } catch (InvalidArgumentException $e) {
        http_response_code(401); // non autorizzato
        echo json_encode(array(
            "messaggio" => "Credenziali non valide"
        ));
        // Non dico se è la password o la mail ad essere sbagliate per questioni di sicurezza
    }
    
    // Tento il login
    if ($utente->login()) {     // Login riuscito
        
        // Imposto i dati utente per la sessione
        impostaDatiUtenteInSessione(
            $utente->getId(),
            $utente->getNomeUtente(),
            $utente->isAdmin(),
            $utente->getEmail()
        );
        
        // Rispondo con i dati dell'utente
        http_response_code(200);    //ok
        echo json_encode(array(
            "messaggio" => "Login effettuato con successo",
            "utente" => array(
                "id" => $utente->getId(),
                "nome_utente" => $utente->getNomeUtente(),
                "admin" => $utente->isAdmin(),
                "email" => $utente->getEmail()
            )
        ));
    } else {        // Login fallito
        http_response_code(401);    // Unauthorized
        echo json_encode(array(
            "messaggio" => "Credenziali non valide"
        ));
    }
    