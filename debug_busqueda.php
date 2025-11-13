<?php
// debug_busqueda.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'debug' => 'Archivo ejecutándose correctamente',
    'get_params' => $_GET,
    'server' => [
        'REQUEST_URI' => $_SERVER['REQUEST_URI'],
        'QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? ''
    ]
]);
?>