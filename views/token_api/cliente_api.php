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
        
        .token-validation-section {
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            border-bottom: 1px solid #e1e5e9;
        }
        
        .token-form {
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .token-input-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        
        .token-input-group label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .token-input {
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .token-input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn-validate {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-validate:hover {
            transform: translateY(-2px);
        }
        
        .btn-validate:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .search-section {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e1e5e9;
            display: none;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto auto;
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

        .btn-token {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
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
        
        .btn-search:hover, .btn-token:hover {
            transform: translateY(-2px);
        }
        
        .btn-search:disabled, .btn-token:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .results-section {
            padding: 30px;
            display: none;
        }
        
        .results-header {
            display: flex;
            justify-content: between;
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

        .token-section {
            background: #e8f4fd;
            border: 1px solid #b3d9f2;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            display: none;
        }

        .token-info {
            font-size: 1em;
            color: #2c3e50;
        }

        .token-value {
            font-family: monospace;
            background: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 10px 0;
            word-break: break-all;
            border: 2px dashed #3498db;
            font-size: 1.1em;
            font-weight: bold;
        }

        .token-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-copy {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-redirect {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        /* Estilos para mensajes de validación */
        .validation-message {
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: center;
            font-weight: 600;
        }

        .validation-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .validation-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

            .token-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🏨 SISHO - Sistema de Hoteles</h1>
            <div class="subtitle">Encuentra los mejores hoteles en Huanta, Ayacucho</div>
        </header>
        
        <!-- Sección de validación de token -->
        <div class="token-validation-section" id="tokenValidationSection">
            <h2>🔐 Validación de Acceso</h2>
            <p>Ingrese su token de acceso para utilizar el sistema</p>
            <div class="token-form">
                <div class="token-input-group">
                    <label for="tokenInput">Token de acceso:</label>
                    <input type="text" id="tokenInput" class="token-input" placeholder="Ingrese su token aquí">
                </div>
                <button class="btn-validate" id="btn_validar">Validar Token</button>
                <div id="validationMessage"></div>
            </div>
        </div>
        
        <!-- Sección de búsqueda (oculta inicialmente) -->
        <div class="search-section" id="searchSection">
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

        <div class="token-section" id="tokenSection">
            <div class="token-info">
                <strong>Token Generado Exitosamente</strong>
                <div class="token-value" id="tokenValue"></div>
                <div class="token-actions">
                    <button class="btn-copy" onclick="copiarToken()">📋 Copiar Token</button>
                    <a href="#" class="btn-redirect" id="redirectLink">🚀 Ir al Sistema con Token</a>
                </div>
            </div>
        </div>
        
        <div class="results-section" id="resultsSection">
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
        // Elementos del DOM
        const tokenValidationSection = document.getElementById('tokenValidationSection');
        const searchSection = document.getElementById('searchSection');
        const resultsSection = document.getElementById('resultsSection');
        const btnValidar = document.getElementById('btn_validar');
        const tokenInput = document.getElementById('tokenInput');
        const validationMessage = document.getElementById('validationMessage');
        
        // Event listeners
        btnValidar.addEventListener('click', validarToken);
        tokenInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') validarToken();
        });
        
        document.getElementById('btn_buscar').addEventListener('click', buscarHoteles);
        document.getElementById('btn_token').addEventListener('click', generarToken);
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') buscarHoteles();
        });
        document.getElementById('category').addEventListener('change', buscarHoteles);
        document.getElementById('sort').addEventListener('change', buscarHoteles);
        
        // Función para validar token contra la base de datos
        async function validarToken() {
            const token = tokenInput.value.trim();
            const btnValidar = document.getElementById('btn_validar');
            
            // Limpiar mensajes anteriores
            validationMessage.innerHTML = '';
            validationMessage.className = 'validation-message';
            
            if (!token) {
                showValidationMessage(' Por favor, ingrese un token válido', 'error');
                return;
            }
            
            // Reset estados
            btnValidar.disabled = true;
            btnValidar.textContent = 'Validando...';
            
            try {
                // Validar token contra la base de datos
                const formData = new FormData();
                formData.append('token', token);
                formData.append('action', 'validarToken');
                
                const respuesta = await fetch('validar_token.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (!respuesta.ok) {
                    throw new Error(`Error HTTP: ${respuesta.status}`);
                }
                
                const data = await respuesta.json();
                
                if (data.success) {
                    showValidationMessage('Token válido - Acceso concedido', 'success');
                    // Ocultar sección de validación y mostrar búsqueda
                    tokenValidationSection.style.display = 'none';
                    searchSection.style.display = 'block';
                    resultsSection.style.display = 'block';
                } else {
                    showValidationMessage(' Token inválido o expirado', 'error');
                }
                
            } catch (error) {
                console.error('Error:', error);
                showValidationMessage('Error al validar el token. Intente nuevamente.', 'error');
            } finally {
                btnValidar.disabled = false;
                btnValidar.textContent = 'Validar Token';
            }
        }
        
        // Función para mostrar mensajes de validación
        function showValidationMessage(message, type) {
            validationMessage.textContent = message;
            validationMessage.className = `validation-message validation-${type}`;
        }
        
        async function buscarHoteles() {
            const searchTerm = document.getElementById('search').value;
            const category = document.getElementById('category').value;
            const sortBy = document.getElementById('sort').value;
            
            const btnBuscar = document.getElementById('btn_buscar');
            const loading = document.getElementById('loading');
            const errorDiv = document.getElementById('error');
            const resultsContainer = document.getElementById('results-container');
            const tokenSection = document.getElementById('tokenSection');
            
            // Reset estados
            errorDiv.style.display = 'none';
            loading.style.display = 'block';
            tokenSection.style.display = 'none';
            btnBuscar.disabled = true;
            btnBuscar.textContent = 'Buscando...';
            resultsContainer.innerHTML = '';
            
            try {
                // Construir parámetros de búsqueda
                const params = new URLSearchParams();
                if (searchTerm) params.append('search', searchTerm);
                if (category) params.append('category', category);
                params.append('sort', sortBy);
                
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
                errorDiv.textContent = 'Error al conectar con la base de datos: ' + error.message;
                errorDiv.style.display = 'block';
                document.getElementById('contador').textContent = '0';
            } finally {
                loading.style.display = 'none';
                btnBuscar.disabled = false;
                btnBuscar.textContent = 'Buscar Hoteles';
            }
        }

        async function generarToken() {
            const btnToken = document.getElementById('btn_token');
            const loading = document.getElementById('loading');
            const errorDiv = document.getElementById('error');
            const tokenSection = document.getElementById('tokenSection');
            const tokenValue = document.getElementById('tokenValue');
            const redirectLink = document.getElementById('redirectLink');
            
            // Reset estados
            errorDiv.style.display = 'none';
            loading.style.display = 'block';
            tokenSection.style.display = 'none';
            btnToken.disabled = true;
            btnToken.textContent = 'Generando...';
            
            try {
                // Generar token sin parámetros de búsqueda
                const respuesta = await fetch('buscar_hoteles.php?action=generarToken');
                
                if (!respuesta.ok) {
                    throw new Error(`Error HTTP: ${respuesta.status}`);
                }
                
                const data = await respuesta.json();
                
                if (data.success && data.token) {
                    // Mostrar token
                    tokenValue.textContent = data.token;
                    tokenSection.style.display = 'block';
                    
                    // Configurar enlace de redirección
                    redirectLink.href = `cliente_api.php?action=resultados&token=${data.token}`;
                    
                } else {
                    throw new Error(data.error || 'Error al generar token');
                }
                
            } catch (error) {
                console.error('Error:', error);
                errorDiv.textContent = 'Error al generar token: ' + error.message;
                errorDiv.style.display = 'block';
            } finally {
                loading.style.display = 'none';
                btnToken.disabled = false;
                btnToken.textContent = 'Generar Token';
            }
        }

        function copiarToken() {
            const tokenValue = document.getElementById('tokenValue').textContent;
            navigator.clipboard.writeText(tokenValue).then(() => {
                alert('Token copiado al portapapeles');
            }).catch(err => {
                console.error('Error al copiar: ', err);
            });
        }
    </script>
</body>
</html>