<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Espacial - Login</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo(rand());?>">
</head>
<body>
    <div class="space-background">
        <div class="stars" id="stars"></div>
        <div class="planet planet-1"></div>
        <div class="planet planet-2"></div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="logo">🚀</div>
                <h2>Portal Espacial</h2>
            </div>

            <!-- Pestañas de método de autenticación -->
            <div class="tabs">
                <button class="tab active" data-tab="password">Con Contraseña</button>
                <button class="tab" data-tab="magiclink">Enlace Mágico</button>
            </div>

            <!-- Formulario de Login con Contraseña -->
            <div id="passwordForm" class="form-container active">
                <form id="password-login-form">
                    <div class="form-group">
                        <label for="password-email">Correo Electrónico</label>
                        <input type="email" id="password-email" name="email" placeholder="astronauta@espacio.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password-input">Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" id="password-input" name="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" data-target="password-input">
                                <span class="eye-icon">👁️</span>
                            </button>
                        </div>
                    </div>
                    <div class="remember-forgot">
                        <div class="forgot-password">
                            <a href="#" data-switch="magiclink">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn">🚀 Iniciar Sesión</button>
                </form>
                <div class="register-link">
                    ¿No tienes cuenta? <a href="#" id="show-register">Regístrate aquí</a>
                </div>
            </div>

            <!-- Formulario de Magic Link -->
            <div id="magiclinkForm" class="form-container">
                <div class="magic-info">
                    <div class="magic-icon">✉️</div>
                    <h3>Acceso sin contraseña</h3>
                    <p>Te enviaremos un enlace mágico a tu correo para acceder sin necesidad de contraseña</p>
                </div>

                <form id="magic-link-form">
                    <div class="form-group">
                        <label for="magic-email">Correo Electrónico</label>
                        <input type="email" id="magic-email" name="email" placeholder="astronauta@espacio.com" required>
                    </div>
                    <button type="submit" class="btn btn-magic">
                        <span class="btn-icon">✨</span>
                        <span class="btn-text">Enviar Enlace Mágico</span>
                    </button>
                </form>

                <div class="magic-benefits">
                    <div class="benefit-item">
                        <span class="benefit-icon">🔒</span>
                        <span class="benefit-text">Más seguro</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">⚡</span>
                        <span class="benefit-text">Más rápido</span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">🎯</span>
                        <span class="benefit-text">Sin memorizar</span>
                    </div>
                </div>

                <div class="back-to-password">
                    <a href="#" data-switch="password">← Volver a login con contraseña</a>
                </div>
            </div>

            <!-- Formulario de Registro -->
            <div id="registerForm" class="form-container">
                <div class="register-header">
                    <h3>Crear Cuenta</h3>
                    <p>Únete a nuestra comunidad espacial</p>
                </div>

                <form id="register-form">
                    <div class="form-group">
                        <label for="register-name">Nombre Completo</label>
                        <input type="text" id="register-name" name="name" placeholder="Neil Armstrong" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">Correo Electrónico</label>
                        <input type="email" id="register-email" name="email" placeholder="astronauta@espacio.com" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" id="register-password" name="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" data-target="register-password">
                                <span class="eye-icon">👁️</span>
                            </button>
                        </div>
                        <div class="password-strength" id="password-strength">
                            <div class="strength-bar"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="register-confirm">Confirmar Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" id="register-confirm" name="confirm_password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" data-target="register-confirm">
                                <span class="eye-icon">👁️</span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn">🌟 Crear Cuenta</button>
                </form>
                <div class="register-link">
                    ¿Ya tienes cuenta? <a href="#" id="show-login">Inicia sesión aquí</a>
                </div>
            </div>

            <!-- Mensajes de feedback -->
            <div id="successMessage" class="success-message"></div>
            <div id="errorMessage" class="error-message"></div>
        </div>
    </div>

    <script src="assets/js/login.js?v=<?php echo(rand());?>"></script>
</body>
</html>