<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="validation-container">
    <div class="validation-header">
        <h1>
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Validar Token API
        </h1>
        <p>Ingresa un token para verificar su validez y obtener información del hotel asociado</p>
    </div>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="validation-card">
        <form action="index.php?action=processValidate" method="POST" class="validation-form">
            <div class="form-group">
                <label for="token" class="form-label">Token a Validar</label>
                <input type="text" id="token" name="token" class="form-control" 
                       placeholder="Ingresa el token completo aquí..." required
                       value="<?php echo isset($_POST['token']) ? htmlspecialchars($_POST['token']) : ''; ?>">
            </div>
            
            <button type="submit" class="btn btn-primary validation-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Validar Token
            </button>
        </form>
    </div>

    <?php if (isset($_SESSION['validation_result'])): ?>
        <div class="result-card <?php echo $_SESSION['validation_result']['valid'] ? 'valid' : 'invalid'; ?>">
            <div class="result-header">
                <div class="result-icon">
                    <?php if ($_SESSION['validation_result']['valid']): ?>
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    <?php else: ?>
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 14L12 12M12 12L14 10M12 12L10 10M12 12L14 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="result-title">
                    <h3><?php echo $_SESSION['validation_result']['message']; ?></h3>
                    <span class="result-status <?php echo $_SESSION['validation_result']['valid'] ? 'status-valid' : 'status-invalid'; ?>">
                        <?php echo $_SESSION['validation_result']['valid'] ? 'VÁLIDO' : 'INVÁLIDO'; ?>
                    </span>
                </div>
            </div>

            <?php if ($_SESSION['validation_result']['token_data']): ?>
                <div class="token-details">
                    <h4>Información del Hotel</h4>
                    <div class="details-grid">
                        <div class="detail-item">
                            <label>Nombre del Hotel:</label>
                            <span class="detail-value"><?php echo htmlspecialchars($_SESSION['validation_result']['token_data']['name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Estado:</label>
                            <span class="detail-value status-badge <?php echo $_SESSION['validation_result']['token_data']['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $_SESSION['validation_result']['token_data']['is_active'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Fecha de Creación:</label>
                            <span class="detail-value"><?php echo date('d/m/Y H:i', strtotime($_SESSION['validation_result']['token_data']['created_at'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Fecha de Expiración:</label>
                            <span class="detail-value <?php echo strtotime($_SESSION['validation_result']['token_data']['expires_at']) < time() ? 'expired' : ''; ?>">
                                <?php echo date('d/m/Y H:i', strtotime($_SESSION['validation_result']['token_data']['expires_at'])); ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Días Restantes:</label>
                            <span class="detail-value">
                                <?php 
                                $days_remaining = floor((strtotime($_SESSION['validation_result']['token_data']['expires_at']) - time()) / (60 * 60 * 24));
                                echo $days_remaining > 0 ? $days_remaining . ' días' : 'Expirado';
                                ?>
                            </span>
                        </div>
                        <div class="detail-item full-width">
                            <label>Token:</label>
                            <div class="token-display">
                                <code class="token-code"><?php echo htmlspecialchars($_SESSION['validation_result']['token_data']['token']); ?></code>
                                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($_SESSION['validation_result']['token_data']['token']); ?>')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <path d="M5 15H4C2.89543 15 2 14.1046 2 13V4C2 2.89543 2.89543 2 4 2H13C14.1046 2 15 2.89543 15 4V5" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php unset($_SESSION['validation_result']); ?>
    <?php endif; ?>

    <div class="back-section">
        <a href="index.php" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Volver al Dashboard
        </a>
    </div>
</div>

<style>
.validation-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.validation-header {
    text-align: center;
    margin-bottom: 40px;
}

.validation-header h1 {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    color: #1e293b;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
}

.validation-header p {
    color: #64748b;
    font-size: 16px;
    margin: 0;
}

.validation-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    margin-bottom: 30px;
}

.validation-form {
    max-width: 500px;
    margin: 0 auto;
}

.form-label {
    display: block;
    color: #374151;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.validation-btn {
    width: 100%;
    padding: 12px 24px;
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Result Card */
.result-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    margin-bottom: 30px;
}

.result-card.valid {
    border-left: 4px solid #10b981;
}

.result-card.invalid {
    border-left: 4px solid #ef4444;
}

.result-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.result-icon {
    flex-shrink: 0;
}

.result-card.valid .result-icon svg {
    color: #10b981;
}

.result-card.invalid .result-icon svg {
    color: #ef4444;
}

.result-title {
    flex: 1;
}

.result-title h3 {
    margin: 0 0 8px 0;
    color: #1e293b;
    font-size: 20px;
    font-weight: 600;
}

.result-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-valid {
    background: #dcfce7;
    color: #166534;
}

.status-invalid {
    background: #fee2e2;
    color: #991b1b;
}

/* Token Details */
.token-details h4 {
    color: #374151;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-item label {
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #374151;
    font-size: 14px;
    font-weight: 500;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    width: fit-content;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.detail-value.expired {
    color: #ef4444;
    font-weight: 600;
}

/* Token Display */
.token-display {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
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
    border-radius: 6px;
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

/* Back Section */
.back-section {
    text-align: center;
    margin-top: 30px;
}

.back-section .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .validation-container {
        padding: 15px;
    }
    
    .validation-header h1 {
        font-size: 24px;
        flex-direction: column;
        gap: 8px;
    }
    
    .validation-card,
    .result-card {
        padding: 20px;
    }
    
    .result-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
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
