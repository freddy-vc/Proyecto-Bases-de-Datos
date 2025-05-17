document.addEventListener('DOMContentLoaded', function() {
    const registroForm = document.getElementById('registro-form');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');
    
    registroForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        
        // Validar que las contraseñas coincidan
        if (password !== confirmPassword) {
            errorMessage.textContent = 'Las contraseñas no coinciden';
            errorMessage.style.display = 'block';
            return;
        }
        
        // Realizar la solicitud de registro
        fetch('../../backend/api/usuarios.php?action=register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                email: email,
                password: password
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Respuesta no válida del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                successMessage.textContent = 'Registro exitoso. Ahora puedes iniciar sesión.';
                successMessage.style.display = 'block';
                errorMessage.style.display = 'none';
                registroForm.reset();
            } else {
                errorMessage.textContent = data.message || 'Error en el registro.';
                errorMessage.style.display = 'block';
                successMessage.style.display = 'none';
            }
        })
        .catch(error => {
            errorMessage.textContent = 'Error de conexión con el servidor.';
            errorMessage.style.display = 'block';
            successMessage.style.display = 'none';
        });
    });
});