<?php
class Hotel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function buscarHoteles($filtros = []) {
        $sql = "SELECT * FROM hotels WHERE 1=1";
        $params = [];
        
        if (!empty($filtros['search'])) {
            $sql .= " AND (name LIKE ? OR address LIKE ? OR district LIKE ?)";
            $searchTerm = "%{$filtros['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filtros['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filtros['category'];
        }
        
        // Ordenamiento
        $sort = $filtros['sort'] ?? 'name';
        switch ($sort) {
            case 'name_desc':
                $sql .= " ORDER BY name DESC";
                break;
            case 'category':
                $sql .= " ORDER BY category";
                break;
            case 'category_desc':
                $sql .= " ORDER BY category DESC";
                break;
            default:
                $sql .= " ORDER BY name";
                break;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>