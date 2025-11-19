<?php
class ApiController {
    private $tokenModel;
    private $sishoDb;
    
    private function connectToSisho() {
        $host = "127.0.0.1";
        $db_name = "sisho";
        $username = "root";
        $password = "";
        
        try {
            $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            error_log("Error conectando a SISHO: " . $e->getMessage());
            return null;
        }
    }

    public function __construct($db) {
        $this->tokenModel = new Token($db);
        $this->sishoDb = $this->connectToSisho();
    }

    // ✅ FUNCIÓN CORREGIDA para tu estructura exacta
    public function validarTokenSISHO($token) {
        if (!$this->sishoDb) {
            error_log("No hay conexión a la base de datos SISHO");
            return false;
        }

        try {
            // Consultar directamente en la tabla tokens_api de SISHO
            $sql = "SELECT token, activo, expiracion FROM tokens_api WHERE token = :token LIMIT 1";
            $stmt = $this->sishoDb->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            
            $token_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($token_data) {
                error_log("Token encontrado en SISHO: " . print_r($token_data, true));
                
                // Verificar si está activo (activo = 1) y no ha expirado
                $activo = ($token_data['activo'] == 1);
                $noExpirado = (strtotime($token_data['expiracion']) > time());
                
                error_log("Resultado validación - Activo: " . ($activo ? 'SI' : 'NO') . ", No Expirado: " . ($noExpirado ? 'SI' : 'NO'));
                
                return $activo && $noExpirado;
            } else {
                error_log("Token NO encontrado en SISHO: " . $token);
                return false;
            }
            
        } catch(PDOException $e) {
            error_log("Error consultando token SISHO: " . $e->getMessage());
            return false;
        }
    }
    
    // ✅ Endpoint corregido
    public function verificarTokenSISHO() {
        header('Content-Type: application/json');
        
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            echo json_encode([
                'activo' => false, 
                'error' => 'Token requerido'
            ]);
            return;
        }
        
        // Validar token en base de datos SISHO
        $esActivo = $this->validarTokenSISHO($token);
        
        echo json_encode([
            'activo' => $esActivo,
            'mensaje' => $esActivo ? 'Token SISHO activo' : 'Token SISHO inactivo o expirado',
            'token' => $token
        ]);
    }

    // ... mantén tus otros métodos existentes ...
    public function validateToken() {
        header('Content-Type: application/json');
        
        if(!isset($_GET['token'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Token no proporcionado'
            ]);
            return;
        }

        $token_data = $this->tokenModel->validateToken($_GET['token']);
        
        if($token_data) {
            echo json_encode([
                'success' => true,
                'message' => 'Token válido',
                'user' => [
                    'id' => $token_data['usuario_id'],
                    'username' => $token_data['username'],
                    'nombre_completo' => $token_data['nombre_completo']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ]);
        }
    }

    public function getUserInfo() {
        header('Content-Type: application/json');
        
        if(!isset($_GET['token'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Token no proporcionado'
            ]);
            return;
        }

        $token_data = $this->tokenModel->validateToken($_GET['token']);
        
        if($token_data) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $token_data['usuario_id'],
                    'username' => $token_data['username'],
                    'nombre_completo' => $token_data['nombre_completo'],
                    'token_expira' => $token_data['expiracion']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ]);
        }
    }

    public function searchHotels() {
        header('Content-Type: application/json');
        
        // Validar token
        if(!isset($_GET['token'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Token no proporcionado'
            ]);
            return;
        }

        $token_data = $this->tokenModel->validateToken($_GET['token']);
        if(!$token_data) {
            echo json_encode([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ]);
            return;
        }

        // Parámetros de búsqueda
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $ciudad = isset($_GET['ciudad']) ? $_GET['ciudad'] : '';
        $precio_min = isset($_GET['precio_min']) ? $_GET['precio_min'] : '';
        $precio_max = isset($_GET['precio_max']) ? $_GET['precio_max'] : '';

        try {
            // URL de tu otro sistema de hoteles
            $hotels_api_url = "http://localhost/sisho/hoteles";
            
            // Construir parámetros
            $params = [];
            if($search) $params[] = "search=" . urlencode($search);
            if($ciudad) $params[] = "ciudad=" . urlencode($ciudad);
            if($precio_min) $params[] = "precio_min=" . $precio_min;
            if($precio_max) $params[] = "precio_max=" . $precio_max;
            
            $api_url = $hotels_api_url . (count($params) > 0 ? '?' . implode('&', $params) : '');
            
            // Hacer la petición
            $response = file_get_contents($api_url);
            $hotels_data = json_decode($response, true);
            
            echo json_encode([
                'success' => true,
                'hoteles' => $hotels_data,
                'total' => count($hotels_data)
            ]);
            
        } catch(Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al conectar con el sistema de hoteles: ' . $e->getMessage()
            ]);
        }
    }
}
?>