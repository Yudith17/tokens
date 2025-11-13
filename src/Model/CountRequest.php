<?php
require_once __DIR__ . '/../../config/database.php';

class CountRequest {
    private $db;

    public function __construct($pdo = null) {
        $this->db = $pdo ?? Database::getInstance();
    }

    // Obtener todas las solicitudes con información del token y cliente
    public function getAll() {
        $stmt = $this->db->query("
            SELECT cr.*, t.Token, c.razon_social 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            LEFT JOIN Cliente_Api c ON t.Id_cliente_Api = c.id
            ORDER BY cr.fecha DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener estadísticas generales
    public function getStats() {
        $stats = [];

        // Total de solicitudes
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM Count_Request");
        $stats['total_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Tokens únicos que han hecho solicitudes
        $stmt = $this->db->query("SELECT COUNT(DISTINCT Id_Token) as unique_tokens FROM Count_Request");
        $stats['unique_tokens'] = $stmt->fetch(PDO::FETCH_ASSOC)['unique_tokens'];

        // Solicitudes de hoy
        $stmt = $this->db->query("SELECT COUNT(*) as today FROM Count_Request WHERE DATE(fecha) = CURDATE()");
        $stats['today_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['today'];

        // Solicitudes por tipo
        $stmt = $this->db->query("SELECT Tipo, COUNT(*) as count FROM Count_Request GROUP BY Tipo");
        $stats['requests_by_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Solicitudes por token (top 5)
        $stmt = $this->db->query("
            SELECT t.Token, c.razon_social, COUNT(cr.Id) as request_count 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            LEFT JOIN Cliente_Api c ON t.Id_cliente_Api = c.id
            GROUP BY cr.Id_Token 
            ORDER BY request_count DESC 
            LIMIT 5
        ");
        $stats['top_tokens'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    // Buscar una solicitud por ID
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT cr.*, t.Token, c.razon_social 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            LEFT JOIN Cliente_Api c ON t.Id_cliente_Api = c.id
            WHERE cr.Id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva solicitud
    public function create($id_token, $tipo) {
        $stmt = $this->db->prepare("
            INSERT INTO Count_Request (Id_Token, Tipo, fecha) 
            VALUES (?, ?, NOW())
        ");
        return $stmt->execute([$id_token, $tipo]);
    }

    // Eliminar una solicitud
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Count_Request WHERE Id = ?");
        return $stmt->execute([$id]);
    }

    // Obtener tokens activos para el formulario
    public function getActiveTokens() {
        $stmt = $this->db->query("
            SELECT t.id, t.Token, c.razon_social 
            FROM Token t
            LEFT JOIN Cliente_Api c ON t.Id_cliente_Api = c.id
            WHERE t.Estado = 1
            ORDER BY c.razon_social
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===== MÉTODOS NUEVOS PARA LA FUNCIONALIDAD DE BÚSQUEDA =====

    /**
     * Obtener todas las solicitudes de un cliente específico
     */
    public function getByClientId($clientId) {
        $stmt = $this->db->prepare("
            SELECT cr.*, t.Token, c.razon_social 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            LEFT JOIN Cliente_Api c ON t.Id_cliente_Api = c.id
            WHERE c.id = ?
            ORDER BY cr.fecha DESC
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener estadísticas específicas de un cliente
     */
    public function getStatsByClient($clientId) {
        $stats = [];

        // Total de solicitudes del cliente
        $stmt = $this->db->prepare("
            SELECT COUNT(cr.Id) as total_requests
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ?
        ");
        $stmt->execute([$clientId]);
        $stats['total_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_requests'] ?? 0;

        // Solicitudes exitosas (GET, POST, PUT, DELETE)
        $stmt = $this->db->prepare("
            SELECT COUNT(cr.Id) as successful_requests
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ? 
            AND (cr.Tipo LIKE 'GET%' OR cr.Tipo LIKE 'POST%' OR cr.Tipo LIKE 'PUT%' OR cr.Tipo LIKE 'DELETE%')
        ");
        $stmt->execute([$clientId]);
        $stats['successful_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['successful_requests'] ?? 0;

        // Solicitudes con errores
        $stmt = $this->db->prepare("
            SELECT COUNT(cr.Id) as error_requests
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ? 
            AND cr.Tipo LIKE '%error%'
        ");
        $stmt->execute([$clientId]);
        $stats['error_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['error_requests'] ?? 0;

        // Tiempo promedio de respuesta (simulado - en producción usarías datos reales)
        $stats['avg_response_time'] = rand(1, 5) + (rand(0, 99) / 100);

        // Solicitudes por tipo para este cliente
        $stmt = $this->db->prepare("
            SELECT cr.Tipo, COUNT(*) as count 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ?
            GROUP BY cr.Tipo 
            ORDER BY count DESC
        ");
        $stmt->execute([$clientId]);
        $stats['requests_by_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tokens utilizados por este cliente
        $stmt = $this->db->prepare("
            SELECT t.Token, COUNT(cr.Id) as request_count 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ?
            GROUP BY t.Token 
            ORDER BY request_count DESC
        ");
        $stmt->execute([$clientId]);
        $stats['tokens_used'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    /**
     * Obtener tipos de requests de un cliente
     */
    public function getRequestTypes($clientId) {
        $stmt = $this->db->prepare("
            SELECT cr.Tipo, COUNT(*) as count 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ? 
            GROUP BY cr.Tipo 
            ORDER BY count DESC
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener solicitudes por rango de fechas para un cliente
     */
    public function getByClientAndDateRange($clientId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT cr.*, t.Token, c.razon_social 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            LEFT JOIN Cliente_Api c ON t.Id_cliente_Api = c.id
            WHERE c.id = ? AND cr.fecha BETWEEN ? AND ?
            ORDER BY cr.fecha DESC
        ");
        $stmt->execute([$clientId, $startDate, $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener el último mes de actividad de un cliente
     */
    public function getLastMonthActivity($clientId) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(cr.fecha) as date,
                COUNT(*) as daily_requests
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ? 
            AND cr.fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(cr.fecha)
            ORDER BY date DESC
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener horas pico de requests para un cliente
     */
    public function getPeakHours($clientId) {
        $stmt = $this->db->prepare("
            SELECT 
                HOUR(cr.fecha) as hour,
                COUNT(*) as request_count
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            WHERE t.Id_cliente_Api = ?
            GROUP BY HOUR(cr.fecha)
            ORDER BY request_count DESC
            LIMIT 5
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar solicitudes por término en el tipo para un cliente específico
     */
    public function searchByTypeAndClient($clientId, $searchTerm) {
        $stmt = $this->db->prepare("
            SELECT cr.*, t.Token, c.razon_social 
            FROM Count_Request cr
            LEFT JOIN Token t ON cr.Id_Token = t.id
            LEFT JOIN Cliente_Api c ON t.Id_cliente_Api = c.id
            WHERE c.id = ? AND cr.Tipo LIKE ?
            ORDER BY cr.fecha DESC
        ");
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->execute([$clientId, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>