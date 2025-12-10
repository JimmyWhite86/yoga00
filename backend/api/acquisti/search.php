<?php
    
    // Richiamo il file che contiene le funzioni che vengono ripetute nelle classi CRUD di ogni istanza
    require_once '../../utils/utils_scrud.php';
    
    // Includo la classe Acquisto.php
    require_once '../../classes/Acquisto.php.php';
    
    // Richiamo la funzione per connettermi al database
    $db = connessioneDatabase();
    
    // Leggo la keyword nella richiesta GET
    $keyword = isset($_GET["s"]) ? $_GET["s"] : "";
    
    // Creo un'istanza di acquisto
    $acquisto = new Acquisto($db);
    

    // TODO: Verificare se questo metodo serve in acquisti