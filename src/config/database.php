<?php
class Database {
    private $host = "localhost";
    private $db_name = "tokens";
    private $username = "root";
    private $password = "root"; // MAMP usa 'root' por defecto
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
            echo "<br>Verifica que la base de datos 'tokens' exista y las credenciales sean correctas.";
        }
        return $this->conn;
    }
}
?>