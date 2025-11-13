<?php
require_once 'models/Hotel.php';
require_once 'models/TokenApi.php';

class HotelController {
    private $hotelModel;
    private $tokenModel;
    
    public function __construct($pdo) {
        $this->hotelModel = new Hotel($pdo);
        $this->tokenModel = new TokenApi($pdo);
    }
    
    public function buscarHoteles($filtros = [], $token = null) {
        // Validar token si se proporciona
        if ($token) {
            $tokenValido = $this->tokenModel->validarToken($token);
            if (!$tokenValido['success']) {
                return ['success' => false, 'error' => 'Token inválido: ' . $tokenValido['message']];
            }
        }
        
        $hoteles = $this->hotelModel->buscarHoteles($filtros);
        
        return [
            'success' => true,
            'hotels' => $hoteles,
            'total' => count($hoteles),
            'search_params' => $filtros,
            'token_validado' => $token !== null
        ];
    }
}
?>