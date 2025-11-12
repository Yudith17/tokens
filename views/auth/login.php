
<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-circle">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M19.4 15C19.2669 15.3044 19.1337 15.6088 19.0006 15.9131C18.5298 16.9023 18.0589 17.8915 17.5881 18.8807C17.2818 19.5479 16.9755 20.2151 16.6692 20.8823C16.5075 21.2319 16.2682 21.5 15.8974 21.5H8.10258C7.73183 21.5 7.49254 21.2319 7.33082 20.8823C7.02447 20.2151 6.71817 19.5479 6.41187 18.8807C5.94106 17.8915 5.47025 16.9023 4.99944 15.9131C4.86634 15.6088 4.73314 15.3044 4.60004 15C4.73314 14.6956 4.86634 14.3912 4.99944 14.0869C5.47025 13.0977 5.94106 12.1085 6.41187 11.1193C6.71817 10.4521 7.02447 9.7849 7.33082 9.1177C7.49254 8.76806 7.73183 8.5 8.10258 8.5H15.8974C16.2682 8.5 16.5075 8.76806 16.6692 9.1177C16.9755 9.7849 17.2818 10.4521 17.5881 11.1193C18.0589 12.1085 18.5298 13.0977 19.0006 14.0869C19.1337 14.3912 19.2669 14.6956 19.4 15Z" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
            <h2>Bienvenido</h2>
            <p>Ingresa a tu cuenta de gestión de tokens</p>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="index.php?action=processLogin" method="POST" class="login-form">
            <div class="form-group animated-input">
                <input type="text" id="username" name="username" class="form-control" required>
                <label for="username">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Usuario
                </label>
            </div>
            
            <div class="form-group animated-input">
                <input type="password" id="password" name="password" class="form-control" required>
                <label for="password">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="16" r="1" fill="currentColor"/>
                        <path d="M7 11V7C7 4.23858 9.23858 2 12 2C14.7614 2 17 4.23858 17 7V11" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Contraseña
                </label>
            </div>

            <button type="submit" class="login-btn">
                <span>Iniciar Sesión</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </form>

        <div class="login-footer">
            <div class="support-info">
                <p>¿Problemas para acceder? <a href="#" class="support-link">Contactar soporte</a></p>
            </div>
        </div>
    </div>

    <div class="login-background">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
    </div>
</div>

<style>
/* Reset y estilos base */
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Tarjeta de login */
.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 40px;
    width: 100%;
    max-width: 440px;
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 2;
    animation: slideUp 0.6s ease-out;
}

/* Encabezado del login */
.login-header {
    text-align: center;
    margin-bottom: 40px;
}

.logo-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.login-header h2 {
    color: #2d3748;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.login-header p {
    color: #718096;
    font-size: 16px;
    margin: 0;
}

/* Formulario */
.login-form {
    margin-bottom: 30px;
}

.form-group.animated-input {
    position: relative;
    margin-bottom: 24px;
}

.animated-input input {
    width: 100%;
    padding: 16px 20px 16px 50px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 16px;
    background: #f8fafc;
    transition: all 0.3s ease;
    outline: none;
}

.animated-input input:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.animated-input input:valid {
    border-color: #48bb78;
}

.animated-input label {
    position: absolute;
    left: 50px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 16px;
    pointer-events: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.animated-input input:focus + label,
.animated-input input:valid + label {
    top: 0;
    left: 16px;
    font-size: 12px;
    color: #667eea;
    background: white;
    padding: 0 8px;
    transform: translateY(-50%);
}

/* Botón de login */
.login-btn {
    width: 100%;
    padding: 16px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}

.login-btn:active {
    transform: translateY(0);
}

/* Footer del login */
.login-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 24px;
}

.support-info {
    text-align: center;
}

.support-info p {
    color: #718096;
    font-size: 14px;
    margin: 0;
}

.support-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.support-link:hover {
    color: #5a6fd8;
    text-decoration: underline;
}

/* Fondo animado */
.login-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.floating-shapes {
    position: relative;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 10%;
    animation-delay: 2s;
}

.shape-3 {
    width: 60px;
    height: 60px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

.shape-4 {
    width: 100px;
 height: 100px;
    top: 20%;
    right: 20%;
    animation-delay: 1s;
}

/* Alertas */
.alert {
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
}

.alert-error {
    background: #fed7d7;
    color: #c53030;
    border: 1px solid #feb2b2;
}

/* Animaciones */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

/* Responsive */
@media (max-width: 480px) {
    .login-card {
        margin: 20px;
        padding: 30px 24px;
    }
    
    .login-header h2 {
        font-size: 24px;
    }
    
    .logo-circle {
        width: 60px;
        height: 60px;
    }
}

/* Mostrar/ocultar contraseña (opcional) */
.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #667eea;
}

.animated-input.password-input {
    position: relative;
}

.animated-input.password-input input {
    padding-right: 50px;
}
</style>

<script>
// Opcional: Agregar funcionalidad para mostrar/ocultar contraseña
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.querySelector('input[type="password"]');
    const passwordGroup = document.querySelector('.animated-input.password-input');
    
    if (passwordInput && !passwordGroup) {
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'password-toggle';
        toggleButton.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
            </svg>
        `;
        
        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Cambiar icono
            if (type === 'text') {
                this.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.5858 10.5858C10.2238 10.9478 10 11.4477 10 12C10 13.1046 10.8954 14 12 14C12.5523 14 13.0522 13.7762 13.4142 13.4142" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16.6818 16.6818C15.4303 17.6328 13.8753 18.1676 12.2055 18.1676C8.66896 18.1676 5.63278 15.9176 4 12.5C4.58814 11.9586 5.21513 11.3898 5.875 10.875" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 4L20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M14.8335 14.8335C14.2613 15.3058 13.5729 15.6676 12.8155 15.6676C10.7333 15.6676 9 13.9343 9 11.8521C9 11.1747 9.18437 10.5374 9.5 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                `;
            } else {
                this.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                `;
            }
        });
        
        passwordInput.parentNode.appendChild(toggleButton);
        passwordInput.parentNode.classList.add('password-input');
    }
});
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>