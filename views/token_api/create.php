<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Generar Nuevo Token API</h1>

<form action="index.php?action=generate" method="POST">
    <div class="form-group">
        <label for="name">Nombre del Hotel/Proyecto</label>
        <input type="text" id="name" name="name" class="form-control" required placeholder="Ej: PARK SUITES HOTEL">
    </div>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <p><strong>Información:</strong></p>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>El token se generará automáticamente</li>
            <li>El token expirará en 1 año</li>
            <li>El token estará activo por defecto</li>
        </ul>
    </div>
    
    <button type="submit" class="btn btn-primary">Generar Token</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>
