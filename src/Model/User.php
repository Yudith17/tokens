<?php
require_once '../src/config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function authenticate($usuario, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verificar la contraseña hasheada
            if (password_verify($password, $user['password'])) {
                return $user;
            }
            // Para debugging - muestra qué contraseña se está usando
            error_log("Login attempt - User: $usuario, Password provided: $password");
        }
        return false;
    }
}
?>