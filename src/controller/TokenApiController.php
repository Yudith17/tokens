<?php
class TokenApiController {
    private $tokenModel;
    private $userModel;
    private $hotelModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->tokenModel = new TokenApi($db);
        $this->userModel = new User($db);
        $this->hotelModel = new Hotel($db); // Nuevo modelo de hoteles
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
        include BASE_PATH . '/views/token_api/view.php';
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
    // Mostrar formulario de validación
    public function validate() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        include BASE_PATH . '/views/token_api/validate.php';
    }

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
    
            // Usar el nuevo método del modelo (SOLUCIÓN 3)
            $tokenData = $this->tokenModel->validateToken($token);
    
            if ($tokenData) {
                // Buscar información del hotel en la base de datos
                $hotelInfo = $this->hotelModel->getByName($tokenData['name']);
                
                // Verificar si el token está activo
                if (!$tokenData['is_active']) {
                    $_SESSION['validation_result'] = [
                        'valid' => false,
                        'message' => 'Token inactivo',
                        'token_data' => $tokenData,
                        'hotel_info' => $hotelInfo ? [
                            'id' => $this->hotelModel->id,
                            'name' => $this->hotelModel->name,
                            'category' => $this->hotelModel->category,
                            'description' => $this->hotelModel->description,
                            'address' => $this->hotelModel->address,
                            'district' => $this->hotelModel->district,
                            'province' => $this->hotelModel->province,
                            'department' => $this->hotelModel->department,
                            'phone' => $this->hotelModel->phone,
                            'email' => $this->hotelModel->email,
                            'website' => $this->hotelModel->website
                        ] : null
                    ];
                } 
                // Verificar si el token ha expirado
                elseif (strtotime($tokenData['expires_at']) < time()) {
                    $_SESSION['validation_result'] = [
                        'valid' => false,
                        'message' => 'Token expirado',
                        'token_data' => $tokenData,
                        'hotel_info' => $hotelInfo ? [
                            'id' => $this->hotelModel->id,
                            'name' => $this->hotelModel->name,
                            'category' => $this->hotelModel->category,
                            'description' => $this->hotelModel->description,
                            'address' => $this->hotelModel->address,
                            'district' => $this->hotelModel->district,
                            'province' => $this->hotelModel->province,
                            'department' => $this->hotelModel->department,
                            'phone' => $this->hotelModel->phone,
                            'email' => $this->hotelModel->email,
                            'website' => $this->hotelModel->website
                        ] : null
                    ];
                }
                else {
                    $_SESSION['validation_result'] = [
                        'valid' => true,
                        'message' => 'Token válido',
                        'token_data' => $tokenData,
                        'hotel_info' => $hotelInfo ? [
                            'id' => $this->hotelModel->id,
                            'name' => $this->hotelModel->name,
                            'category' => $this->hotelModel->category,
                            'description' => $this->hotelModel->description,
                            'address' => $this->hotelModel->address,
                            'district' => $this->hotelModel->district,
                            'province' => $this->hotelModel->province,
                            'department' => $this->hotelModel->department,
                            'phone' => $this->hotelModel->phone,
                            'email' => $this->hotelModel->email,
                            'website' => $this->hotelModel->website
                        ] : null
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

}
?>