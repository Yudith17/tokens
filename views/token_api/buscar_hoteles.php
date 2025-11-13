<?php
// buscar_hoteles.php - SOLO CÓDIGO PHP, SIN HTML
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

// Configuración CORRECTA
$host = 'localhost';
$dbname = 'sisho';
$username = 'root';
$password = 'root';

// Configuración del sistema de tokens
$cliente_api_url = 'http://localhost/cliente_api/index.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Función para generar token seguro
    function generarToken($longitud = 32) {
        return bin2hex(random_bytes($longitud));
    }
    
    // Función para validar y almacenar token en la base de datos
    function almacenarToken($pdo, $token, $datosBusqueda) {
        // Primero verifica si la tabla existe
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'tokens_api'")->fetch();
        
        if (!$tableCheck) {
            // Crear tabla si no existe
            $createTable = "CREATE TABLE IF NOT EXISTS tokens_api (
                id INT AUTO_INCREMENT PRIMARY KEY,
                token VARCHAR(64) UNIQUE NOT NULL,
                datos_busqueda TEXT,
                fecha_creacion DATETIME,
                fecha_expiracion DATETIME,
                utilizado TINYINT DEFAULT 0
            )";
            $pdo->exec($createTable);
        }
        
        $sql = "INSERT INTO tokens_api (token, datos_busqueda, fecha_creacion, fecha_expiracion, utilizado) 
                VALUES (:token, :datos_busqueda, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR), 0)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':datos_busqueda', json_encode($datosBusqueda));
        
        return $stmt->execute();
    }
    
    // Función para enviar búsqueda a cliente_api
    function enviarABusquedaHoteles($datosBusqueda, $token) {
        global $cliente_api_url;
        
        $payload = array_merge($datosBusqueda, ['token' => $token]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $cliente_api_url . '?action=buscarHoteles');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return ['code' => $httpCode, 'data' => json_decode($response, true)];
    }
    
    // Verificar si es una solicitud de búsqueda con token
    $action = $_GET['action'] ?? '';
    
    if ($action === 'buscarConToken') {
        // Generar token único para esta búsqueda
        $token = generarToken();
        
        // Parámetros de búsqueda
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $sort = $_GET['sort'] ?? 'name';
        
        $datosBusqueda = [
            'search' => $search,
            'category' => $category,
            'sort' => $sort,
            'timestamp' => time()
        ];
        
        // Almacenar token en la base de datos
        if (almacenarToken($pdo, $token, $datosBusqueda)) {
            // Enviar búsqueda a cliente_api
            $resultadoAPI = enviarABusquedaHoteles($datosBusqueda, $token);
            
            if ($resultadoAPI['code'] === 200 && isset($resultadoAPI['data']['status']) && $resultadoAPI['data']['status'] === 'success') {
                // Éxito - redirigir a resultados
                echo json_encode([
                    'success' => true,
                    'token' => $token,
                    'redirect_url' => $cliente_api_url . '?action=resultados&token=' . $token,
                    'message' => 'Búsqueda procesada correctamente'
                ]);
            } else {
                // Falló la comunicación con cliente_api, pero igual mostramos resultados locales
                echo json_encode([
                    'success' => true,
                    'token' => $token,
                    'redirect_url' => $cliente_api_url . '?action=resultados&token=' . $token,
                    'warning' => 'Comunicación con API secundaria falló, pero la búsqueda fue procesada',
                    'datos_busqueda' => $datosBusqueda
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al generar token de seguridad'
            ]);
        }
        exit;
    }
    
    // BÚSQUEDA LOCAL ORIGINAL
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    $sort = $_GET['sort'] ?? 'name';
    
    // Construir consulta
    $sql = "SELECT * FROM hotels WHERE 1=1";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND (name LIKE ? OR address LIKE ? OR district LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    if (!empty($category)) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    // Ordenamiento
    switch ($sort) {
        case 'name_desc':
            $sql .= " ORDER BY name DESC";
            break;
        case 'category':
            $sql .= " ORDER BY category";
            break;
        case 'category_desc':
            $sql .= " ORDER BY category DESC";
            break;
        default:
            $sql .= " ORDER BY name";
            break;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si se solicita solo datos (sin token), devolver resultados normales
    if ($action === 'soloDatos') {
        echo json_encode([
            'success' => true,
            'hotels' => $hotels,
            'total' => count($hotels),
            'search_params' => [
                'search' => $search,
                'category' => $category,
                'sort' => $sort
            ]
        ]);
    } else {
        // Respuesta normal (compatible con el código original)
        echo json_encode([
            'success' => true,
            'hotels' => $hotels,
            'total' => count($hotels)
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error de base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error general: ' . $e->getMessage()
    ]);
}
?>