<?php
    
    /**
     * API Search Acquisti
     *
     * Endpoint che permette di cercare acquisti in base a una keyword.
     *
     * TODO: La classe acquisti non è attualmente sviluppata in modo definitivo.
     *
     * @path /Applications/MAMP/htdocs/yoga00/backend/api/acquisti/search.php
     * @package api.acquisti
     *
     * @api
     * METHOD: GET
     *
     * @author Bianchi Andrea
     * @version 1.0.0
     */
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_api.php';
    
    // Includo la classe Acquisto.php
    require_once '../../classes/Acquisto.php.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo la keyword nella richiesta GET
    $keyword = isset($_GET["s"]) ? $_GET["s"] : "";
    
    // Creo un'istanza di acquisto
    $acquisto = new Acquisto($db);
    

    // TODO: Verificare se questo metodo serve in acquisti