

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
    position: relative;
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
    font-family: 'Courier New', monospace;
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

.btn-back {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-validate:hover, .btn-back:hover {
    transform: translateY(-2px);
}

.btn-validate:disabled {
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
    justify-content: center;
}

.btn-copy {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: transform 0.2s;
}

.btn-copy:hover {
    transform: translateY(-1px);
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
    transition: transform 0.2s;
}

.btn-redirect:hover {
    transform: translateY(-1px);
}

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
    margin-top: 20px;
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

.token-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.detail-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #3498db;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.detail-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.detail-value {
    color: #555;
    font-size: 1.1em;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: 600;
}

.status-valid {
    background: #d4edda;
    color: #155724;
}

.status-invalid {
    background: #f8d7da;
    color: #721c24;
}

.back-button-container {
    text-align: center;
    margin-top: 20px;
    padding: 20px;
    border-top: 1px solid #e1e5e9;
}

@media (max-width: 768px) {
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

    .token-details-grid {
        grid-template-columns: 1fr;
    }

    .token-validation-section {
        padding: 20px;
    }
}
</style>

<div class="container">
    <header>
        <h1> Validar Token</h1>
        <div class="subtitle">Sistema de Validación de Tokens de Acceso</div>
    </header>

    <section class="token-validation-section">
        <form id="validationForm" class="token-form">
            <div class="token-input-group">
                <label for="token">Ingrese el Token a Validar:</label>
                <input type="text" class="token-input" id="token" name="token" 
                       placeholder="Ingrese el token aquí..." required>
            </div>
            <button type="submit" class="btn-validate">
                <i class="fas fa-check-circle me-2"></i>Validar Token
            </button>
        </form>
        
        <!-- Botón Volver al Inicio -->
        <div class="back-button-container">
            <a href="index.php" class="btn-back">
                <i class="fas fa-home me-2"></i>Volver al Inicio
            </a>
        </div>
    </section>

    <section id="validationResult" class="results-section">
        <div class="results-header">
            <div class="results-count">Resultado de la Validación</div>
        </div>
        
        <div id="resultAlert"></div>
        
        <div id="tokenDetails" class="token-section">
            <div class="token-info">
                <h3>Información del Token Validado</h3>
                <div class="token-details-grid" id="tokenInfoTable">
                    <!-- Se llena dinámicamente -->
                </div>
            </div>
        </div>
        
        <!-- Botón Volver al Inicio en la sección de resultados -->
        <div class="back-button-container">
            <a href="index.php" class="btn-back">
                <i class="fas fa-home me-2"></i>Volver al Inicio
            </a>
        </div>
    </section>
</div>

<script>
document.getElementById('validationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const token = document.getElementById('token').value.trim();
    const resultSection = document.getElementById('validationResult');
    const resultAlert = document.getElementById('resultAlert');
    const tokenDetails = document.getElementById('tokenDetails');
    const tokenInfoTable = document.getElementById('tokenInfoTable');
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Validar que el token no esté vacío
    if (!token) {
        resultAlert.innerHTML = `
            <div class="validation-message validation-error">
                <i class="fas fa-exclamation-triangle me-2"></i>Por favor ingrese un token para validar
            </div>
        `;
        resultSection.style.display = 'block';
        return;
    }
    
    // Limpiar resultados anteriores
    resultAlert.innerHTML = '';
    tokenInfoTable.innerHTML = '';
    tokenDetails.style.display = 'none';
    
    // Mostrar loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validando...';
    
    resultAlert.innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner fa-spin me-2"></i>
            <strong>Validando token...</strong>
            <div class="text-muted">Por favor espere</div>
        </div>
    `;
    resultSection.style.display = 'block';
    
    // Hacer la petición AJAX a tu API
    fetch('validar_token.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'validarToken',
            token: token
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        if (data.success) {
            resultAlert.innerHTML = `
                <div class="validation-message validation-success">
                    <i class="fas fa-check-circle me-2"></i>${data.message}
                </div>
            `;
            
            // Mostrar detalles del token
            if (data.token_data) {
                let detailsContent = '';
                
                detailsContent += `
                    <div class="detail-card">
                        <div class="detail-label">ID del Token</div>
                        <div class="detail-value">${data.token_data.id || 'N/A'}</div>
                    </div>
                `;
                
                if (data.cliente) {
                    detailsContent += `
                        <div class="detail-card">
                            <div class="detail-label">Cliente</div>
                            <div class="detail-value">${data.cliente}</div>
                        </div>
                    `;
                }
                
                if (data.token_data.fecha_registro) {
                    detailsContent += `
                        <div class="detail-card">
                            <div class="detail-label">Fecha de Registro</div>
                            <div class="detail-value">${new Date(data.token_data.fecha_registro).toLocaleString('es-PE')}</div>
                        </div>
                    `;
                }
                
                detailsContent += `
                    <div class="detail-card">
                        <div class="detail-label">Hora de Validación</div>
                        <div class="detail-value">${new Date().toLocaleString('es-PE')}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Estado</div>
                        <div class="detail-value">
                            <span class="status-badge status-valid">VÁLIDO</span>
                        </div>
                    </div>
                `;
                
                tokenInfoTable.innerHTML = detailsContent;
                tokenDetails.style.display = 'block';
            }
        } else {
            resultAlert.innerHTML = `
                <div class="validation-message validation-error">
                    <i class="fas fa-times-circle me-2"></i>${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        resultAlert.innerHTML = `
            <div class="error">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error al validar el token</strong><br>
                <small class="text-muted">Por favor intente nuevamente</small>
            </div>
        `;
    });
});

// Efecto de focus en el input
document.getElementById('token').addEventListener('focus', function() {
    this.style.borderColor = '#3498db';
    this.style.boxShadow = '0 0 0 2px rgba(52, 152, 219, 0.2)';
});

document.getElementById('token').addEventListener('blur', function() {
    this.style.borderColor = '#e1e5e9';
    this.style.boxShadow = 'none';
});

// Copiar token al portapapeles
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Token copiado al portapapeles');
    }, function(err) {
        console.error('Error al copiar: ', err);
    });
}
</script>

