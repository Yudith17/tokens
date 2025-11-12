<?php
class AuthController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new User($db);
    }

    // Mostrar formulario de login
    public function login() {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        
        include BASE_PATH . '/views/auth/login.php';
    }

    // Procesar login
    public function processLogin() {
        if ($_POST) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            if ($this->userModel->login($username, $password)) {
                $_SESSION['user_id'] = $this->userModel->id;
                $_SESSION['username'] = $this->userModel->username;
                $_SESSION['role'] = $this->userModel->role;
                
                $_SESSION['message'] = "Bienvenido, " . $username . "!";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Usuario o contraseña incorrectos";
                header("Location: index.php?action=login");
                exit();
            }
        }
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>