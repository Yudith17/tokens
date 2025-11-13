<?php
require_once __DIR__ . '/../models/ClientApi.php';
require_once __DIR__ . '/../models/CountRequest.php';
require_once __DIR__ . '/../models/TokenApi.php';

class ClientapiController {
    private $clientApiModel;

    public function __construct() {
        $this->clientApiModel = new ClientApi();
    }

    // ==================== ACCIÓN VER ====================

    public function index() {

        $clients = $this->clientApiModel->getAll();
        require __DIR__ . '/../views/client_api/index.php';
    }

    public function view() {
        // TEMPORAL: Comentado para desarrollo  
        // if (!isset($_SESSION['user_id'])) {
        //     header('Location: index.php?controller=auth&action=login');
        //     exit;
        // }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $client = $this->clientApiModel->find($id);
            if (!$client) {
                $_SESSION['error'] = 'Cliente no encontrado';
                header('Location: index.php?controller=clientapi&action=index');
                exit;
            }
            require __DIR__ . '/../views/client_api/view.php';
        } else {
            $_SESSION['error'] = 'ID no especificado';
            header('Location: index.php?controller=clientapi&action=index');
            exit;
        }
    }

    // ==================== ACCIÓN BUSCAR ====================

    public function search() {
        // TEMPORAL: Comentado para desarrollo
        // if (!isset($_SESSION['user_id'])) {
        //     header('Location: index.php?controller=auth&action=login');
        //     exit;
        // }

        $clientId = $_GET['id'] ?? null;
        
        if (!$clientId) {
            $_SESSION['error'] = 'ID de cliente no especificado';
            header('Location: index.php?controller=clientapi&action=index');
            exit;
        }

        try {
            $client = $this->clientApiModel->find($clientId);
            
            if (!$client) {
                $_SESSION['error'] = 'Cliente no encontrado';
                header('Location: index.php?controller=clientapi&action=index');
                exit;
            }

            $countRequestModel = new CountRequest();
            $stats = $countRequestModel->getStatsByClient($clientId);
            $requests = $countRequestModel->getByClientId($clientId);
            $tokenApiModel = new TokenApi();
            $tokens = $tokenApiModel->getByClientId($clientId);

            require_once __DIR__ . '/../views/client_api/search.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar los datos: ' . $e->getMessage();
            header('Location: index.php?controller=clientapi&action=index');
            exit;
        }
    }
  /**
     * Mostrar formulario de creación (GET)
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ruc = $_POST['ruc'] ?? '';
            $razon_social = $_POST['razon_social'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $estado = $_POST['estado'] ?? 'activo';
    
            // Llamar al modelo para crear el cliente
            $success = $this->clientApiModel->create($ruc, $razon_social, $correo, $telefono, $estado);
            
            if ($success) {
                header("Location: index.php?controller=clientapi&action=index");
                exit;
            } else {
                // Manejar error - puedes mostrar un mensaje
                echo "Error al crear el cliente";
            }
        }
    }
    public function store() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?controller=auth&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'ruc' => trim($_POST['ruc']),
                    'razon_social' => trim($_POST['razon_social']),
                    'telefono' => trim($_POST['telefono']),
                    'correo' => trim($_POST['correo']),
                    'estado' => $_POST['estado']
                ];

                // Validaciones básicas
                if (empty($data['ruc']) || strlen($data['ruc']) !== 11) {
                    throw new Exception("El RUC debe tener 11 dígitos");
                }

                if (empty($data['razon_social'])) {
                    throw new Exception("La razón social es obligatoria");
                }

                if (empty($data['correo']) || !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("El correo electrónico no es válido");
                }

                // Llamar al método create del modelo
                $id = $this->clientApiModel->create($data);
                
                $_SESSION['success'] = "Cliente API creado exitosamente (ID: $id)";
                header('Location: index.php?controller=clientapi&action=index');
                exit;

            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?controller=clientapi&action=create');
                exit;
            }
        } else {
            header('Location: index.php?controller=clientapi&action=create');
            exit;
        }
    }


    // ==================== NUEVA ACCIÓN PARA CLIENTE_API ====================
    
    public function cliente_api() {
        // Esta acción carga directamente el archivo cliente_api.php sin requerir autenticación
        // y sin pasar por las vistas del sistema
        
        // Incluir directamente el archivo cliente_api.php que está en la raíz
        include __DIR__ . '/../../cliente_api.php';
        exit; // Importante: salir para que no cargue el layout del sistema
    }
    // ==================== ACCIÓN PÚBLICA CLIENTE_API ====================

}