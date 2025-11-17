<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?? 'Cliente API'; ?></title>
    <style>
        body { font-family: Arial; margin: 0; }
        .header { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .btn-cerrar { background: #dc3545; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer; }
        .content { padding: 50px 20px; text-align: center; }
        .btn-buscar { background: #28a745; color: white; padding: 15px 30px; font-size: 18px; border: none; border-radius: 5px; cursor: pointer; margin: 20px 0; }
        .btn-buscar:hover { background: #218838; }
        .search-form { max-width: 600px; margin: 0 auto; background: #f8f9fa; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <div><strong><?php echo $headerTitle ?? 'Cliente API'; ?></strong></div>
        <div>
            <?php echo $_SESSION['usuario'] ?? ''; ?> 
            <?php if (isset($_SESSION['usuario'])): ?>
                | <button class="btn-cerrar" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
            <?php endif; ?>
        </div>
    </div>