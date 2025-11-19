<?php
// Conectar sin especificar base de datos para crearla
$host = "127.0.0.1";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Crear base de datos tokens
    $conn->exec("CREATE DATABASE IF NOT EXISTS tokens");
    echo "✅ Base de datos 'tokens' creada<br>";
    
    // 2. Usar la base de datos
    $conn->exec("USE tokens");
    
    // 3. Crear tabla usuarios
    $sql = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        nombre_completo VARCHAR(100),
        activo TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "✅ Tabla 'usuarios' creada<br>";
    
    // 4. Crear usuario admin
    $sql = "INSERT IGNORE INTO usuarios (username, password, email, nombre_completo, activo) 
            VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@sistema.com', 'Administrador', 1)";
    
    $conn->exec($sql);
    echo "✅ Usuario admin creado<br>";
    echo "🔑 <strong>Usuario: admin</strong><br>";
    echo "🔑 <strong>Contraseña: password</strong><br>";
    
    // 5. Crear tabla tokens_api (si no existe)
    $sql = "CREATE TABLE IF NOT EXISTS tokens_api (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT(11) NOT NULL,
        token VARCHAR(255) NOT NULL UNIQUE,
        expiracion DATETIME NOT NULL,
        activo TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql);
    echo "✅ Tabla 'tokens_api' creada<br>";
    
    echo "<hr>";
    echo "<h2>🎉 SISTEMA TOKENS LISTO</h2>";
    echo "<a href='index.php'>Ir al Login</a>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>