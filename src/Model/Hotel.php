<?php
require_once '../src/config/database.php';


class Hotel {
    private $conn;
    private $table_name = "hoteles";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Buscar hoteles por nombre y categoría
     */
    public function buscarHoteles($nombre, $categoria = 'todas') {
        try {
            // Construir consulta base
            $query = "SELECT * FROM " . $this->table_name . " WHERE nombre LIKE ?";
            $params = ["%" . $nombre . "%"];
            $types = "s";
            
            // Agregar filtro de categoría si no es "todas"
            if (!empty($categoria) && $categoria != 'todas') {
                $query .= " AND categoria = ?";
                $params[] = $categoria;
                $types .= "s";
            }
            
            // Preparar y ejecutar consulta
            $stmt = $this->conn->prepare($query);
            
            if ($stmt === false) {
                throw new Exception("Error preparando la consulta: " . $this->conn->error);
            }
            
            // Bind parameters
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $hoteles = [];
            
            while ($row = $result->fetch_assoc()) {
                $hoteles[] = $row;
            }
            
            return $hoteles;
            
        } catch (Exception $e) {
            // En caso de error, devolver array vacío o datos de ejemplo
            error_log("Error en búsqueda de hoteles: " . $e->getMessage());
            return $this->getHotelesEjemplo($nombre, $categoria);
        }
    }
    
    /**
     * Datos de ejemplo para desarrollo
     */
    private function getHotelesEjemplo($termino, $categoria) {
        $hoteles_base = [
            [
                'id' => 1,
                'nombre' => 'Hotel Valencia',
                'categoria' => '4',
                'precio' => '120',
                'ubicacion' => 'Valencia, España',
                'servicios' => 'WiFi, Piscina, Spa',
                'descripcion' => 'Un hotel moderno en el centro de Valencia'
            ],
            [
                'id' => 2,
                'nombre' => 'Valencia Beach Resort',
                'categoria' => '5',
                'precio' => '200',
                'ubicacion' => 'Playa de Valencia, España',
                'servicios' => 'WiFi, Piscina, Gym, Restaurante',
                'descripcion' => 'Resort de lujo frente al mar'
            ],
            [
                'id' => 3,
                'nombre' => 'Hotel Valencia Center',
                'categoria' => '3',
                'precio' => '80',
                'ubicacion' => 'Centro de Valencia, España',
                'servicios' => 'WiFi, Desayuno incluido',
                'descripcion' => 'Hotel económico en el centro de la ciudad'
            ]
        ];
        
        // Filtrar por término de búsqueda
        $resultados = array_filter($hoteles_base, function($hotel) use ($termino) {
            return stripos($hotel['nombre'], $termino) !== false;
        });
        
        // Filtrar por categoría si no es "todas"
        if ($categoria !== 'todas') {
            $resultados = array_filter($resultados, function($hotel) use ($categoria) {
                return $hotel['categoria'] === $categoria;
            });
        }
        
        return array_values($resultados);
    }
}
?>