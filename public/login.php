<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si YA está logueado, redirigir al index
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/controller/AuthController.php';
$controller = new AuthController();
$controller->login();
?>