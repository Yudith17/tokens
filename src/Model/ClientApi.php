<?php

class ClientApi {
    private $conn;
    private $table_name = "client_api";

    public $id;
    public $ruc;
    public $razon_social;
    public $correo;
    public $telefono;
    public $estado;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todos los clientes API
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_registro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para buscar cliente por ID
    public function find($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->ruc = $row['ruc'];
            $this->razon_social = $row['razon_social'];
            $this->correo = $row['correo'];
            $this->telefono = $row['telefono'];
            $this->estado = $row['estado'];
            $this->fecha_registro = $row['fecha_registro'];
            return $row;
        }
        return false;
    }

    // Método para crear nuevo cliente API
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (ruc, razon_social, correo, telefono, estado, fecha_registro) 
                 VALUES (:ruc, :razon_social, :correo, :telefono, :estado, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $ruc = htmlspecialchars(strip_tags($data['ruc']));
        $razon_social = htmlspecialchars(strip_tags($data['razon_social']));
        $correo = htmlspecialchars(strip_tags($data['correo']));
        $telefono = htmlspecialchars(strip_tags($data['telefono'] ?? ''));
        $estado = htmlspecialchars(strip_tags($data['estado']));
        
        // Bind parameters
        $stmt->bindParam(':ruc', $ruc);
        $stmt->bindParam(':razon_social', $razon_social);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':estado', $estado);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para obtener búsquedas del cliente
    public function getSearches($client_id) {
        $query = "SELECT * FROM count_requests WHERE client_api_id = ? ORDER BY fecha_consulta DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }
}
?>