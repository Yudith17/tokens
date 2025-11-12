<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Gestión de Tokens API</h1>

<?php
// Mostrar mensajes de éxito o error
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div class="filter-buttons">
        <a href="index.php?status=all" class="btn <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'btn-primary' : 'btn-secondary'; ?>">Todos los Tokens</a>
        <a href="index.php?status=active" class="btn <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'btn-primary' : 'btn-secondary'; ?>">Tokens Activos</a>
        <a href="index.php?status=inactive" class="btn <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'btn-primary' : 'btn-secondary'; ?>">Tokens Inactivos</a>
    </div>
    
    <div style="display: flex; gap: 10px;">
        <a href="index.php?action=create" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Generar Token
        </a>
        <a href="index.php?action=validate" class="btn btn-info">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Validar Token API
        </a>
        <a href="index.php?action=logout" class="btn btn-secondary">Cerrar Sesión</a>
    </div>
</div>

<div class="token-list">
    <?php
    // Mostrar tokens
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $status_class = $is_active ? 'status-active' : 'status-inactive';
            $status_text = $is_active ? 'Activo' : 'Inactivo';
            $action_text = $is_active ? 'Desactivar' : 'Activar';
            $action_status = $is_active ? 'deactivate' : 'activate';
            
            echo '<div class="token-item">';
            echo '<div class="token-info">';
            echo '<h3>' . $name . '</h3>';
            echo '<div class="token-code">' . $token . '</div>';
            echo '<div style="font-size: 12px; color: #666; margin-top: 5px;">';
            echo 'Creado: ' . date('d/m/Y H:i', strtotime($created_at)) . ' | ';
            echo 'Expira: ' . date('d/m/Y H:i', strtotime($expires_at));
            echo '</div>';
            echo '</div>';
            echo '<div style="display: flex; align-items: center; gap: 10px;">';
            echo '<span class="token-status ' . $status_class . '">' . $status_text . '</span>';
            echo '<a href="index.php?action=toggleStatus&id=' . $id . '&status=' . $action_status . '" class="btn ' . ($is_active ? 'btn-danger' : 'btn-primary') . '" style="padding: 5px 10px; font-size: 12px;">' . $action_text . '</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="token-item">';
        echo '<p>No se encontraron tokens.</p>';
        echo '</div>';
    }
    ?>
</div>

<div class="hotels-section">
    <div class="section-header">
        <h2 class="section-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21Z" stroke="currentColor" stroke-width="2"/>
                <path d="M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M7 11H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M7 15H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Hoteles con Tokens Generados
        </h2>
        <div class="section-stats">
            <span class="stat-badge">
                Total: <?php 
                    $database = new Database();
                    $db = $database->getConnection();
                    $tokenModel = new TokenApi($db);
                    $allTokens = $tokenModel->readAll();
                    echo $allTokens->rowCount(); 
                ?>
            </span>
        </div>
    </div>

    <div class="hotels-grid">
        <?php
        if ($allTokens->rowCount() > 0) {
            while ($row = $allTokens->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $status_class = $is_active ? 'status-active' : 'status-inactive';
                $status_icon = $is_active ? '🟢' : '🔴';
                $days_remaining = floor((strtotime($expires_at) - time()) / (60 * 60 * 24));
                
                echo '<div class="hotel-card ' . $status_class . '">';
                echo '<div class="hotel-header">';
                echo '<div class="hotel-name">';
                echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                echo '<path d="M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21Z" stroke="currentColor" stroke-width="2"/>';
                echo '<path d="M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
                echo '<path d="M7 11H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
                echo '<path d="M7 15H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
                echo '</svg>';
                echo '<h3>' . htmlspecialchars($name) . '</h3>';
                echo '</div>';
                echo '<div class="status-indicator ' . $status_class . '">';
                echo '<span class="status-dot"></span>';
                echo ($is_active ? 'Activo' : 'Inactivo');
                echo '</div>';
                echo '</div>';
                
                echo '<div class="token-section">';
                echo '<label>Token API:</label>';
                echo '<div class="token-display">';
                echo '<code class="token-code">' . htmlspecialchars($token) . '</code>';
                echo '<button class="copy-btn" onclick="copyToClipboard(\'' . htmlspecialchars($token) . '\')">';
                echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                echo '<rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>';
                echo '<path d="M5 15H4C2.89543 15 2 14.1046 2 13V4C2 2.89543 2.89543 2 4 2H13C14.1046 2 15 2.89543 15 4V5" stroke="currentColor" stroke-width="2"/>';
                echo '</svg>';
                echo '</button>';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="hotel-meta">';
                echo '<div class="meta-item">';
                echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                echo '<path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
                echo '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>';
                echo '</svg>';
                echo 'Creado: ' . date('d/m/Y', strtotime($created_at));
                echo '</div>';
                
                echo '<div class="meta-item">';
                echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                echo '<path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
                echo '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>';
                echo '</svg>';
                echo 'Expira: ' . date('d/m/Y', strtotime($expires_at));
                echo '</div>';
                
                if ($is_active) {
                    echo '<div class="meta-item days-remaining">';
                    echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                    echo '<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>';
                    echo '<path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
                    echo '</svg>';
                    echo $days_remaining > 0 ? $days_remaining . ' días restantes' : 'Expirado';
                    echo '</div>';
                }
                echo '</div>';
                
                echo '</div>';
            }
        } else {
            echo '<div class="empty-state">';
            echo '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
            echo '<path d="M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21Z" stroke="currentColor" stroke-width="2"/>';
            echo '<path d="M9 9H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
            echo '<path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
            echo '<path d="M9 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
            echo '</svg>';
            echo '<h3>No hay hoteles registrados</h3>';
            echo '<p>Comienza generando tu primer token para un hotel</p>';
            echo '<a href="index.php?action=create" class="btn btn-primary">Generar Primer Token</a>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<style>
/* Sección de Hoteles */
.hotels-section {
    margin-top: 50px;
    padding: 30px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 20px;
    border: 1px solid #e2e8f0;
}

.section-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #1e293b;
    font-size: 24px;
    font-weight: 700;
    margin: 0;
}

.section-title svg {
    color: #667eea;
}

.section-stats {
    display: flex;
    gap: 10px;
}

.stat-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Grid de Hoteles */
.hotels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

/* Tarjetas de Hotel */
.hotel-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.hotel-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hotel-card.status-inactive::before {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
}

.hotel-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

/* Header de la tarjeta */
.hotel-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.hotel-name {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.hotel-name svg {
    color: #667eea;
    flex-shrink: 0;
}

.hotel-name h3 {
    margin: 0;
    color: #1e293b;
    font-size: 18px;
    font-weight: 600;
    line-height: 1.4;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-indicator.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-indicator.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.status-active .status-dot {
    background: #16a34a;
}

.status-inactive .status-dot {
    background: #dc2626;
}

/* Sección del Token */
.token-section {
    margin-bottom: 20px;
}

.token-section label {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.token-display {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    position: relative;
}

.token-code {
    flex: 1;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 13px;
    color: #475569;
    word-break: break-all;
    margin: 0;
}

.copy-btn {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px;
    cursor: pointer;
    color: #64748b;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.copy-btn:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

/* Metadatos del Hotel */
.hotel-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.meta-item svg {
    color: #94a3b8;
    flex-shrink: 0;
}

.days-remaining {
    color: #16a34a;
    font-weight: 600;
}

.days-remaining svg {
    color: #16a34a;
}

/* Estado vacío */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.empty-state svg {
    color: #cbd5e1;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #475569;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 8px;
}

.empty-state p {
    color: #64748b;
    margin-bottom: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .hotels-section {
        padding: 20px;
        margin: 30px 0;
    }
    
    .hotels-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .hotel-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .status-indicator {
        align-self: flex-start;
    }
}

@media (max-width: 480px) {
    .hotels-section {
        padding: 15px;
    }
    
    .hotel-card {
        padding: 20px;
    }
    
    .token-display {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    
    .copy-btn {
        align-self: flex-end;
    }
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Mostrar feedback visual
        const buttons = document.querySelectorAll('.copy-btn');
        buttons.forEach(btn => {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            `;
            btn.style.background = '#10b981';
            btn.style.borderColor = '#10b981';
            btn.style.color = 'white';
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.background = '';
                btn.style.borderColor = '';
                btn.style.color = '';
            }, 2000);
        });
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        alert('Error al copiar el token');
    });
}
</script>

