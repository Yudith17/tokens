<?php
require_once '/src/model/TokenApi.php';
require_once 'model/User.php';

class TokenApiController {
    private $tokenModel;
    private $userModel;
    
    public function __construct($pdo) {
        $this->tokenModel = new TokenApi($pdo);
        $this->userModel = new User($pdo);
    }
    
    public function generarToken($userId, $name = '') {
        // Verificar que el usuario existe
        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            return ['success' => false, 'error' => 'Usuario no encontrado'];
        }
        
        return $this->tokenModel->generarToken($userId, $name);
    }
    
    public function validarToken($token) {
        return $this->tokenModel->validarToken($token);
    }
    
    public function listarTokens($userId) {
        return $this->tokenModel->getTokenByUserId($userId);
    }
    
    public function desactivarToken($tokenId) {
        return $this->tokenModel->desactivarToken($tokenId);
    }
}
?>