document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Obtener los valores del formulario
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        // Realizar la solicitud de inicio de sesión
        fetch('../../backend/api/usuarios.php?action=login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
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
                localStorage.setItem('user', JSON.stringify(data.data)); // Guarda el usuario
                window.location.href = '../index.html';
            } else {
                document.getElementById('error-message').textContent = data.message || 'Usuario o contraseña incorrectos.';
                document.getElementById('error-message').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('error-message').textContent = 'Error de conexión con el servidor.';
            document.getElementById('error-message').style.display = 'block';
        });
    });
});