<?php
class AuthController {
    private $userModel;
    private $tokenModel;

    public function __construct($db) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User($db);
        $this->tokenModel = new Token($db);
    }

    public function login() {
        if($_POST) {
            $this->userModel->username = $_POST['username'];
            $this->userModel->password = $_POST['password'];

            if($this->userModel->login()) {
                $_SESSION['user_id'] = $this->userModel->id;
                $_SESSION['username'] = $this->userModel->username;
                $_SESSION['nombre_completo'] = $this->userModel->nombre_completo;
                
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos";
                include_once 'views/login.php';
            }
        } else {
            include_once 'views/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function generateApiToken() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }

        $token = $this->tokenModel->generateToken($_SESSION['user_id']);
        if($token) {
            $_SESSION['success'] = "Token generado exitosamente: " . $token;
        } else {
            $_SESSION['error'] = "Error al generar el token";
        }
        
        header("Location: index.php?action=dashboard");
        exit();
    }

    public function revokeToken() {
        if(!isset($_SESSION['user_id']) || !isset($_POST['token_id'])) {
            header("Location: index.php");
            exit();
        }

        if($this->tokenModel->revokeToken($_POST['token_id'], $_SESSION['user_id'])) {
            $_SESSION['success'] = "Token revocado exitosamente";
        } else {
            $_SESSION['error'] = "Error al revocar el token";
        }
        
        header("Location: index.php?action=dashboard");
        exit();
    }
}
?>