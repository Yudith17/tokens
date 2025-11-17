<?php
require_once '../src/config/database.php';

class Hotel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function buscarHoteles($destino, $estrellas = null) {
        // Usar directamente los datos reales de tu sistema SISHO
        return $this->getHotelesReales($destino, $estrellas);
    }
    
    // Datos REALES basados en tu lista de hoteles de SISHO
    private function getHotelesReales($destino, $estrellas) {
        $hotelesReales = [
            [
                'id' => 1,
                'nombre' => 'Hotel Valencia',
                'estrellas' => 3,
                'direccion' => 'Jiron Saenz Peña',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 120,
                'descripcion' => 'Amplias y cómodas habitaciones, la atención fue excelente',
                'telefono' => '914721345',
                'email' => 'hotelvalencia@gmail.com',
                'servicios' => ['WiFi', 'Estacionamiento', 'Recepcion 24h', 'Restaurante']
            ],
            [
                'id' => 2,
                'nombre' => 'Hotel Su Majestad 2',
                'estrellas' => 2,
                'direccion' => 'Jr. Osvaldo N. Regal 411',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 80,
                'descripcion' => 'Su Majestad II es un hospedaje de 2 estrellas que ofrece comodidad',
                'telefono' => '984637463',
                'email' => 'sumagestad2@gmail.com',
                'servicios' => ['WiFi', 'Recepcion', 'Estacionamiento']
            ],
            [
                'id' => 3,
                'nombre' => 'Moreno\'s',
                'estrellas' => 3,
                'direccion' => 'Calle San Ignacio 364, Laredo, Peru',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 95,
                'descripcion' => 'DEJA QUE EL GUSTÓ DECIDA. Ofrecemos la mejor experiencia',
                'telefono' => '989522700',
                'email' => 'morenos.chicken@gmail.com',
                'servicios' => ['Restaurante', 'WiFi', 'Recepcion']
            ],
            [
                'id' => 4,
                'nombre' => 'Hotel Las Vegas',
                'estrellas' => 3,
                'direccion' => 'Av. Mariscal Castilla Nro 882',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 110,
                'descripcion' => 'Hotel con excelente ubicación en el centro de Huanta',
                'telefono' => '936252508',
                'email' => 'hotelasvegashuanta@gmail.com',
                'servicios' => ['WiFi', 'Estacionamiento', 'Restaurante', 'Recepcion 24h']
            ],
            [
                'id' => 5,
                'nombre' => 'Hostal Nina Quintana',
                'estrellas' => 3,
                'direccion' => 'Jr. Manuel Jesús Urbina 166',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 65,
                'descripcion' => 'Un lugar agradable, acogedor y discreto para que usted pase una estadía placentera',
                'telefono' => '910027208',
                'email' => '',
                'servicios' => ['WiFi', 'Habitaciones familiares', 'Recepcion']
            ],
            [
                'id' => 6,
                'nombre' => 'GRAN HOTEL IMPERIAL HUANTA',
                'estrellas' => 3,
                'direccion' => 'Jr. Miguel Uniiveros 257',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 130,
                'descripcion' => 'Muy cerca al centro y al mercado. Ubicación estratégica',
                'telefono' => '923056622',
                'email' => 'granhotelimperial@gmail.com',
                'servicios' => ['WiFi', 'Estacionamiento', 'Restaurante', 'Recepcion 24h', 'Room Service']
            ],
            [
                'id' => 8,
                'nombre' => 'Hotel Huanta Grande',
                'estrellas' => 3,
                'direccion' => 'Av. Libertad 123',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 150,
                'descripcion' => 'El mejor hotel de la ciudad con servicios premium y atención personalizada',
                'telefono' => '966123456',
                'email' => 'huantagrande@hotel.com',
                'servicios' => ['WiFi', 'Piscina', 'Spa', 'Restaurante', 'Estacionamiento', 'Gimnasio']
            ],
            [
                'id' => 9,
                'nombre' => 'Hostal San Francisco',
                'estrellas' => 3,
                'direccion' => 'Jr. San Martín 456',
                'distrito' => 'HUANTA',
                'provincia' => 'HUANTA',
                'precio' => 70,
                'descripcion' => 'Hostal económico y acogedor, perfecto para mochileros',
                'telefono' => '955789123',
                'email' => 'sanfranciscohostal@gmail.com',
                'servicios' => ['WiFi', 'Cocina compartida', 'Lavandería', 'Recepcion']
            ]
        ];
        
        
        // Filtrar por búsqueda
        if (!empty($destino)) {
            $searchTerm = strtolower($destino);
            $hotelesReales = array_filter($hotelesReales, function($hotel) use ($searchTerm) {
                return strpos(strtolower($hotel['nombre']), $searchTerm) !== false ||
                       strpos(strtolower($hotel['direccion']), $searchTerm) !== false ||
                       strpos(strtolower($hotel['distrito']), $searchTerm) !== false ||
                       strpos(strtolower($hotel['descripcion']), $searchTerm) !== false;
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
    public function verificarTokenActivo() {
        $stmt = $this->db->prepare("
            SELECT token, name, expires_at 
            FROM tokens_api 
            WHERE is_active = 1 AND expires_at > NOW() 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute();
        $token_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return !empty($token_data);
    }
}
?>