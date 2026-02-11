// ===================================
// CONFIGURACIÓN
// ===================================
// Cambia esta URL según tu entorno
const BASE_URL = 'http://spacecraft.local/';
// Para desarrollo local puedes usar:
// const BASE_URL = 'http://localhost/spacecraft/';
// const BASE_URL = 'http://localhost:8000/';

// ===================================
// FUNCIONES
// ===================================

// Generar estrellas
function createStars() {
    const starsContainer = document.getElementById('stars');
    const numberOfStars = 200;

    for (let i = 0; i < numberOfStars; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        
        const size = Math.random() * 3;
        star.style.width = size + 'px';
        star.style.height = size + 'px';
        star.style.left = Math.random() * 100 + '%';
        star.style.top = Math.random() * 100 + '%';
        star.style.animationDelay = Math.random() * 3 + 's';
        
        starsContainer.appendChild(star);
    }
}

// Cambiar entre pestañas de autenticación
function switchAuthTab(tab) {
    const tabs = document.querySelectorAll('.tab');
    const forms = document.querySelectorAll('.form-container');
    
    tabs.forEach(t => t.classList.remove('active'));
    forms.forEach(f => f.classList.remove('active'));
    
    if (tab === 'password') {
        tabs[0].classList.add('active');
        document.getElementById('passwordForm').classList.add('active');
    } else if (tab === 'magiclink') {
        tabs[1].classList.add('active');
        document.getElementById('magiclinkForm').classList.add('active');
    }

    hideMessages();
}

// Mostrar formulario de registro
function showRegisterForm() {
    const forms = document.querySelectorAll('.form-container');
    const tabs = document.querySelectorAll('.tab');
    
    forms.forEach(f => f.classList.remove('active'));
    tabs.forEach(t => t.classList.remove('active'));
    
    document.getElementById('registerForm').classList.add('active');
    hideMessages();
}

// Mostrar formulario de login
function showLoginForm() {
    const forms = document.querySelectorAll('.form-container');
    const tabs = document.querySelectorAll('.tab');
    
    forms.forEach(f => f.classList.remove('active'));
    tabs[0].classList.add('active');
    
    document.getElementById('passwordForm').classList.add('active');
    hideMessages();
}

// Toggle password visibility
function togglePasswordVisibility(targetId) {
    const input = document.getElementById(targetId);
    const button = document.querySelector(`[data-target="${targetId}"]`);
    
    if (input.type === 'password') {
        input.type = 'text';
        button.querySelector('.eye-icon').textContent = '👁️‍🗨️';
    } else {
        input.type = 'password';
        button.querySelector('.eye-icon').textContent = '👁️';
    }
}

// Validar fuerza de contraseña
function checkPasswordStrength(password) {
    const strengthBar = document.querySelector('#password-strength .strength-bar');
    const strengthContainer = document.getElementById('password-strength');
    
    if (!password) {
        strengthContainer.classList.remove('show');
        return;
    }
    
    strengthContainer.classList.add('show');
    
    let strength = 0;
    
    // Criterios de fuerza
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    // Remover clases previas
    strengthBar.classList.remove('weak', 'medium', 'strong');
    
    // Aplicar clase según fuerza
    if (strength <= 2) {
        strengthBar.classList.add('weak');
    } else if (strength === 3) {
        strengthBar.classList.add('medium');
    } else {
        strengthBar.classList.add('strong');
    }
}

// Mostrar mensaje de éxito
function showSuccess(message) {
    const successDiv = document.getElementById('successMessage');
    const errorDiv = document.getElementById('errorMessage');
    
    errorDiv.classList.remove('show');
    successDiv.textContent = message;
    successDiv.classList.add('show');
    
    setTimeout(() => {
        successDiv.classList.remove('show');
    }, 5000);
}

// Mostrar mensaje de error
function showError(message) {
    const successDiv = document.getElementById('successMessage');
    const errorDiv = document.getElementById('errorMessage');
    
    successDiv.classList.remove('show');
    errorDiv.textContent = message;
    errorDiv.classList.add('show');
    
    setTimeout(() => {
        errorDiv.classList.remove('show');
    }, 5000);
}

// Ocultar mensajes
function hideMessages() {
    document.getElementById('successMessage').classList.remove('show');
    document.getElementById('errorMessage').classList.remove('show');
}

// Manejar login con contraseña
function handlePasswordLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('password-email').value;
    const password = document.getElementById('password-input').value;
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Deshabilitar botón
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Iniciando sesión...';
    
    // Preparar datos para enviar
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    
    // Llamada fetch al backend
    fetch(BASE_URL + 'users/login', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(`🎉 ¡Bienvenido de vuelta, ${data.user.name || email}! Preparando tu nave espacial...`);
            
            // Redirigir al dashboard después de 1.5 segundos
            setTimeout(() => {
                window.location.href = data.redirect_url || 'dashboard';
            }, 1500);
        } else {
            showError(data.message || '❌ Credenciales incorrectas. Por favor verifica tu email y contraseña.');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('❌ Error de conexión. Por favor intenta nuevamente.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}

// Manejar envío de enlace mágico
function handleMagicLink(event) {
    event.preventDefault();
    
    const email = document.getElementById('magic-email').value;
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const originalText = btnText.textContent;
    
    // Deshabilitar botón
    submitBtn.disabled = true;
    btnText.textContent = 'Enviando...';
    
    // Preparar datos para enviar
    const formData = new FormData();
    formData.append('email', email);
    
    // Llamada fetch al backend
    fetch(BASE_URL + 'users/send_magic_link', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        btnText.textContent = originalText;
        
        if (data.success) {
            showSuccess(data.message || `✉️ ¡Listo! Te hemos enviado un enlace mágico a ${email}. Revisa tu bandeja de entrada.`);
            
            // Limpiar formulario
            event.target.reset();
        } else {
            showError(data.message || '❌ Error al enviar el enlace. Por favor intenta nuevamente.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.disabled = false;
        btnText.textContent = originalText;
        showError('❌ Error de conexión. Por favor intenta nuevamente.');
    });
}

// Manejar registro
function handleRegister(event) {
    event.preventDefault();
    
    const name = document.getElementById('register-name').value;
    const email = document.getElementById('register-email').value;
    const password = document.getElementById('register-password').value;
    const confirm = document.getElementById('register-confirm').value;
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Validar contraseñas coincidan
    if (password !== confirm) {
        showError('❌ Las contraseñas no coinciden');
        return;
    }
        
    // Validar fuerza de contraseña
    if (password.length < 8) {
        showError('❌ La contraseña debe tener al menos 8 caracteres');
        return;
    }
    
    // Deshabilitar botón
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Creando cuenta...';
    
    // Preparar datos para enviar
    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('confirm_password', confirm);
    
    // Llamada fetch al backend
    fetch(BASE_URL + 'users/register', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message || ` ¡Bienvenido a bordo, ${name}! Tu cuenta ha sido creada exitosamente.`);
            
            // Cambiar a login después de 2 segundos
            setTimeout(() => {
                showLoginForm();
                
                // Pre-llenar el email en el login
                document.getElementById('password-email').value = email;
            }, 2000);
        } else {
            showError(data.message || ' Error al crear la cuenta. Por favor intenta nuevamente.');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error de conexión. Por favor intenta nuevamente.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}

// Inicializar eventos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Crear estrellas
    createStars();

    // Event listeners para las pestañas de autenticación
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabType = this.getAttribute('data-tab');
            switchAuthTab(tabType);
        });
    });

    // Event listeners para cambiar entre métodos de autenticación
    const switchLinks = document.querySelectorAll('[data-switch]');
    switchLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetTab = this.getAttribute('data-switch');
            switchAuthTab(targetTab);
        });
    });

    // Event listener para mostrar registro
    const showRegisterBtn = document.getElementById('show-register');
    if (showRegisterBtn) {
        showRegisterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showRegisterForm();
        });
    }

    // Event listener para mostrar login
    const showLoginBtn = document.getElementById('show-login');
    if (showLoginBtn) {
        showLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginForm();
        });
    }

    // Event listener para el formulario de login con contraseña
    const passwordLoginForm = document.getElementById('password-login-form');
    if (passwordLoginForm) {
        passwordLoginForm.addEventListener('submit', handlePasswordLogin);
    }

    // Event listener para el formulario de enlace mágico
    const magicLinkForm = document.getElementById('magic-link-form');
    if (magicLinkForm) {
        magicLinkForm.addEventListener('submit', handleMagicLink);
    }

    // Event listener para el formulario de registro
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }

    // Event listeners para botones de toggle password
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            togglePasswordVisibility(targetId);
        });
    });

    // Event listener para verificar fuerza de contraseña
    const registerPasswordInput = document.getElementById('register-password');
    if (registerPasswordInput) {
        registerPasswordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }

    // Event listeners para botones de login social (todos)
    const socialButtons = document.querySelectorAll('.social-btn');
    socialButtons.forEach(button => {
        button.addEventListener('click', function() {
            const provider = this.getAttribute('data-provider');
            handleSocialLogin(provider);
        });
    });
});
