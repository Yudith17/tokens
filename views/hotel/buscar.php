<?php
$title = "Buscar Hoteles - Cliente API";
$headerTitle = "Cliente API - Buscar Hoteles";
?>
<?php include '../views/layouts/header.php'; ?>

<div class="content">
    <h1 class="page-title">
        <i class="fas fa-search"></i> Buscar Hoteles Disponibles
    </h1>
    
    <div class="search-form">
        <h2 class="form-title">
            <i class="fas fa-filter"></i> Formulario de Búsqueda
        </h2>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-hotel"></i> Nombre del Hotel o Destino:
                </label>
                <input type="text" name="destino" class="form-input" 
                       placeholder="Ej: Valencia, Huanta, Las Vegas..." 
                       value="<?php echo htmlspecialchars($destino ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-star"></i> Categoría:
                </label>
                <select name="estrellas" class="form-select">
                    <option value="0" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '0') ? 'selected' : 'selected'; ?>>
                        ⭐ Todas las categorías
                    </option>
                    <option value="1" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '1') ? 'selected' : ''; ?>>
                        ⭐ 1 Estrella
                    </option>
                    <option value="2" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '2') ? 'selected' : ''; ?>>
                        ⭐⭐ 2 Estrellas
                    </option>
                    <option value="3" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '3') ? 'selected' : ''; ?>>
                        ⭐⭐⭐ 3 Estrellas
                    </option>
                    <option value="4" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '4') ? 'selected' : ''; ?>>
                        ⭐⭐⭐⭐ 4 Estrellas
                    </option>
                    <option value="5" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '5') ? 'selected' : ''; ?>>
                        ⭐⭐⭐⭐⭐ 5 Estrellas
                    </option>
                </select>
            </div>
            
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i> Buscar Hoteles
            </button>
        </form>
    </div>
    
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($resultados) && !empty($resultados)): ?>
        <div class="results-container">
            <h3 class="results-title">
                <i class="fas fa-hotel"></i> Hoteles Disponibles 
                <?php if (!empty($destino)): ?>
                    para "<?php echo htmlspecialchars($destino); ?>"
                <?php endif; ?>
                <?php if (isset($_POST['estrellas']) && $_POST['estrellas'] != '0'): ?>
                    - <?php echo str_repeat('⭐', $_POST['estrellas']); ?> Estrellas
                <?php endif; ?>
            </h3>
            
            <p class="results-count">
                <i class="fas fa-info-circle"></i> 
                Se encontraron <?php echo count($resultados); ?> hotel(es) disponible(s)
            </p>
            
            <?php foreach ($resultados as $hotel): ?>
                <div class="hotel-card">
                    <div class="hotel-header">
                        <div style="flex: 1;">
                            <h4 class="hotel-name">
                                <i class="fas fa-building"></i> 
                                <?php echo htmlspecialchars($hotel['nombre']); ?>
                            </h4>
                            
                            <div class="hotel-stars">
                                <?php echo str_repeat('⭐', $hotel['estrellas']); ?>
                                <span style="color: #6c757d; font-size: 0.9rem; margin-left: 0.5rem;">
                                    (<?php echo $hotel['estrellas']; ?> estrellas)
                                </span>
                            </div>
                            
                            <p class="hotel-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($hotel['direccion'] . ', ' . $hotel['distrito'] . ', ' . $hotel['provincia']); ?>
                            </p>
                            
                            <p class="hotel-price">
                                <i class="fas fa-money-bill-wave"></i>
                                <strong>$<?php echo number_format($hotel['precio'], 2); ?> por noche</strong>
                            </p>
                            
                            <?php if (isset($hotel['telefono']) && !empty($hotel['telefono'])): ?>
                                <p class="hotel-contact">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($hotel['telefono']); ?>
                                    <?php if (isset($hotel['email']) && !empty($hotel['email'])): ?>
                                        | <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($hotel['email']); ?>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if (isset($hotel['servicios']) && !empty($hotel['servicios'])): ?>
                                <div class="hotel-services">
                                    <i class="fas fa-concierge-bell"></i> 
                                    <strong>Servicios:</strong> <?php echo implode(', ', $hotel['servicios']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="btn-reservar">
                                <i class="fas fa-calendar-check"></i> Reservar
                            </button>
                            <button class="btn-detalles">
                                <i class="fas fa-info-circle"></i> Ver Detalles
                            </button>
                        </div>
                    </div>
                    
                    <?php if (isset($hotel['descripcion'])): ?>
                        <p class="hotel-description">
                            <i class="fas fa-quote-left"></i>
                            <?php echo htmlspecialchars($hotel['descripcion']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($resultados) && !isset($mensaje)): ?>
        <div class="alert alert-warning">
            <i class="fas fa-search"></i> 
            <h4>No hay resultados</h4>
            <p>No se encontraron hoteles para los criterios de búsqueda. Intente con otros parámetros.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>