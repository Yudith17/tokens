<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SIEMPRE redirigir al login si no está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Solo si está autenticado, cargar el controlador de hoteles
require_once '../src/controller/HotelController.php';
$controller = new HotelController();
$controller->index();
?>