<?php
class HotelController {
    private $tokenModel;
    
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
    }

    public function search() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }

        $hoteles = [];
        $categorias = $this->getCategorias();
        $tokenStatus = $this->checkTokenStatus(); // ✅ NUEVO: Verificar estado del token
        
        // Si el token está activo y hay búsqueda, buscar hoteles
        if($tokenStatus['active'] && $_GET && !empty(array_filter($_GET))) {
            $sisho_conn = $this->connectToSisho();
            if ($sisho_conn) {
                $hoteles = $this->fetchHotelsFromSisho($_GET, $sisho_conn);
                $sisho_conn = null;
            }
        }

        include_once 'views/hoteles.php';
    }

    /**
     * ✅ NUEVO MÉTODO: Verificar estado del token del usuario
     */
    private function checkTokenStatus() {
        $userTokens = $this->tokenModel->getUserTokens($_SESSION['user_id']);
        
        if(empty($userTokens)) {
            return [
                'active' => false,
                'message' => 'No tienes tokens API activos. Genera un token primero.',
                'tokens' => []
            ];
        }
        
        // Verificar si hay al menos un token activo y no expirado
        $activeToken = null;
        foreach($userTokens as $token) {
            if($token['activo'] == 1 && strtotime($token['expiracion']) > time()) {
                $activeToken = $token;
                break;
            }
        }
        
        if($activeToken) {
            return [
                'active' => true,
                'message' => 'Token activo y válido',
                'token' => $activeToken,
                'tokens' => $userTokens
            ];
        } else {
            return [
                'active' => false,
                'message' => 'Todos tus tokens han expirado o están inactivos. Genera un nuevo token.',
                'tokens' => $userTokens
            ];
        }
    }


    private function getCategorias() {
        return [
            '' => 'Todas las categorías',
            '1' => '⭐ 1 Estrella',
            '2' => '⭐⭐ 2 Estrellas', 
            '3' => '⭐⭐⭐ 3 Estrellas',
            '4' => '⭐⭐⭐⭐ 4 Estrellas',
            '5' => '⭐⭐⭐⭐⭐ 5 Estrellas'
        ];
    }

    private function fetchHotelsFromSisho($filters, $conn) {
        try {
            $sql = "SELECT * FROM hoteles WHERE 1=1";
            $params = [];
            
            if(isset($filters['search']) && !empty($filters['search'])) {
                $sql .= " AND (nombre LIKE :search OR descripcion LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            if(isset($filters['categoria']) && !empty($filters['categoria'])) {
                $sql .= " AND categoria = :categoria";
                $params[':categoria'] = $filters['categoria'];
            }

            $sql .= " ORDER BY nombre ASC";
            
            $stmt = $conn->prepare($sql);
            
            foreach($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Error en SISHO: " . $e->getMessage());
            return $this->getSampleHotels($filters);
        }
    }

    private function getSampleHotels($filters) {
        // Hoteles de ejemplo
        return [
            [
                'id' => 1,
                'nombre' => 'Hotel Ejemplo',
                'categoria' => 3,
                'descripcion' => 'Hotel de ejemplo para demostración',
                'precio_noche' => 150
            ]
        ];
    }

}
?>