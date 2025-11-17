<?php
require_once 'BaseController.php';
require_once '../src/model/User.php';

class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
    }
    
    public function login() {
        // REMOVER esta verificación para permitir el login siempre
        // if (isset($_SESSION['user_id'])) {
        //     $this->redirect('index.php');
        //     return;
        // }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->authenticate($usuario, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['usuario'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['token'] = bin2hex(random_bytes(32));
                
                $this->redirect('index.php');
                return;
            } else {
                $error = "Credenciales incorrectas";
            }
        }
        
        $this->renderView('../views/auth/login.php', ['error' => $error ?? null]);
    }
    
    public function logout() {
        // Destruir completamente la sesión
        session_destroy();
        // Forzar limpieza de variables de sesión
        $_SESSION = [];
        // Redirigir al login
        $this->redirect('login.php');
    }
}
?>