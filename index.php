<?php
session_start();
define('BASE_PATH', dirname(__FILE__));

require_once 'src/config/database.php';
require_once 'src/controller/TokenApiController.php';

$controller = new TokenApiController();
$action = $_GET['action'] ?? 'index';

switch($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'generate':
        $controller->generate();
        break;
    case 'view':
        $controller->view();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'toggleStatus':
        $controller->toggleStatus();
        break;
    case 'validate':
        $controller->validate();
        break;
    case 'processValidate':
        $controller->processValidate();
        break;
    case 'cliente_api':
        $controller->clienteApi();
        break;
    case 'buscar_hoteles':
        $controller->buscarHoteles();
        break;
    default:
        $controller->index();
        break;
}
?>