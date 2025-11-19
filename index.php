<?php
// session_start() solo aquí, al inicio del archivo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir configuraciones y modelos
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Token.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ApiController.php';
require_once 'controllers/HotelController.php'; // ✅ AGREGAR ESTA LÍNEA

// Inicializar base de datos
$database = new Database();
$db = $database->getConnection();

// Determinar la acción
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Enrutamiento
switch($action) {
    case 'login':
        $authController = new AuthController($db);
        $authController->login();
        break;
        
    case 'logout':
        $authController = new AuthController($db);
        $authController->logout();
        break;
        
    case 'dashboard':
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        $tokenModel = new Token($db);
        $tokens = $tokenModel->getUserTokens($_SESSION['user_id']);
        include_once 'views/dashboard.php';
        break;
        
    case 'generate_token':
        $authController = new AuthController($db);
        $authController->generateApiToken();
        break;
        
    case 'revoke_token':
        $authController = new AuthController($db);
        $authController->revokeToken();
        break;
        
    case 'search_hotels': // ✅ NUEVA ACCIÓN
        $hotelController = new HotelController($db);
        $hotelController->search();
        break;
        
    case 'api_hotels': // ✅ NUEVA ACCIÓN PARA API
        $hotelController = new HotelController($db);
        $hotelController->apiSearch();
        break;
        
    case 'api_validate':
        $apiController = new ApiController($db);
        $apiController->validateToken();
        break;
        
    case 'api_user':
        $apiController = new ApiController($db);
        $apiController->getUserInfo();
        break;
        
    // ✅ NUEVA RUTA PARA VALIDAR TOKEN SISHO (ANTES DEL DEFAULT)
    case 'verificar-token-sisho':
        $apiController = new ApiController($db);
        $apiController->verificarTokenSISHO();
        break;
        
    // ✅ SOLO UN DEFAULT (ELIMINA EL ANTERIOR)
    default:
        // Si el usuario está logueado, ir al dashboard, sino al login
        if(isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
        } else {
            header("Location: index.php?action=login");
        }
        exit();
}
?>