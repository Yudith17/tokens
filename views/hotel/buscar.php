<?php
require_once __DIR__ . '/../layouts/header.php';

// Estas variables vienen del controlador HotelController
$hoteles = $hoteles ?? [];
$termino_busqueda = $termino_busqueda ?? '';
$total = $total ?? 0;
$token = $token ?? '';
$categoria_seleccionada = $categoria_seleccionada ?? 'todas';
$error = $error ?? '';
$mensaje = $mensaje ?? '';

// Token activo para mostrar el badge
$token_activo = "tok_4aaaf5a2dc22b87d7c70efed5324def05be51ff8626688825f6a530dccdaec74";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Hoteles - Sistema de Reservas</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 30px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
        }
        
        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2.5rem;
        }
        
        .form-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            border-left: 5px solid var(--secondary-color);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            transform: translateY(-2px);
        }
        
        label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .hotel-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-left: 4px solid var(--success-color);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .hotel-card .card-body {
            padding: 25px;
        }
        
        .hotel-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.4rem;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid var(--accent-color);
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border-left: 4px solid var(--warning-color);
        }
        
        .alert-token {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border-left: 4px solid var(--warning-color);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            font-weight: 500;
        }
        
        .search-icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        
        .feature-icon {
            color: var(--secondary-color);
            margin-right: 5px;
        }
        
        .price-tag {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .token-badge {
            background: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 0.8rem;
            position: absolute;
            top: 15px;
            right: 15px;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
            border-radius: 8px;
        }
        
        /* Modal para token expirado */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .token-expired-modal {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            border-top: 5px solid var(--accent-color);
            animation: modalAppear 0.5s ease-out;
        }

        @keyframes modalAppear {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(-50px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .token-expired-modal .modal-icon {
            font-size: 4rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        .token-expired-modal h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .token-expired-modal p {
            color: #666;
            margin-bottom: 25px;
            font-size: 1.1rem;
            line-height: 1.5;
        }

        /* Asegurar que el modal esté por encima de todo */
        .modal-overlay {
            z-index: 9999;
        }

        .container {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Badge del token -->
    <?php if (!empty($token) && $token === $token_activo): ?>
        <div class="token-badge">
            <i class="fas fa-key"></i> Token activo
        </div>
    <?php endif; ?>
    
    <h1>
        <i class="fas fa-search search-icon"></i>
        Buscar Hoteles Disponibles
    </h1>
    
    <!-- Mostrar mensaje de error si viene del index.php -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-token mt-4">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>
    
    <!-- Formulario de búsqueda -->
    <div class="form-container">
        <form method="POST" action="" class="mb-4">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token_activo); ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="nombre">
                            <i class="fas fa-hotel feature-icon"></i>
                            Nombre del Hotel o Destino:
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($termino_busqueda); ?>" 
                               placeholder="Ej: Hotel Valencia, Resort Paradise, etc." required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="categoria">
                            <i class="fas fa-star feature-icon"></i>
                            Categoría:
                        </label>
                        <select id="categoria" name="categoria" class="form-control">
                            <option value="todas" <?php echo $categoria_seleccionada == 'todas' ? 'selected' : ''; ?>>Todas las categorías</option>
                            <option value="1" <?php echo $categoria_seleccionada == '1' ? 'selected' : ''; ?>>⭐ 1 Estrella</option>
                            <option value="2" <?php echo $categoria_seleccionada == '2' ? 'selected' : ''; ?>>⭐⭐ 2 Estrellas</option>
                            <option value="3" <?php echo $categoria_seleccionada == '3' ? 'selected' : ''; ?>>⭐⭐⭐ 3 Estrellas</option>
                            <option value="4" <?php echo $categoria_seleccionada == '4' ? 'selected' : ''; ?>>⭐⭐⭐⭐ 4 Estrellas</option>
                            <option value="5" <?php echo $categoria_seleccionada == '5' ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ 5 Estrellas</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Buscar Hoteles
            </button>
        </form>
    </div>

    <!-- Mostrar errores SOLO cuando se hizo búsqueda -->
    <?php if (isset($error) && !empty($termino_busqueda)): ?>
        <!-- Modal para token expirado -->
        <?php if (strpos($error, 'token') !== false || strpos($error, 'Token') !== false): ?>
            <div class="modal-overlay" id="tokenModal">
                <div class="modal-content token-expired-modal">
                    <div class="modal-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3>Token Expirado</h3>
                    <p><?php echo $error; ?></p>
                    <button class="btn btn-primary" onclick="closeTokenModal()">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger mt-4">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Mostrar resultados SOLO cuando se hizo búsqueda y no hay error -->
    <?php if (isset($hoteles) && empty($error) && !empty($termino_busqueda)): ?>
        <?php if ($total > 0): ?>
            <div class="alert alert-success mt-4">
                <i class="fas fa-check-circle"></i>
                Se encontraron <strong><?php echo $total; ?> hotel(es)</strong> disponible(s) para 
                "<strong><?php echo htmlspecialchars($termino_busqueda); ?></strong>"
            </div>
            
            <div class="row">
                <?php foreach ($hoteles as $hotel): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="hotel-card">
                            <div class="card-body">
                                <h3 class="hotel-title">
                                    <i class="fas fa-hotel"></i> 
                                    <?php echo htmlspecialchars($hotel['nombre']); ?>
                                </h3>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-warning text-dark">
                                        <?php echo htmlspecialchars($hotel['categoria']); ?> estrellas
                                    </span>
                                    <span class="price-tag">
                                        $<?php echo htmlspecialchars($hotel['precio']); ?> /noche
                                    </span>
                                </div>
                                
                                <p class="mb-3">
                                    <i class="fas fa-map-marker-alt feature-icon"></i>
                                    <strong>Ubicación:</strong> 
                                    <?php echo htmlspecialchars($hotel['ubicacion']); ?>
                                </p>
                                
                                <p class="mb-0">
                                    <i class="fas fa-concierge-bell feature-icon"></i>
                                    <strong>Servicios:</strong> 
                                    <?php echo htmlspecialchars($hotel['servicios']); ?>
                                </p>
                                
                                <div class="mt-3">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Ver detalles
                                    </button>
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-calendar-check"></i> Reservar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php else: ?>
            <div class="alert alert-warning mt-4">
                <i class="fas fa-info-circle"></i>
                No se encontraron hoteles para "<strong><?php echo htmlspecialchars($termino_busqueda); ?></strong>"
                <br><small>Intenta con otros términos de búsqueda.</small>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts personalizados -->
<script>
    // Efectos de hover en tarjetas
    document.addEventListener('DOMContentLoaded', function() {
        const hotelCards = document.querySelectorAll('.hotel-card');
        hotelCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Mostrar modal automáticamente si existe
        const tokenModal = document.getElementById('tokenModal');
        if (tokenModal) {
            tokenModal.style.display = 'flex';
        }
    });
    
    // Función para cerrar el modal
    function closeTokenModal() {
        const modal = document.getElementById('tokenModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('tokenModal');
        if (modal && event.target === modal) {
            closeTokenModal();
        }
    });
    
    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('tokenModal');
        if (modal && event.key === 'Escape') {
            closeTokenModal();
        }
    });
</script>
</body>
</html>