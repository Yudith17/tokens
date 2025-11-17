<?php
$title = "Buscar Hoteles - Cliente API";
$headerTitle = "Cliente API - Buscar Hoteles";
?>
<?php include '../views/layouts/header.php'; ?>

<div class="content">
    <h1>Buscar Hoteles Disponibles</h1>
    
    <div class="search-form">
        <h2>🔍 Formulario de Búsqueda</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre del Hotel o Destino:</label>
                <input type="text" name="destino" placeholder="Ej: Hilton, Marriott, Cancún, París..." value="<?php echo htmlspecialchars($destino ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Categoría:</label>
                <select name="estrellas" class="category-select">
                    <option value="0" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '0') ? 'selected' : 'selected'; ?>>⭐ Todas las categorías</option>
                    <option value="1" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '1') ? 'selected' : ''; ?>>⭐ 1 Estrella</option>
                    <option value="2" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '2') ? 'selected' : ''; ?>>⭐⭐ 2 Estrellas</option>
                    <option value="3" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '3') ? 'selected' : ''; ?>>⭐⭐⭐ 3 Estrellas</option>
                    <option value="4" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '4') ? 'selected' : ''; ?>>⭐⭐⭐⭐ 4 Estrellas</option>
                    <option value="5" <?php echo (isset($_POST['estrellas']) && $_POST['estrellas'] == '5') ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ 5 Estrellas</option>
                </select>
            </div>
            
            <button type="submit" class="btn-buscar">🔍 Buscar Hoteles</button>
        </form>
    </div>
    
    <?php if (isset($mensaje)): ?>
        <div style="margin: 20px auto; padding: 15px; background: #f8d7da; color: #721c24; border-radius: 5px; max-width: 600px;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($resultados) && !empty($resultados)): ?>
        <div style="max-width: 1000px; margin: 30px auto; text-align: left;">
            <h3>🏨 Hoteles Disponibles 
                <?php if (!empty($destino)): ?>
                    para "<?php echo htmlspecialchars($destino); ?>"
                <?php endif; ?>
                <?php if (isset($_POST['estrellas']) && $_POST['estrellas'] != '0'): ?>
                    - <?php echo str_repeat('⭐', $_POST['estrellas']); ?> Estrellas
                <?php endif; ?>
            </h3>
            
            <p style="color: #666; margin-bottom: 20px;">
                Se encontraron <?php echo count($resultados); ?> hotel(es) disponible(s)
            </p>
            
            <?php foreach ($resultados as $hotel): ?>
                <div style="border: 1px solid #ddd; padding: 20px; margin-bottom: 15px; border-radius: 8px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 10px 0; color: #333; font-size: 1.2em;">
                                <?php echo htmlspecialchars($hotel['nombre'] ?? $hotel['name'] ?? 'Hotel'); ?>
                            </h4>
                            
                            <div style="display: flex; align-items: center; margin: 5px 0;">
                                <span style="color: #ffc107; margin-right: 10px;">
                                    <?php echo str_repeat('⭐', $hotel['estrellas'] ?? $hotel['stars'] ?? 3); ?>
                                </span>
                                <span style="color: #666; font-size: 0.9em;">
                                    (<?php echo $hotel['estrellas'] ?? $hotel['stars'] ?? 3; ?> estrellas)
                                </span>
                            </div>
                            
                            <p style="margin: 5px 0; color: #666;">
                                📍 <?php echo htmlspecialchars($hotel['direccion'] ?? $hotel['address'] ?? $hotel['ubicacion'] ?? 'Dirección no disponible'); ?>
                            </p>
                            
                            <p style="margin: 5px 0; color: #28a745; font-size: 1.1em;">
                                💰 <strong>$<?php echo number_format($hotel['precio'] ?? $hotel['price'] ?? $hotel['rate'] ?? $hotel['tarifa'] ?? 0, 2); ?> por noche</strong>
                            </p>
                            
                            <?php if (isset($hotel['servicios']) || isset($hotel['amenities'])): ?>
                                <p style="margin: 8px 0; color: #555; font-size: 0.9em;">
                                🛎️ Servicios: 
                                <?php 
                                $servicios = $hotel['servicios'] ?? $hotel['amenities'] ?? [];
                                if (is_array($servicios)) {
                                    echo implode(', ', array_slice($servicios, 0, 3));
                                } else {
                                    echo substr($servicios, 0, 100) . '...';
                                }
                                ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <div style="text-align: right; min-width: 120px;">
                            <button style="background: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; margin-bottom: 8px;">
                                Reservar
                            </button>
                            <button style="background: #17a2b8; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9em; width: 100%;">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                    
                    <?php if (isset($hotel['descripcion']) || isset($hotel['description'])): ?>
                        <p style="margin: 15px 0 0 0; color: #555; font-style: italic; border-top: 1px solid #eee; padding-top: 10px;">
                            <?php 
                            $descripcion = $hotel['descripcion'] ?? $hotel['description'] ?? '';
                            echo htmlspecialchars(strlen($descripcion) > 200 ? substr($descripcion, 0, 200) . '...' : $descripcion); 
                            ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($resultados) && !isset($mensaje)): ?>
        <div style="margin: 30px auto; padding: 20px; background: #fff3cd; color: #856404; border-radius: 5px; max-width: 600px;">
            <h4>⚠️ No hay resultados</h4>
            <p>No se encontraron hoteles para los criterios de búsqueda. Intente con otros parámetros.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.form-group {
    margin-bottom: 20px;
}

input[type="text"], input[type="date"], select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    background: white;
}

.category-select {
    background: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 12px;
    font-size: 16px;
    width: 100%;
    cursor: pointer;
}

.category-select:focus {
    border-color: #28a745;
    outline: none;
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
}

.btn-buscar {
    background: #28a745;
    color: white;
    padding: 15px 30px;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
    transition: background 0.3s ease;
}

.btn-buscar:hover {
    background: #218838;
}

.search-form {
    max-width: 600px;
    margin: 0 auto;
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}
</style>

</body>
</html>