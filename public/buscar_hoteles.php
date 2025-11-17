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
$controller->buscar();
?>