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

// Validar token API
require_once '../utils/validar_tokens.php';
$validacionToken = validarTokenAPI();

if (!$validacionToken['valido']) {
    // Si el token no es válido, ir directamente a buscar_hoteles.php con mensaje de error
    header('Location: buscar_hoteles.php?error=token');
    exit;
}

// Si el token es válido, redirigir a buscar_hoteles.php
header('Location: buscar_hoteles.php');
exit;
?>