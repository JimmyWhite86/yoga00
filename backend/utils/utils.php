<?php
    
    // idIsValid
// Prende l'id dalla richiesta GET e lo valida
function idIsValid(string $id = 'id'): int
{
    if (!isset($_GET[$id])) {
        http_response_code(400);
        echo json_encode(array("messaggio" => "ID non presente nella richiesta"));
        exit;
    }
    
    if (!is_numeric($_GET[$id]) || $_GET[$id] <= 0) {
       http_response_code(400);
       echo json_encode(array("messaggio" => "ID non valido"));
    }
    
    return (int)$_GET[$id]; // Cast esplicito
}