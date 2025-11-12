<?php
class TokenApi {
    protected $conn;  // Cambiado de private a protected
    private $table_name = "tokens_api";

    public $id;
    public $user_id;
    public $token;
    public $name;
    public $created_at;
    public $expires_at;
    public $is_active;

    public function __construct($db) {
        $this->conn = $db;
    }
   

    // Crear un nuevo token
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET user_id=:user_id, token=:token, name=:name, 
                      created_at=:created_at, expires_at=:expires_at, is_active=:is_active";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar datos
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->token = htmlspecialchars(strip_tags($this->token));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->expires_at = htmlspecialchars(strip_tags($this->expires_at));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));
        
        // Vincular valores
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":token", $this->token);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":expires_at", $this->expires_at);
        $stmt->bindParam(":is_active", $this->is_active);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los tokens
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer tokens por estado activo/inactivo
    public function readByStatus($is_active) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = :is_active ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":is_active", $is_active);
        $stmt->execute();
        return $stmt;
    }

    // Leer tokens activos
    public function readActive() {
        return $this->readByStatus(1);
    }

    // Leer tokens inactivos
    public function readInactive() {
        return $this->readByStatus(0);
    }

    // Generar un token único
    public function generateToken() {
        return bin2hex(random_bytes(32));
    }

    // Actualizar estado del token
    public function updateStatus($id, $is_active) {
        $query = "UPDATE " . $this->table_name . " SET is_active = :is_active WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":is_active", $is_active);
        $stmt->bindParam(":id", $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Agregar este método a la clase TokenApi
public function validateToken($token) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE token = :token LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
}
}
?>