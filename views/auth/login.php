<?php
$title = "Login - Cliente API";
?>
<?php include '../views/layouts/header.php'; ?>

<div style="max-width: 400px; margin: 100px auto; padding: 20px;">
    <h2>Login - Cliente API</h2>
    <form method="POST">
        <div style="margin-bottom: 15px;">
            <label>Usuario:</label>
            <input type="text" name="usuario" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Contraseña:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; width: 100%;">
            Iniciar Sesión
        </button>
        <?php if (isset($error)): ?>
            <div style="color: red; margin-top: 10px; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>