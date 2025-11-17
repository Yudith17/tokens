<?php
require_once '../src/config/database.php';

class Hotel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function buscarHoteles($destino, $estrellas = null) {
        // Intentar conectar con la API SISHO
        $hoteles = $this->callAPISISHO($destino, $estrellas);
        
        // Si falla, usar datos basados en tu lista real
        if (empty($hoteles)) {
            $hoteles = $this->getHotelesReales($destino, $estrellas);
        }
        
        return $hoteles;
    }
    
    private function callAPISISHO($destino, $estrellas) {
        $api_url = "http://localhost/sisho/api/hoteles.php";
        
        $params = [
            'busqueda' => $destino,
            'categoria' => $estrellas
        ];
        
        $query_string = http_build_query($params);
        $full_url = $api_url . '?' . $query_string;
        
        error_log("Llamando a API SISHO: " . $full_url);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $data = json_decode($response, true);
            if ($data && $data['success']) {
                return $data['hoteles'];
            }
        }
        
        return [];
    }
    
    // Datos REALES basados en tu lista de hoteles
    private function getHotelesReales($destino, $estrellas) {
        $hotelesReales = [
            [
                'id' => 1,
                'nombre' => 'Hotel Valencia',
                'estrellas' => 3,
                'direccion' => 'Jiron Saenz Peña, HUANTA',
                'precio' => 120,
                'descripcion' => 'Amplias y cómodas habitaciones, la atención fue excelente',
                'contacto' => '914721345 - hotelvalencia@gmail.com',
                'servicios' => ['WiFi', 'Estacionamiento', 'Recepcion 24h']
            ],
            [
                'id' => 2,
                'nombre' => 'Hotel Su Majestad 2',
                'estrellas' => 2,
                'direccion' => 'Jr. Osvaldo N. Regal 411, HUANTA',
                'precio' => 80,
                'descripcion' => 'Su Majestad II es un hospedaje de 2 estrellas que ofrece comodidad',
                'contacto' => '984637463 - sumagestad2@gmail.com',
                'servicios' => ['WiFi', 'Recepcion']
            ],
            [
                'id' => 3,
                'nombre' => 'Moreno\'s',
                'estrellas' => 3,
                'direccion' => 'Calle San Ignacio 364, Laredo, Peru',
                'precio' => 95,
                'descripcion' => 'DEJA QUE EL GUSTÓ DECIDA. Ofrecemos la mejor experiencia',
                'contacto' => '989522700 - morenos.chicken@gmail.com',
                'servicios' => ['Restaurante', 'WiFi']
            ],
            [
                'id' => 4,
                'nombre' => 'Hotel Las Vegas',
                'estrellas' => 3,
                'direccion' => 'Av. Mariscal Castilla Nro 882, HUANTA',
                'precio' => 110,
                'descripcion' => 'Hotel con excelente ubicación en el centro de Huanta',
                'contacto' => '936252508 - hotelasvegashuanta@gmail.com',
                'servicios' => ['WiFi', 'Estacionamiento', 'Restaurante']
            ],
            [
                'id' => 5,
                'nombre' => 'Hostal Nina Quintana',
                'estrellas' => 3,
                'direccion' => 'Jr. Manuel Jesús Urbina 166, HUANTA',
                'precio' => 65,
                'descripcion' => 'Un lugar agradable, acogedor y discreto para que usted pase una estadía placentera',
                'contacto' => '910027208',
                'servicios' => ['WiFi', 'Habitaciones familiares']
            ],
            [
                'id' => 6,
                'nombre' => 'GRAN HOTEL IMPERIAL HUANTA',
                'estrellas' => 3,
                'direccion' => 'Jr. Miguel Uniiveros 257, HUANTA',
                'precio' => 130,
                'descripcion' => 'Muy cerca al centro y al mercado. Ubicación estratégica',
                'contacto' => '923056622 - granhotelimperial@gmail.com',
                'servicios' => ['WiFi', 'Estacionamiento', 'Restaurante', 'Recepcion 24h']
            ],
            [
                'id' => 8,
                'nombre' => 'Hotel Huanta Grande',
                'estrellas' => 3,
                'direccion' => 'Av. Libertad 123, HUANTA',
                'precio' => 150,
                'descripcion' => 'El mejor hotel de la ciudad con servicios premium y atención personalizada',
                'contacto' => '966123456 - huantagrande@hotel.com',
                'servicios' => ['WiFi', 'Piscina', 'Spa', 'Restaurante', 'Estacionamiento']
            ],
            [
                'id' => 9,
                'nombre' => 'Hostal San Francisco',
                'estrellas' => 3,
                'direccion' => 'Jr. San Martín 456, HUANTA',
                'precio' => 70,
                'descripcion' => 'Hostal económico y acogedor, perfecto para mochileros',
                'contacto' => '955789123 - sanfranciscohostal@gmail.com',
                'servicios' => ['WiFi', 'Cocina compartida', 'Lavandería']
            ]
        ];
        
        // Filtrar por búsqueda
        if (!empty($destino)) {
            $searchTerm = strtolower($destino);
            $hotelesReales = array_filter($hotelesReales, function($hotel) use ($searchTerm) {
                return strpos(strtolower($hotel['nombre']), $searchTerm) !== false ||
                       strpos(strtolower($hotel['direccion']), $searchTerm) !== false;
            });
        }
        
        // Filtrar por estrellas
        if ($estrellas && $estrellas != '0') {
            $hotelesReales = array_filter($hotelesReales, function($hotel) use ($estrellas) {
                return $hotel['estrellas'] == $estrellas;
            });
        }
        
        return array_values($hotelesReales);
    }
}
?>