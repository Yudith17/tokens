<?php
// buscar_hoteles.php - Versión corregida
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración
$host = 'localhost';
$dbname = 'sisho';
$username = 'root';
$password = 'root';

// Obtener token de la URL
$token = $_GET['token'] ?? '';

// =============================================================================
// VALIDACIÓN AUTOMÁTICA DEL TOKEN
// =============================================================================
function validarTokenAutomatico($token, $host, $dbname, $username, $password) {
    if (empty($token)) {
        return ['success' => false, 'message' => 'Token no proporcionado'];
    }
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            SELECT t.*, ca.razon_social 
            FROM Token t 
            INNER JOIN Cliente_Api ca ON t.Id_cliente_Api = ca.id 
            WHERE t.Token = ? AND t.Estado = 1
        ");
        
        $stmt->execute([$token]);
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tokenData) {
            return [
                'success' => true,
                'message' => 'Token válido',
                'cliente' => $tokenData['razon_social'],
                'token_data' => $tokenData
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Token inválido o expirado'
            ];
        }
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ];
    }
}

// Verificar si es una petición AJAX (tiene action=soloDatos)
$action = $_GET['action'] ?? '';
$isAjaxRequest = ($action === 'soloDatos');

if ($isAjaxRequest) {
    // =============================================================================
    // PARA PETICIONES AJAX - SIEMPRE DEVOLVER JSON
    // =============================================================================
    header('Content-Type: application/json; charset=utf-8');
    
    // Validar token para peticiones AJAX
    $validacionToken = validarTokenAutomatico($token, $host, $dbname, $username, $password);
    
    if (!$validacionToken['success']) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Token inválido: ' . $validacionToken['message']
        ]);
        exit;
    }
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // BÚSQUEDA
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
        
        // Devolver resultados JSON
        echo json_encode([
            'success' => true,
            'hotels' => $hotels,
            'total' => count($hotels),
            'search_params' => [
                'search' => $search,
                'category' => $category,
                'sort' => $sort
            ],
            'token_validado' => true,
            'cliente' => $validacionToken['cliente'] ?? 'Sistema SISHO'
        ]);
        
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
    exit;
    
} else {
    // =============================================================================
    // PARA ACCESO DIRECTO - MOSTRAR INTERFAZ HTML
    // =============================================================================
    
    // Validar token automáticamente
    $validacionToken = validarTokenAutomatico($token, $host, $dbname, $username, $password);
    
    // Si el token no es válido, mostrar error
    if (!$validacionToken['success']) {
        http_response_code(401);
        die("Error: " . $validacionToken['message']);
    }
    
    // Si llegamos aquí, el token es válido - Mostrar interfaz HTML
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buscador de Hoteles - SISHO</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                padding: 20px;
            }
            
            .container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            header {
                background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
                color: white;
                padding: 30px;
                text-align: center;
            }
            
            h1 {
                font-size: 2.5em;
                margin-bottom: 10px;
            }
            
            .subtitle {
                font-size: 1.2em;
                opacity: 0.9;
            }
            
            .search-section {
                padding: 30px;
                background: #f8f9fa;
                border-bottom: 1px solid #e1e5e9;
            }
            
            .search-form {
                display: grid;
                grid-template-columns: 1fr 1fr auto;
                gap: 15px;
                align-items: end;
            }
            
            .form-group {
                display: flex;
                flex-direction: column;
            }
            
            label {
                margin-bottom: 8px;
                font-weight: 600;
                color: #2c3e50;
            }
            
            input, select {
                padding: 12px 15px;
                border: 2px solid #e1e5e9;
                border-radius: 8px;
                font-size: 16px;
                transition: border-color 0.3s;
            }
            
            input:focus, select:focus {
                outline: none;
                border-color: #3498db;
            }
            
            .btn-search {
                background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: transform 0.2s;
                height: 46px;
            }
            
            .btn-search:hover {
                transform: translateY(-2px);
            }
            
            .results-section {
                padding: 30px;
            }
            
            .results-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .results-count {
                font-size: 1.3em;
                color: #2c3e50;
                font-weight: 600;
            }
            
            .filters {
                display: flex;
                gap: 10px;
                align-items: center;
            }
            
            .hotel-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 20px;
            }
            
            .hotel-card {
                background: white;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                overflow: hidden;
                transition: transform 0.3s;
            }
            
            .hotel-card:hover {
                transform: translateY(-5px);
            }
            
            .hotel-header {
                padding: 20px;
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                color: white;
            }
            
            .hotel-name {
                font-size: 1.4em;
                margin-bottom: 5px;
                font-weight: 600;
            }
            
            .hotel-category {
                display: inline-block;
                background: rgba(255,255,255,0.2);
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.9em;
            }
            
            .hotel-body {
                padding: 20px;
            }
            
            .hotel-info {
                margin-bottom: 15px;
            }
            
            .info-item {
                display: flex;
                align-items: center;
                margin-bottom: 8px;
                color: #555;
            }
            
            .info-item i {
                width: 20px;
                margin-right: 10px;
                color: #3498db;
            }
            
            .hotel-description {
                color: #666;
                font-style: italic;
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid #e1e5e9;
            }
            
            .loading {
                text-align: center;
                padding: 40px;
                color: #3498db;
                font-size: 1.1em;
            }
            
            .error {
                text-align: center;
                padding: 40px;
                color: #e74c3c;
                background: #fdf2f2;
                border-radius: 10px;
                margin: 20px 0;
            }
            
            .no-results {
                text-align: center;
                padding: 40px;
                color: #7f8c8d;
                font-size: 1.1em;
            }
            
            @media (max-width: 768px) {
                .search-form {
                    grid-template-columns: 1fr;
                }
                
                .hotel-grid {
                    grid-template-columns: 1fr;
                }
                
                .results-header {
                    flex-direction: column;
                    align-items: stretch;
                }
                
                h1 {
                    font-size: 2em;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header>
                <h1>🏨 SISHO - Sistema de Hoteles</h1>
                <div class="subtitle">Token validado - Bienvenido al buscador de hoteles</div>
            </header>
            
            <div class="search-section">
                <div class="search-form">
                    <div class="form-group">
                        <label for="search">🔍 Buscar hotel:</label>
                        <input type="text" id="search" placeholder="Nombre del hotel, dirección...">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">⭐ Categoría:</label>
                        <select id="category">
                            <option value="">Todas las categorías</option>
                            <option value="1★">1★</option>
                            <option value="2★">2★</option>
                            <option value="3★">3★</option>
                            <option value="4★">4★</option>
                            <option value="5★">5★</option>
                        </select>
                    </div>
                    
                    <button class="btn-search" id="btn_buscar">Buscar Hoteles</button>
                </div>
            </div>
            
            <div class="results-section">
                <div class="results-header">
                    <div class="results-count">
                        Hoteles encontrados: <span id="contador">0</span>
                    </div>
                    <div class="filters">
                        <label>Ordenar por:</label>
                        <select id="sort">
                            <option value="name">Nombre A-Z</option>
                            <option value="name_desc">Nombre Z-A</option>
                            <option value="category">Categoría (↑)</option>
                            <option value="category_desc">Categoría (↓)</option>
                        </select>
                    </div>
                </div>
                
                <div id="loading" class="loading" style="display: none;">
                    🔍 Buscando hoteles...
                </div>
                
                <div id="error" class="error" style="display: none;"></div>
                
                <div id="results-container">
                    <div class="no-results">
                        Ingrese un término de búsqueda y haga clic en "Buscar Hoteles"
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Token obtenido automáticamente de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            
            // Función para buscar hoteles
            async function buscarHoteles() {
                const searchTerm = document.getElementById('search').value;
                const category = document.getElementById('category').value;
                const sortBy = document.getElementById('sort').value;
                
                const btnBuscar = document.getElementById('btn_buscar');
                const loading = document.getElementById('loading');
                const errorDiv = document.getElementById('error');
                const resultsContainer = document.getElementById('results-container');
                
                // Reset estados
                errorDiv.style.display = 'none';
                loading.style.display = 'block';
                btnBuscar.disabled = true;
                btnBuscar.textContent = 'Buscando...';
                resultsContainer.innerHTML = '';
                
                try {
                    // Construir parámetros de búsqueda INCLUYENDO EL TOKEN
                    const params = new URLSearchParams();
                    if (searchTerm) params.append('search', searchTerm);
                    if (category) params.append('category', category);
                    params.append('sort', sortBy);
                    params.append('token', token); // TOKEN INCLUIDO AUTOMÁTICAMENTE
                    params.append('action', 'soloDatos');
                    
                    console.log('Buscando con token:', token); // Para debug
                    
                    const respuesta = await fetch('buscar_hoteles.php?' + params.toString());
                    
                    if (!respuesta.ok) {
                        throw new Error(`Error HTTP: ${respuesta.status}`);
                    }
                    
                    const data = await respuesta.json();
                    
                    let resultadosHTML = '';
                    
                    if (data.success && data.hotels && data.hotels.length > 0) {
                        data.hotels.forEach((hotel) => {
                            resultadosHTML += `
                            <div class="hotel-card">
                                <div class="hotel-header">
                                    <div class="hotel-name">${hotel.name}</div>
                                    <div class="hotel-category">${hotel.category}</div>
                                </div>
                                <div class="hotel-body">
                                    <div class="hotel-info">
                                        <div class="info-item">📍 ${hotel.address}</div>
                                        <div class="info-item">🏘️ ${hotel.district}, ${hotel.province}</div>
                                        <div class="info-item">📞 ${hotel.phone}</div>
                                        <div class="info-item">✉️ ${hotel.email || 'No especificado'}</div>
                                        ${hotel.website ? `<div class="info-item">🌐 <a href="${hotel.website}" target="_blank">Sitio web</a></div>` : ''}
                                    </div>
                                    ${hotel.description ? `<div class="hotel-description">"${hotel.description}"</div>` : ''}
                                </div>
                            </div>`;
                        });
                        
                        document.getElementById('contador').textContent = data.hotels.length;
                        resultsContainer.innerHTML = `<div class="hotel-grid">${resultadosHTML}</div>`;
                    } else {
                        resultsContainer.innerHTML = `
                        <div class="no-results">
                            🏨 No se encontraron hoteles que coincidan con tu búsqueda
                        </div>`;
                        document.getElementById('contador').textContent = '0';
                    }
                    
                } catch (error) {
                    console.error('Error:', error);
                    errorDiv.textContent = 'Error al buscar hoteles: ' + error.message;
                    errorDiv.style.display = 'block';
                    document.getElementById('contador').textContent = '0';
                } finally {
                    loading.style.display = 'none';
                    btnBuscar.disabled = false;
                    btnBuscar.textContent = 'Buscar Hoteles';
                }
            }

            // Event listeners
            document.getElementById('btn_buscar').addEventListener('click', buscarHoteles);
            document.getElementById('search').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') buscarHoteles();
            });
            document.getElementById('category').addEventListener('change', buscarHoteles);
            document.getElementById('sort').addEventListener('change', buscarHoteles);
        </script>
    </body>
    </html>
    <?php
}
?>