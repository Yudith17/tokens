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
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login.php');
            return;
        }
        
        // Validar token API antes de mostrar la página
        $validacionToken = validarTokenAPI();
        if (!$validacionToken['valido']) {
            $this->renderView('../views/hotel/buscar.php', [
                'mensaje' => $validacionToken['mensaje'],
                'resultados' => []
            ]);
            return;
        }
        
        $this->renderView('../views/hotel/buscar.php');
    }
    
    public function buscar() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login.php');
            return;
        }
        
        $resultados = [];
        $destino = '';
        $mensaje = '';
        
        // Validar token API primero
        $validacionToken = validarTokenAPI();
        if (!$validacionToken['valido']) {
            $mensaje = $validacionToken['mensaje'];
        } else {
            // Solo procesar búsqueda si el token es válido
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $destino = $_POST['destino'] ?? '';
                $estrellas = $_POST['estrellas'] ?? null;
                
                if (!empty($destino)) {
                    // Buscar hoteles en la API incluyendo las estrellas
                    $resultados = $this->hotelModel->buscarHoteles($destino, $estrellas);
                    
                    if (empty($resultados)) {
                        $mensaje = "No se encontraron hoteles disponibles para los criterios seleccionados.";
                    }
                } else {
                    $mensaje = "Por favor, ingrese un nombre de hotel o destino.";
                }
            }
        }
        
        $this->renderView('../views/hotel/buscar.php', [
            'resultados' => $resultados,
            'destino' => $destino,
            'mensaje' => $mensaje
        ]);
    }
}
?>