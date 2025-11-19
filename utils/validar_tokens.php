<?php
// utils/validar_tokens.php

/**
 * Función para validar tokens de API
 */
function validarTokenAPI() {
    // Token válido y activo - CAMBIA ESTE VALOR PARA DESACTIVAR EL TOKEN
    $token_activo = "tok_4aaaf5a2dc22b87d7c70efed5324def05be51ff8626688825f6a530dccdaec74";
    
    // Obtener token del POST o GET
    $token = $_POST['token'] ?? $_GET['token'] ?? '';
    
    if (empty($token)) {
        return [
            'valido' => false,
            'mensaje' => 'Token no proporcionado. Por favor, inicie sesión nuevamente.'
        ];
    }
    
    if ($token !== $token_activo) {
        return [
            'valido' => false,
            'mensaje' => 'Token inválido o ha expirado. Por favor, inicie sesión nuevamente.'
        ];
    }
    
    return [
        'valido' => true,
        'mensaje' => 'Token válido'
    ];
}

/**
 * Función alternativa para validar token (compatible con el HotelController)
 */
function validarToken($token) {
    $validacion = validarTokenAPI();
    
    return [
        'success' => $validacion['valido'],
        'message' => $validacion['mensaje']
    ];
}
?>