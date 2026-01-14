<?php
    /**
     * FILE DI CONFIGURAZIONE CORS
     *
     *  https://stackoverflow.com/a/9866124
     *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
     *  origin.
     *
     *  In a production environment, you probably want to be more restrictive, but this gives you
     *  the general idea of what is involved.  For the nitty-gritty low-down, read:
     *
     *  - https://developer.mozilla.org/en/HTTP_access_control
     *  - https://fetch.spec.whatwg.org/#http-cors-protocol
     *
     *  -----------------------------------------------------------
     *
     *  CORS (Cross-Origin Resource Sharing)
     *
     *  Meccanismo di sicurezza dei browser che blocca richieste HTTP da un dominio diverso da quello che ha servito la pagina.
     *
     *  Esempio:
     *  Una pagina web caricata da "https://example.com" fa una richiesta AJAX a "https://api.example.org".
     *  In questo caso, il browser bloccherà la richiesta a meno che il server "api.example.org" non consenta esplicitamente le richieste cross-origin.
     *  Per consentire le richieste cross-origin, il server deve includere specifici header HTTP nelle risposte.
     *
     *  Questo codice implementa una versione "permissiva" di CORS, utile in sviluppo.
     *  In produzione, è consigliabile limitare gli origin consentiti per motivi di sicurezza.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/utils/cors.php
     * @package api
     *
     * @author Unknown
     */
    
    
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        
        exit(0);
    }
    
    // se si arriva qui, tutto bene



// TODO: Vedere appunti del 04.12.2025

/*

Slide 10_REST1 p 23

Possibile problema:
una pagina scaricataa dal sito www.cattivo.com potrebbe contenere un JS malizioso che si collega
ad un sito www.postaelettronica.com su cui l'utente è loggato.

Per evitare questo problema, i browser hanno inizialmente implementato un
sistema di protezione chiamato Same-Origin Policy (SOP):
Un js può accedere solo a dati che provengono dalla stessa origin da cui è stato scaricato.

La same-origin policy blocca "richieste incrociate" (cross-origin requests).
Il Js scaricato da cattivo.com non può accedere a risorse di postaelettronica.com
in quanto hanno origine diversa.

Ci sono però casi in cui, come le nostre REST API,  vogliamo permettere l'accesso cross-origin.
Per es il browser potrebbe prelevare le pagine da www.miosito.com e richiedere le api a api.miosito.com

Quindi i browser implementano CORS (Cross-Origin Resource Sharing):
MEccanismo che, richiedendo la collaborazione tra browser e web server con uno scambio di header che dimostrino il "consenso" del server alla condivisione,
permette ad un js di effettuare richieste a origine diversa da quella da cui è stato scaricato.

