<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../src/controller/HotelController.php';

$controller = new HotelController();

// Verificar si hay error de token en la URL
if (isset($_GET['error']) && $_GET['error'] === 'token') {
    require_once '../utils/validar_tokens.php';
    $validacionToken = validarTokenAPI();
    
    if (!$validacionToken['valido']) {
        // Mostrar página con mensaje de error de token
        $controller->renderView('../views/hotel/buscar.php', [
            'mensaje' => $validacionToken['mensaje'],
            'resultados' => []
        ]);
        exit;
    }
}

$controller->buscar();
?>