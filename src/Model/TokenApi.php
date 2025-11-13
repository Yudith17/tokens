<?php
class TokenApi {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function generarToken($userId, $name = '') {
        $token = bin2hex(random_bytes(32));
        $createdAt = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));
        
        $sql = "INSERT INTO tokens_api (user_id, token, name, created_at, expires_at, is_active) 
                VALUES (?, ?, ?, ?, ?, 1)";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$userId, $token, $name, $createdAt, $expiresAt]);
        
        if ($result) {
            return [
                'success' => true,
                'token' => $token,
                'id' => $this->pdo->lastInsertId()
            ];
        }
        
        return ['success' => false, 'error' => 'Error al generar token'];
    }
    
    public function validarToken($token) {
        $sql = "SELECT ta.*, u.username 
                FROM tokens_api ta 
                INNER JOIN users u ON ta.user_id = u.id 
                WHERE ta.token = ? AND ta.is_active = 1 AND ta.expires_at > NOW()";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tokenData) {
            return [
                'success' => true,
                'message' => 'Token válido',
                'token_data' => $tokenData
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Token inválido, expirado o desactivado'
        ];
    }
    
    public function getTokenByUserId($userId) {
        $sql = "SELECT * FROM tokens_api WHERE user_id = ? AND is_active = 1 ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function desactivarToken($tokenId) {
        $sql = "UPDATE tokens_api SET is_active = 0 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tokenId]);
    }
}
?>