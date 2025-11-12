<?php
session_start();

// Definir la ruta base
define('BASE_PATH', __DIR__);

// Cargar archivos necesarios
require_once BASE_PATH . '/src/config/database.php';
require_once BASE_PATH . '/src/Model/TokenApi.php';
require_once BASE_PATH . '/src/Model/User.php';
require_once BASE_PATH . '/src/controller/TokenApiController.php';
require_once BASE_PATH . '/src/controller/AuthController.php';

// Instanciar controladores
$tokenController = new TokenApiController();
$authController = new AuthController();

// Determinar la acción a realizar
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Ejecutar la acción correspondiente
switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'processLogin':
        $authController->processLogin();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'create':
        $tokenController->create();
        break;
    case 'generate':
        $tokenController->generate();
        break;
    case 'toggleStatus':
        $tokenController->toggleStatus();
        break;
    case 'validate': // NUEVA ACCIÓN
        $tokenController->validate();
        break;
    case 'processValidate': // NUEVA ACCIÓN
        $tokenController->processValidate();
        break;
    case 'view':
        $tokenController->view();
        break;
    case 'index':
    default:
        $tokenController->index();
        break;
}
?>