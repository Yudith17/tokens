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

// Redirigir directamente a buscar_hoteles.php
// La validación del token se hará en buscar_hoteles.php cuando se realice una búsqueda
header('Location: buscar_hoteles.php');
exit;
?>