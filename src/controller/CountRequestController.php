<?php
require_once __DIR__ . '/../models/CountRequest.php';
require_once __DIR__ . '/../models/TokenApi.php';

class CountrequestController {
    private $countRequestModel;
    private $tokenApiModel;

    public function __construct() {
        $this->countRequestModel = new CountRequest(Database::getInstance());
        $this->tokenApiModel = new TokenApi(Database::getInstance());
    }

    public function index() {
        $requests = $this->countRequestModel->getAll();
        $stats = $this->countRequestModel->getStats();
        require __DIR__ . '/../views/count_request/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos
                if (empty($_POST['Id_Token']) || empty($_POST['Tipo'])) {
                    $_SESSION['error'] = "Todos los campos son obligatorios";
                    header("Location: index.php?controller=countrequest&action=create");
                    exit;
                }

                $success = $this->countRequestModel->create(
                    $_POST['Id_Token'],
                    $_POST['Tipo']
                );

                if ($success) {
                    $_SESSION['success'] = "Solicitud registrada exitosamente";
                } else {
                    $_SESSION['error'] = "Error al registrar la solicitud";
                }

                header("Location: index.php?controller=countrequest&action=index");
                exit;

            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header("Location: index.php?controller=countrequest&action=create");
                exit;
            }
        }

        $tokens = $this->countRequestModel->getActiveTokens();
        require __DIR__ . '/../views/count_request/create.php';
    }

    public function view() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = "ID no especificado";
            header("Location: index.php?controller=countrequest&action=index");
            exit;
        }

        $request = $this->countRequestModel->find($id);
        if (!$request) {
            $_SESSION['error'] = "Solicitud no encontrada";
            header("Location: index.php?controller=countrequest&action=index");
            exit;
        }

        require __DIR__ . '/../views/count_request/view.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $success = $this->countRequestModel->delete($id);
            if ($success) {
                $_SESSION['success'] = "Solicitud eliminada exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar la solicitud";
            }
        }
        header("Location: index.php?controller=countrequest&action=index");
        exit;
    }
}
?>