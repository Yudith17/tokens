<?php
// Incluir los modelos necesarios
require_once BASE_PATH . '/src/Model/TokenApi.php';
require_once BASE_PATH . '/src/Model/User.php';
// require_once BASE_PATH . '/src/Model/Hotel.php'; // Descomenta si tienes este modelo

class TokenApiController {
    private $tokenModel;
    private $userModel;
    private $hotelModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->tokenModel = new TokenApi($db);
        $this->userModel = new User($db);
        //$this->hotelModel = new Hotel($db); // Nuevo modelo de hoteles
    }

    // Página principal - Lista de tokens
    public function index() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $status = isset($_GET['status']) ? $_GET['status'] : 'all';
        
        if ($status == 'active') {
            $stmt = $this->tokenModel->readActive();
        } elseif ($status == 'inactive') {
            $stmt = $this->tokenModel->readInactive();
        } else {
            $stmt = $this->tokenModel->readAll();
        }
        
        include BASE_PATH . '/views/token_api/index.php';
    }

    // Generar nuevo token
    public function generate() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_POST) {
            $name = $_POST['name'];
            $token = $this->tokenModel->generateToken();
            
            $this->tokenModel->user_id = $_SESSION['user_id'];
            $this->tokenModel->token = $token;
            $this->tokenModel->name = $name;
            $this->tokenModel->created_at = date('Y-m-d H:i:s');
            $this->tokenModel->expires_at = date('Y-m-d H:i:s', strtotime('+1 year'));
            $this->tokenModel->is_active = 1;
            
            if ($this->tokenModel->create()) {
                $_SESSION['message'] = "Token generado exitosamente para: " . $name;
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Error al generar el token";
            }
        }
        
        include BASE_PATH . '/views/token_api/create.php';
    }

    // Cambiar estado del token (activar/desactivar)
    public function toggleStatus() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status'] == 'activate' ? 1 : 0;
            
            if ($this->tokenModel->updateStatus($id, $status)) {
                $_SESSION['message'] = "Token " . ($status ? "activado" : "desactivado") . " exitosamente";
            } else {
                $_SESSION['error'] = "Error al cambiar el estado del token";
            }
        }
        
        header("Location: index.php");
        exit();
    }

    // Ver token individual
    public function view() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $token = $this->tokenModel->readOne($id);
            
            if ($token) {
                include BASE_PATH . '/views/token_api/view.php';
            } else {
                $_SESSION['error'] = "Token no encontrado";
                header("Location: index.php");
                exit();
            }
        } else {
            header("Location: index.php");
            exit();
        }
    }

    // Crear token (formulario)
    public function create() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        include BASE_PATH . '/views/token_api/create.php';
    }

    public function validate() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        include BASE_PATH . '/views/token_api/validar_token.php';
    }
    // Procesar validación de token
    public function processValidate() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    
        if ($_POST) {
            $token = trim($_POST['token']);
            
            if (empty($token)) {
                $_SESSION['error'] = "Por favor ingresa un token para validar";
                header("Location: index.php?action=validate");
                exit();
            }
    
            // Usar el método del modelo para validar token
            $tokenData = $this->tokenModel->validateToken($token);
    
            if ($tokenData) {
                // Verificar si el token está activo
                if (!$tokenData['is_active']) {
                    $_SESSION['validation_result'] = [
                        'valid' => false,
                        'message' => 'Token inactivo',
                        'token_data' => $tokenData,
                        'hotel_info' => null
                    ];
                } 
                // Verificar si el token ha expirado
                elseif (strtotime($tokenData['expires_at']) < time()) {
                    $_SESSION['validation_result'] = [
                        'valid' => false,
                        'message' => 'Token expirado',
                        'token_data' => $tokenData,
                        'hotel_info' => null
                    ];
                }
                else {
                    $_SESSION['validation_result'] = [
                        'valid' => true,
                        'message' => 'Token válido',
                        'token_data' => $tokenData,
                        'hotel_info' => null
                    ];
                }
            } else {
                $_SESSION['validation_result'] = [
                    'valid' => false,
                    'message' => 'Token no encontrado',
                    'token_data' => null,
                    'hotel_info' => null
                ];
            }
            
            header("Location: index.php?action=validate");
            exit();
        }
        
        header("Location: index.php?action=validate");
        exit();
    }

    // Métodos adicionales para las otras vistas

    // Cliente API - Interfaz para clientes
    public function clienteApi() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        include BASE_PATH . '/views/token_api/cliente_api.php';
    }

    // Buscar hoteles
    public function buscarHoteles() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $hoteles = [];
        $searchTerm = '';
        
        if ($_POST && isset($_POST['search'])) {
            $searchTerm = trim($_POST['search']);
            
            if (!empty($searchTerm)) {
                // Buscar hoteles en la base de datos
                //$hoteles = $this->hotelModel->search($searchTerm);
            }
        }
        
        include BASE_PATH . '/views/token_api/buscar_hoteles.php';
    }

    // Eliminar token
    public function delete() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            if ($this->tokenModel->delete($id)) {
                $_SESSION['message'] = "Token eliminado exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar el token";
            }
        }
        
        header("Location: index.php");
        exit();
    }

    // Editar token
    public function edit() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $token = $this->tokenModel->readOne($id);
            
            if (!$token) {
                $_SESSION['error'] = "Token no encontrado";
                header("Location: index.php");
                exit();
            }

            if ($_POST) {
                $name = $_POST['name'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                if ($this->tokenModel->update($id, $name, $is_active)) {
                    $_SESSION['message'] = "Token actualizado exitosamente";
                    header("Location: index.php?action=view&id=" . $id);
                    exit();
                } else {
                    $_SESSION['error'] = "Error al actualizar el token";
                }
            }
            
            include BASE_PATH . '/views/token_api/edit.php';
        } else {
            header("Location: index.php");
            exit();
        }
    }
}
?>