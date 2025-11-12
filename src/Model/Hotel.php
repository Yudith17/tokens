<?php
class Hotel {
    private $conn;
    private $table_name = "hotels";

    public $id;
    public $name;
    public $category;
    public $description;
    public $address;
    public $district;
    public $province;
    public $department;
    public $phone;
    public $email;
    public $website;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
        
    }

    // Obtener hotel por nombre
    public function getByName($name) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name = :name LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar valores a las propiedades
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->category = $row['category'];
            $this->description = $row['description'];
            $this->address = $row['address'];
            $this->district = $row['district'];
            $this->province = $row['province'];
            $this->department = $row['department'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->website = $row['website'];
            $this->created_at = $row['created_at'];
            
            return true;
        }
        return false;
    }

    // Obtener hotel por ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar valores a las propiedades
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->category = $row['category'];
            $this->description = $row['description'];
            $this->address = $row['address'];
            $this->district = $row['district'];
            $this->province = $row['province'];
            $this->department = $row['department'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->website = $row['website'];
            $this->created_at = $row['created_at'];
            
            return true;
        }
        return false;
    }

    // Buscar hotel por nombre (búsqueda parcial)
    public function searchByName($name) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name LIKE :name";
        $stmt = $this->conn->prepare($query);
        $search_term = "%" . $name . "%";
        $stmt->bindParam(":name", $search_term);
        $stmt->execute();

        return $stmt;
    }
}
?>