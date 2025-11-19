<?php
// En lugar de session_start() simple, usa:
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener el token del usuario para usar en JavaScript
$token_del_usuario = isset($_SESSION['token']) ? $_SESSION['token'] : 'tok_4aaaf5a2dc22b87d7c70efed5324def05be51ff8626688825f6a530dccdaec74';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tokens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .btn-buscar-hoteles {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 10px 0;
        }
        .btn-buscar-hoteles:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .mensaje-token {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
    </style>
</head>
<body>
    <!-- Tu contenido actual de la primera imagen -->
    <div class="container mt-4">
        <h2>Mís Tokens API</h2>
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Token:</strong> <?php echo $token_del_usuario; ?></p>
                
                <!-- ✅ BOTÓN BUSCAR HOTELES CON VALIDACIÓN SISHO -->
                <button id="btn-buscar-hoteles" class="btn-buscar-hoteles">
                    <i class="fas fa-search me-2"></i>Buscar Hoteles
                </button>
                
                <div id="mensaje-container"></div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Generar Nuevo Token</h5>
                <p><strong>Expiración:</strong> 18/12/2025 21:36</p>
                <button class="btn btn-primary">Generar Token</button>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Endpoints API</h5>
                <p><strong>Validar Token:</strong><br>
                GET /api/validate?token=TOKEN</p>
                
                <p><strong>Obtener Info Usuario:</strong><br>
                GET /api/user?token=TOKEN</p>
                
                <p><em>Nota: Usa estos endpoints en tus aplicaciones para validar tokens.</em></p>
            </div>
        </div>
    </div>

    <!-- ✅ JavaScript ACTUALIZADO para validar token con SISHO -->
    <script>
document.getElementById('btn-buscar-hoteles').addEventListener('click', async function() {
    const tokenSISHO = 'tok_4aaaf5a2dc22b87d7c70efed5324def05be51ff8626688825f6a530dccdaec74';
    
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validando token SISHO...';
    btn.disabled = true;
    
    try {
        console.log('🔍 Validando token SISHO:', tokenSISHO);
        
        // ✅ USA LA NUEVA RUTA
        const url = `index.php?action=verificar-token-sisho&token=${tokenSISHO}`;
        console.log('📡 URL llamada:', url);
        
        const response = await fetch(url);
        console.log('📨 Status de respuesta:', response.status);
        
        const contentType = response.headers.get('content-type');
        console.log('📋 Content-Type:', contentType);
        
        if (!contentType || !contentType.includes('application/json')) {
            const textResponse = await response.text();
            console.error('❌ Respuesta no es JSON:', textResponse.substring(0, 200));
            throw new Error('El servidor no respondió con JSON. Verifica la ruta.');
        }
        
        const data = await response.json();
        console.log('📨 Respuesta COMPLETA:', data);
        
        if (data.activo) {
            mostrarMensajeExito('✅ Token SISHO válido. Redirigiendo a hoteles...');
            setTimeout(() => {
                window.location.href = 'index.php?action=search_hotels';
            }, 1500);
        } else {
            mostrarMensajeError('❌ Error al validar el token: ' + (data.mensaje || 'Token inactivo o expirado'));
        }
    } catch (error) {
        console.error('💥 Error completo:', error);
        mostrarMensajeError('🚨 Error de conexión: ' + error.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

// ... mantén las funciones de mensajes igual ...

        function mostrarMensajeError(mensaje) {
            const overlay = document.createElement('div');
            overlay.className = 'overlay';
            
            const mensajeDiv = document.createElement('div');
            mensajeDiv.className = 'mensaje-token';
            mensajeDiv.innerHTML = `
                <div class="alert alert-danger border-0">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h4 class="alert-heading">Error de Validación</h4>
                    <p class="mb-3">${mensaje}</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button onclick="cerrarMensaje()" class="btn btn-primary">Aceptar</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            document.body.appendChild(mensajeDiv);
        }

        function mostrarMensajeExito(mensaje) {
            const overlay = document.createElement('div');
            overlay.className = 'overlay';
            
            const mensajeDiv = document.createElement('div');
            mensajeDiv.className = 'mensaje-token';
            mensajeDiv.innerHTML = `
                <div class="alert alert-success border-0">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4 class="alert-heading">¡Validación Exitosa!</h4>
                    <p class="mb-3">${mensaje}</p>
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Redirigiendo...</span>
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            document.body.appendChild(mensajeDiv);
        }

        function cerrarMensaje() {
            const overlay = document.querySelector('.overlay');
            const mensaje = document.querySelector('.mensaje-token');
            if (overlay) overlay.remove();
            if (mensaje) mensaje.remove();
        }

        // Cerrar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarMensaje();
            }
        });

        // Cerrar al hacer click fuera del mensaje
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('overlay')) {
                cerrarMensaje();
            }
        });
    </script>
</body>
</html>