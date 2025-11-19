<?php
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener el token del usuario actual para usar en JavaScript
$token_del_usuario = isset($_SESSION['token']) ? $_SESSION['token'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Hoteles - Sistema de Tokens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hotel-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }
        .price-tag {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .search-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .token-alert {
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
            max-width: 500px;
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
        .stars {
            color: #FFD700;
            font-size: 1.2rem;
        }
        .token-status-active {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
        .token-status-inactive {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
        .btn-volver {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-volver:hover {
            background: linear-gradient(135deg, #5a6268, #343a40);
            transform: translateY(-2px);
            color: white;
        }
        .mensaje-error {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
        .mensaje-error .alert {
            padding: 20px;
            border-radius: 8px;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .btn-buscar-hoteles {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-buscar-hoteles:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=dashboard">
                <i class="fas fa-key me-2"></i>Sistema de Tokens
            </a>
            <div class="navbar-nav ms-auto">
                <a href="index.php?action=dashboard" class="nav-link">Dashboard</a>
                <a href="index.php?action=search_hotels" class="nav-link active">Buscar Hoteles</a>
                <a href="index.php?action=logout" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Overlay para mensaje de token (versión antigua) -->
    <?php if(isset($tokenError) && !empty($tokenError)): ?>
        <div class="overlay"></div>
        <div class="token-alert">
            <div class="alert alert-warning border-0">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h4 class="alert-heading">Token No Disponible</h4>
                <p class="mb-3"><?php echo $tokenError; ?></p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="index.php?action=dashboard" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i>Generar Token
                    </a>
                    <a href="index.php?action=dashboard" class="btn btn-volver">
                        <i class="fas fa-home me-2"></i>Volver al Inicio
                    </a>
                    <button onclick="closeTokenAlert()" class="btn btn-secondary">Cerrar</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        

        <!-- ✅ Mostrar estado del token -->
        <?php if(!$tokenStatus['active']): ?>
            <div class="token-status-inactive text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h2>Token No Válido</h2>
                <p class="lead"><?php echo $tokenStatus['message']; ?></p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="index.php?action=dashboard" class="btn btn-light btn-lg">
                        <i class="fas fa-key me-2"></i>Gestionar Tokens
                    </a>
                    <a href="index.php?action=dashboard" class="btn btn-volver btn-lg">
                        <i class="fas fa-home me-2"></i>Volver al Inicio
                    </a>
                </div>
            </div>
        <?php else: ?>
        
        <!-- ✅ MOSTRAR FORMULARIO SOLO SI EL TOKEN ESTÁ ACTIVO -->
        <div class="token-status-active text-center">
            <h1><i class="fas fa-hotel me-2"></i>Buscar Hoteles</h1>
            <p class="lead mb-0">
                <i class="fas fa-check-circle me-2"></i>
                Token activo - Expira: <?php echo date('d/m/Y H:i', strtotime($tokenStatus['token']['expiracion'])); ?>
            </p>
        </div>

        <!-- Botón Volver al Inicio en la parte superior -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php?action=dashboard" class="btn btn-volver">
                <i class="fas fa-arrow-left me-2"></i>Volver al Inicio
            </a>
            <div class="text-muted">
                <small>Sistema de Búsqueda de Hoteles</small>
            </div>
        </div>

        <!-- Formulario de Búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="index.php">
                    <input type="hidden" name="action" value="search_hotels">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">¿Qué hotel buscas?</label>
                            <input type="text" class="form-control" name="search" 
                                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                                   placeholder="Nombre del hotel, descripción...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Categoría</label>
                            <select class="form-select" name="categoria">
                                <?php foreach($categorias as $value => $label): ?>
                                    <option value="<?php echo $value; ?>" 
                                        <?php echo (($_GET['categoria'] ?? '') == $value) ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 h-100">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información del Token (versión mejorada) -->
        <?php if(isset($tokenStatus['tokens']) && !empty($tokenStatus['tokens'])): ?>
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-key me-2"></i>
                    <strong>Token Activo:</strong> 
                    <?php 
                    $activeCount = 0;
                    foreach($tokenStatus['tokens'] as $token) {
                        if($token['activo'] == 1 && strtotime($token['expiracion']) > time()) {
                            $activeCount++;
                        }
                    }
                    echo "Tienes $activeCount token(s) activo(s)";
                    ?>
                </div>
                <small>
                    Expira: <?php echo date('d/m/Y H:i', strtotime($tokenStatus['token']['expiracion'])); ?>
                </small>
            </div>
        <?php endif; ?>

        <!-- Resultados -->
        <?php if(isset($_GET['search']) || isset($_GET['categoria'])): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>
                    <?php if(empty($hoteles)): ?>
                        No se encontraron resultados
                    <?php else: ?>
                        <?php echo count($hoteles); ?> hotel(es) encontrado(s)
                    <?php endif; ?>
                </h4>
                <?php if(!empty($hoteles)): ?>
                    <small class="text-muted">Conectado a: Sistema SISHO</small>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php if(empty($hoteles) && (isset($_GET['search']) || isset($_GET['categoria']))): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No se encontraron hoteles que coincidan con tu búsqueda.
                        <?php if(isset($_GET['search'])): ?>
                            <br><small>Búsqueda: "<?php echo htmlspecialchars($_GET['search']); ?>"</small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif(empty($hoteles)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Utiliza el formulario para buscar hoteles en el sistema SISHO.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach($hoteles as $hotel): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card hotel-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($hotel['nombre']); ?></h5>
                                
                                <!-- Mostrar estrellas según categoría -->
                                <?php if(isset($hotel['categoria'])): ?>
                                    <p class="mb-2">
                                        <span class="stars">
                                            <?php echo str_repeat('⭐', $hotel['categoria']); ?>
                                        </span>
                                        <small class="text-muted">(<?php echo $hotel['categoria']; ?> estrellas)</small>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if(isset($hotel['distrito']) && isset($hotel['provincia'])): ?>
                                    <p class="card-text mb-2">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        <?php echo htmlspecialchars($hotel['distrito']); ?>, <?php echo htmlspecialchars($hotel['provincia']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <p class="card-text small text-muted mb-3">
                                    <?php echo htmlspecialchars($hotel['descripcion'] ?? 'Descripción no disponible'); ?>
                                </p>
                                
                                <div class="mt-auto">
                                    <?php if(isset($hotel['telefono'])): ?>
                                        <p class="mb-1">
                                            <i class="fas fa-phone me-2 text-success"></i>
                                            <?php echo htmlspecialchars($hotel['telefono']); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if(isset($hotel['email'])): ?>
                                        <p class="mb-3">
                                            <i class="fas fa-envelope me-2 text-primary"></i>
                                            <?php echo htmlspecialchars($hotel['email']); ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if(isset($hotel['precio_noche'])): ?>
                                            <span class="price-tag">
                                                $<?php echo number_format($hotel['precio_noche'], 0); ?> /noche
                                            </span>
                                        <?php endif; ?>
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-info-circle me-1"></i> Detalles
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Botón Volver al Inicio en la parte inferior -->
        <div class="text-center mt-5">
            <a href="index.php?action=dashboard" class="btn btn-volver btn-lg">
                <i class="fas fa-home me-2"></i>Volver al Inicio
            </a>
        </div>
        
        <?php endif; // Cierre del if token activo ?>
        
    </div>

    <!-- ✅ NUEVO: JavaScript para validar token con SISHO -->
    <script>
        document.getElementById('btn-buscar-hoteles').addEventListener('click', async function() {
            // Obtener el token del usuario actual
            const token = '<?php echo $token_del_usuario; ?>';
            
            // Mostrar loading
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validando token...';
            btn.disabled = true;
            
            try {
                // Verificar token con SISHO
                const response = await fetch(`/api/verificar-token-sisho?token=${token}`);
                const data = await response.json();
                
                if (data.activo) {
                    // Token ACTIVO - Redirigir al formulario de hoteles
                    mostrarMensajeExito('Token válido, redirigiendo...');
                    setTimeout(() => {
                        window.location.href = 'index.php?action=search_hotels';
                    }, 1500);
                } else {
                    // Token INACTIVO - Mostrar mensaje
                    mostrarMensajeError('El token ha expirado o está inactivo en SISHO');
                }
            } catch (error) {
                mostrarMensajeError('Error al verificar el token con SISHO');
                console.error('Error:', error);
            } finally {
                // Restaurar botón
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        function mostrarMensajeError(mensaje) {
            const mensajeContainer = document.getElementById('mensaje-container');
            mensajeContainer.innerHTML = `
                <div class="mensaje-error">
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h5>Error de Token</h5>
                        <p class="mb-0">${mensaje}</p>
                    </div>
                </div>
            `;
            
            // Ocultar mensaje después de 5 segundos
            setTimeout(() => {
                mensajeContainer.innerHTML = '';
            }, 5000);
        }

        function mostrarMensajeExito(mensaje) {
            const mensajeContainer = document.getElementById('mensaje-container');
            mensajeContainer.innerHTML = `
                <div class="mensaje-error">
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h5>¡Éxito!</h5>
                        <p class="mb-0">${mensaje}</p>
                    </div>
                </div>
            `;
            
            // Ocultar mensaje después de 3 segundos
            setTimeout(() => {
                mensajeContainer.innerHTML = '';
            }, 3000);
        }

        function closeTokenAlert() {
            const overlay = document.querySelector('.overlay');
            const alert = document.querySelector('.token-alert');
            if (overlay) overlay.remove();
            if (alert) alert.remove();
        }

        // Cerrar con ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTokenAlert();
            }
        });

        // Cerrar alerta al hacer click fuera de ella
        document.addEventListener('click', function(e) {
            const alert = document.querySelector('.token-alert');
            const overlay = document.querySelector('.overlay');
            if (alert && overlay && e.target === overlay) {
                closeTokenAlert();
            }
        });
    </script>
</body>
</html>