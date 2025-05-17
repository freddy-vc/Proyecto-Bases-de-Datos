document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el usuario está autenticado y es administrador
    const userStr = localStorage.getItem('user');
    if (!userStr) {
        window.location.href = '../login.html';
        return;
    }
    
    const user = JSON.parse(userStr);
    if (user.rol !== 'admin') {
        window.location.href = '../../index.html';
        return;
    }
    
    // Mostrar información del administrador
    document.getElementById('admin-username').textContent = user.username;
    if (user.foto_perfil) {
        document.getElementById('admin-avatar').src = 'data:image/jpeg;base64,' + user.foto_perfil;
    }
    
    // Elementos del DOM
    const usuariosTableBody = document.getElementById('usuarios-table-body');
    const addUsuarioBtn = document.getElementById('add-usuario-btn');
    const usuarioModal = document.getElementById('usuario-modal');
    const closeModal = document.querySelector('.close');
    const usuarioForm = document.getElementById('usuario-form');
    const modalTitle = document.getElementById('modal-title');
    const usuarioId = document.getElementById('usuario-id');
    const passwordGroup = document.getElementById('password-group');
    const errorMessage = document.getElementById('error-message');
    const logoutBtn = document.getElementById('logout-btn');
    
    // Cargar usuarios
    loadUsuarios();
    
    // Evento para agregar usuario
    addUsuarioBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Agregar Usuario';
        usuarioForm.reset();
        usuarioId.value = '';
        passwordGroup.style.display = 'block';
        document.getElementById('password').required = true;
        usuarioModal.style.display = 'block';
    });
    
    // Evento para cerrar modal
    closeModal.addEventListener('click', function() {
        usuarioModal.style.display = 'none';
    });
    
    // Evento para cerrar modal al hacer clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === usuarioModal) {
            usuarioModal.style.display = 'none';
        }
    });
    
    // Evento para enviar formulario
    usuarioForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const rol = document.getElementById('rol').value;
        const id = usuarioId.value;
        
        if (id) {
            // Actualizar usuario
            updateUsuario(id, username, email, rol, password);
        } else {
            // Crear usuario
            createUsuario(username, email, password, rol);
        }
    });
    
    // Evento para cerrar sesión
    logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        localStorage.removeItem('user');
        window.location.href = '../login.html';
    });
    
    // Función para cargar usuarios
    function loadUsuarios() {
        fetch('../../backend/api/usuarios.php?action=getAll', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayUsuarios(data.data);
            } else {
                console.error('Error al cargar usuarios:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Función para mostrar usuarios en la tabla
    function displayUsuarios(usuarios) {
        usuariosTableBody.innerHTML = '';
        
        usuarios.forEach(usuario => {
            const row = document.createElement('tr');
            
            const avatarImg = usuario.foto_perfil 
                ? `<img src="data:image/jpeg;base64,${usuario.foto_perfil}" class="user-avatar" alt="Avatar">` 
                : `<img src="../../assets/img/default-avatar.svg" class="user-avatar" alt="Avatar">`;
            
            row.innerHTML = `
                <td>${usuario.cod_user}</td>
                <td>${avatarImg}</td>
                <td>${usuario.username}</td>
                <td>${usuario.email}</td>
                <td>${usuario.rol}</td>
                <td>
                    <button class="btn-action btn-edit" data-id="${usuario.cod_user}">Editar</button>
                    <button class="btn-action btn-delete" data-id="${usuario.cod_user}">Eliminar</button>
                </td>
            `;
            
            usuariosTableBody.appendChild(row);
        });
        
        // Agregar eventos a los botones de editar y eliminar
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editUsuario(id);
            });
        });
        
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('¿Está seguro de eliminar este usuario?')) {
                    deleteUsuario(id);
                }
            });
        });
    }
    
    // Función para editar usuario
    function editUsuario(id) {
        fetch(`../../backend/api/usuarios.php?action=getById&id=${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const usuario = data.data;
                
                modalTitle.textContent = 'Editar Usuario';
                document.getElementById('username').value = usuario.username;
                document.getElementById('email').value = usuario.email;
                document.getElementById('rol').value = usuario.rol;
                usuarioId.value = usuario.cod_user;
                
                // Ocultar campo de contraseña en edición
                passwordGroup.style.display = 'none';
                document.getElementById('password').required = false;
                
                usuarioModal.style.display = 'block';
            } else {
                console.error('Error al obtener usuario:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Función para crear usuario
    function createUsuario(username, email, password, rol) {
        fetch('../../backend/api/usuarios.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                email: email,
                password: password,
                rol: rol
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                usuarioModal.style.display = 'none';
                loadUsuarios();
            } else {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMessage.textContent = 'Error al procesar la solicitud';
            errorMessage.style.display = 'block';
        });
    }
    
    // Función para actualizar usuario
    function updateUsuario(id, username, email, rol, password) {
        const userData = {
            username: username,
            email: email,
            rol: rol
        };
        
        // Si se proporcionó una contraseña, incluirla
        if (password) {
            userData.password = password;
        }
        
        fetch(`../../backend/api/usuarios.php?action=update&id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                usuarioModal.style.display = 'none';
                loadUsuarios();
            } else {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMessage.textContent = 'Error al procesar la solicitud';
            errorMessage.style.display = 'block';
        });
    }
    
    // Función para eliminar usuario
    function deleteUsuario(id) {
        fetch(`../../backend/api/usuarios.php?action=delete&id=${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                loadUsuarios();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    }
});