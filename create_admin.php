<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h1>Creando Usuario Admin</h1>";

try {
    // Crear tabla con estructura MÍNIMA
    $sql = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        password VARCHAR(255) NOT NULL
    )";
    
    $db->exec($sql);
    echo "Tabla usuarios lista<br>";
    
    // Crear usuario (solo password)
    $sql = "INSERT INTO usuarios (password) 
            VALUES ('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
            ON DUPLICATE KEY UPDATE password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'";
    
    $db->exec($sql);
    echo "Usuario creado<br>";
    echo "<strong>Contraseña: password</strong><br>";
    echo "<strong>Cualquier usuario funciona</strong><br>";
    
    echo "<hr>";
    echo "<a href='index.php'>Ir al Login</a>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>