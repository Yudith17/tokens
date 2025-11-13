<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Hotel.php';

class PublicApiController {
    private $hotelModel;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->hotelModel = new Hotel($db);
    }
    
    public function searchHotels($params) {
        $query = isset($params['q']) ? $params['q'] : '';
        $category = isset($params['category']) ? $params['category'] : '';
        $location = isset($params['location']) ? $params['location'] : '';
        
        // Si no hay filtros, devolver todos los hoteles
        if (empty($query) && empty($category) && empty($location)) {
            return $this->hotelModel->getAllHotels();
        }
        
        // Construir consulta con filtros
        $filters = [];
        if (!empty($query)) {
            $filters['q'] = $query;
        }
        if (!empty($category)) {
            $filters['category'] = $category;
        }
        if (!empty($location)) {
            $filters['location'] = $location;
        }
        
        return $this->hotelModel->searchHotels($filters);
    }
}
?>