<?php
class Token {
    private $conn;
    private $table = "tokens_api";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateToken($user_id, $length = 32) {
        $token = bin2hex(random_bytes($length));
        $expiracion = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $query = "INSERT INTO " . $this->table . " 
                  SET usuario_id=:user_id, token=:token, expiracion=:expiracion, activo=1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":expiracion", $expiracion);
        
        try {
            if($stmt->execute()) {
                return $token;
            } else {
                $error = $stmt->errorInfo();
                error_log("Error en generateToken execute: " . print_r($error, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("PDOException en generateToken: " . $e->getMessage());
            return false;
        }
    }

    public function validateToken($token) {
        $query = "SELECT t.*, u.username, u.nombre_completo 
                  FROM " . $this->table . " t
                  JOIN usuarios u ON t.usuario_id = u.id
                  WHERE t.token = :token AND t.activo = 1 AND t.expiracion > NOW()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        
        try {
            if($stmt->execute()) {
                if($stmt->rowCount() == 1) {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
                return false;
            } else {
                $error = $stmt->errorInfo();
                error_log("Error en validateToken execute: " . print_r($error, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("PDOException en validateToken: " . $e->getMessage());
            return false;
        }
    }

    public function getUserTokens($user_id) {
        // PRIMERO: Verificar qué columnas existen realmente
        $columns = $this->getTableColumns();
        error_log("Columnas disponibles: " . implode(', ', $columns));
        
        // CONSTRUIR QUERY BASADA EN COLUMNAS REALES
        if (in_array('usuario_id', $columns)) {
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE usuario_id = :user_id AND activo = 1 
                      ORDER BY created_at DESC";
        } elseif (in_array('user_id', $columns)) {
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE user_id = :user_id AND activo = 1 
                      ORDER BY created_at DESC";
        } else {
            // Si no hay columna de usuario, mostrar todos los tokens activos
            error_log("No se encontró columna de usuario, mostrando todos los tokens");
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE activo = 1 
                      ORDER BY created_at DESC";
        }
        
        error_log("Ejecutando query: " . $query);
        
        $stmt = $this->conn->prepare($query);
        
        // Solo hacer bind si la query tiene :user_id
        if (strpos($query, ':user_id') !== false) {
            $stmt->bindParam(":user_id", $user_id);
        }
        
        try {
            if($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Tokens encontrados: " . count($result));
                return $result;
            } else {
                $error = $stmt->errorInfo();
                error_log("Error en getUserTokens execute: " . print_r($error, true));
                return [];
            }
        } catch (PDOException $e) {
            error_log("PDOException en getUserTokens: " . $e->getMessage());
            return [];
        }
    }

    public function revokeToken($token_id, $user_id) {
        $columns = $this->getTableColumns();
        
        if (in_array('usuario_id', $columns)) {
            $query = "UPDATE " . $this->table . " 
                      SET activo = 0 
                      WHERE id = :id AND usuario_id = :user_id";
        } else {
            $query = "UPDATE " . $this->table . " 
                      SET activo = 0 
                      WHERE id = :id AND user_id = :user_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $token_id);
        $stmt->bindParam(":user_id", $user_id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("PDOException en revokeToken: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Método para obtener las columnas reales de la tabla
     */
    private function getTableColumns() {
        try {
            $stmt = $this->conn->prepare("DESCRIBE " . $this->table);
            if($stmt->execute()) {
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                return $columns;
            }
            return [];
        } catch (PDOException $e) {
            error_log("Error obteniendo columnas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Método de emergencia: Obtener tokens sin filtro de usuario
     */
    public function getAllActiveTokens() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE activo = 1 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        try {
            if($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        } catch (PDOException $e) {
            error_log("PDOException en getAllActiveTokens: " . $e->getMessage());
            return [];
        }
    }
    // En models/Token.php - Agrega este método
public function validateSishoToken($token) {
    $query = "SELECT t.*, u.username, u.nombre_completo 
              FROM " . $this->table . " t
              JOIN usuarios u ON t.usuario_id = u.id
              WHERE t.token = :token AND t.activo = 1 AND t.expiracion > NOW()";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    if($stmt->rowCount() == 1) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
}
 // Puedes agregar métodos para gestionar tokens locales
    public static function verificarEstado($token) {
        // Lógica adicional si necesitas verificar en tu base de datos también
        return true;
    }
}
?>