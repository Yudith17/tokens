<?php
require_once 'BaseController.php';
require_once '../src/model/Hotel.php';
require_once '../utils/validar_tokens.php';

class HotelController extends BaseController {
    private $hotelModel;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->hotelModel = new Hotel();
    }
    
    // Método render para cargar vistas
    protected function render($view, $data = []) {
        // Extraer los datos para que estén como variables en la vista
        extract($data);
        
        // Incluir el header
        require_once '../views/layouts/header.php';
        
        // Incluir la vista específica
        require_once '../views/' . $view . '.php';
    }
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login.php');
            return;
        }
        
        // Redirigir al método buscar
        $this->buscar();
    }
    
    public function buscar() {
        $token = $_POST['token'] ?? "tok_4aaaf5a2dc22b87d7c70efed5324def05be51ff8626688825f6a530dccdaec74";
        $nombre = $_POST['nombre'] ?? '';
        $categoria = $_POST['categoria'] ?? 'todas';
        
        $error = null;
        $hoteles = [];
        $total = 0;
        
        // Solo validar el token si se envió el formulario (hay búsqueda)
        if (!empty($nombre)) {
            $validacion = validarToken($token);
            
            if (!$validacion['success']) {
                $error = $validacion['message'];
            } else {
                // Si el token es válido, proceder con la búsqueda
                try {
                    // Usar el modelo Hotel para buscar
                    $hoteles = $this->hotelModel->buscarHoteles($nombre, $categoria);
                    $total = count($hoteles);
                    
                } catch (Exception $e) {
                    $error = "Error en la búsqueda: " . $e->getMessage();
                }
            }
        }
        
        // Pasar datos a la vista (siempre mostrar el formulario)
        $this->render('hotel/buscar', [
            'hoteles' => $hoteles,
            'termino_busqueda' => $nombre,
            'total' => $total,
            'token' => $token,
            'categoria_seleccionada' => $categoria,
            'error' => $error
        ]);
    }
}
?>