<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }
    
    public function login($username, $password) {
        $user = $this->userModel->validateUser($username, $password);
        
        if ($user) {
            return [
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ]
            ];
        }
        
        return ['success' => false, 'error' => 'Credenciales inválidas'];
    }
    
    public function getUser($userId) {
        return $this->userModel->getUserById($userId);
    }
}
?>