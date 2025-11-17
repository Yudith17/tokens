<?php
function validarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        // Evitar redirección infinita
        $current_page = basename($_SERVER['PHP_SELF']);
        if ($current_page !== 'login.php') {
            header('Location: login.php');
            exit;
        }
        return false;
    }
    return true;
}
?>