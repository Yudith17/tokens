<?php include '../header.php'; ?>

<div class="main-container">
    <?php include '../sidebar.php'; ?>
    
    <main class="main-content">
        <div class="content-section">
            <h2>Gestión de Tokens API</h2>
            
            <div class="token-actions">
                <button onclick="generateToken()" class="btn btn-primary">Generar Nuevo Token</button>
                <button onclick="loadMyTokens()" class="btn btn-secondary">Mis Tokens</button>
            </div>

            <div id="token-result" style="margin-top: 1rem;"></div>
            
            <div id="tokens-list" class="results-table" style="margin-top: 2rem;">
                <!-- Lista de tokens se cargará aquí -->
            </div>
        </div>
    </main>
</div>

<script>
async function generateToken() {
    const dias = prompt('¿Por cuántos días será válido el token?', '30');
    if (!dias) return;

    try {
        const formData = new FormData();
        formData.append('dias', dias);

        const response = await fetch('../controllers/AuthController.php?action=generate_api_token', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('token-result').innerHTML = `
                <div class="success-message">
                    <strong>Token generado exitosamente:</strong><br>
                    <code style="background: #f4f4f4; padding: 10px; display: block; margin: 10px 0;">
                        ${result.token}
                    </code>
                    <strong>Expira:</strong> ${result.expiracion}<br>
                    <small>Guarda este token en un lugar seguro, no podrás verlo nuevamente.</small>
                </div>
            `;
            loadMyTokens(); // Recargar lista
        } else {
            document.getElementById('token-result').innerHTML = `
                <div class="error-message">${result.message}</div>
            `;
        }
    } catch (error) {
        document.getElementById('token-result').innerHTML = `
            <div class="error-message">Error: ${error.message}</div>
        `;
    }
}

async function loadMyTokens() {
    try {
        const response = await fetch('../controllers/AuthController.php?action=get_my_tokens');
        const result = await response.json();

        if (result.success) {
            const tokensList = document.getElementById('tokens-list');
            
            if (result.tokens.length === 0) {
                tokensList.innerHTML = '<div class="no-results">No tienes tokens generados</div>';
                return;
            }

            let html = '<h3>Mis Tokens</h3><table><thead><tr><th>Token</th><th>Expiración</th><th>Estado</th><th>Creado</th><th>Acciones</th></tr></thead><tbody>';
            
            result.tokens.forEach(token => {
                const estado = token.activo ? 
                    '<span style="color: green;">Activo</span>' : 
                    '<span style="color: red;">Inactivo</span>';
                
                const tokenDisplay = token.token.substring(0, 10) + '...';
                const acciones = token.activo ? 
                    `<button onclick="revokeToken('${token.token}')" class="btn btn-danger btn-sm">Revocar</button>` : 
                    'Revocado';
                
                html += `
                    <tr>
                        <td><code>${tokenDisplay}</code></td>
                        <td>${token.expiracion}</td>
                        <td>${estado}</td>
                        <td>${token.created_at}</td>
                        <td>${acciones}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            tokensList.innerHTML = html;
        }
    } catch (error) {
        console.error('Error cargando tokens:', error);
    }
}

async function revokeToken(token) {
    if (!confirm('¿Estás seguro de que quieres revocar este token?')) return;

    try {
        const formData = new FormData();
        formData.append('token', token);

        const response = await fetch('../controllers/AuthController.php?action=revoke_token', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert('Token revocado exitosamente');
            loadMyTokens(); // Recargar lista
        } else {
            alert('Error revocando token: ' + result.message);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Cargar tokens al abrir la página
document.addEventListener('DOMContentLoaded', loadMyTokens);
</script>

</body>
</html>