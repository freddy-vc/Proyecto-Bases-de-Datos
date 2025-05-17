/**
 * JavaScript para la administración de usuarios
 */
document.addEventListener('DOMContentLoaded', function() {
    // Cargar usuarios
    cargarUsuarios();
    
    // Configurar eventos para modales
    configurarModales();
    
    // Configurar evento para agregar usuario
    document.getElementById('add-usuario-btn').addEventListener('click', function() {
        document.getElementById('modal-title').textContent = 'Agregar Usuario';
        document.getElementById('usuario-form').reset();
        document.getElementById('usuario-id').value = '';
        document.getElementById('password-hint').style.display = 'none';
        document.getElementById('usuario-modal').style.display = 'block';
    });
});

/**
 * Carga la lista de usuarios desde el backend
 */
function cargarUsuarios() {
    const tableBody = document.getElementById('usuarios-table-body');
    
    fetchAPI('../../backend/api/usuarios.php')
        .then(data => {
            if (data.status === 'success') {
                mostrarUsuarios(data.data);
            } else {
                tableBody.innerHTML = `<tr><td colspan="6" class="error-message">${data.message || 'Error al cargar usuarios'}</td></tr>`;
            }
        })
        .catch(error => {
            console.error('Error al cargar usuarios:', error);
            tableBody.innerHTML = '<tr><td colspan="6" class="error-message">Error de conexión. Por favor, intente nuevamente.</td></tr>';
        });
}

/**
 * Muestra los usuarios en la tabla
 * 
 * @param {Array} usuarios Lista de usuarios a mostrar
 */
function mostrarUsuarios(usuarios) {
    const tableBody = document.getElementById('usuarios-table-body');
    
    if (!usuarios || usuarios.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6">No hay usuarios registrados</td></tr>';
        return;
    }
    
    let html = '';
    
    usuarios.forEach(usuario => {
        html += `
        <tr>
            <td><img src="${usuario.avatar || '../../assets/img/default-avatar.png'}" alt="Avatar" class="user-avatar"></td>
            <td>${usuario.username}</td>
            <td>${usuario.email}</td>
            <td>${usuario.rol === 'admin' ? 'Administrador' : 'Usuario'}</td>
            <td><span class="estado-badge estado-${usuario.estado}">${usuario.estado}</span></td>
            <td>
                <button class="btn-action btn-edit" data-id="${usuario.id}">Editar</button>
                <button class="btn-action btn-delete" data-id="${usuario.id}">Eliminar</button>
            </td>
        </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    
    // Configurar eventos para botones de editar y eliminar
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            editarUsuario(this.getAttribute('data-id'));
        });
    });
    
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            confirmarEliminar(this.getAttribute('data-id'));
        });
    });
}

/**
 * Configura los eventos para los modales
 */
function configurarModales() {
    // Cerrar modales al hacer clic en la X
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
    
    // Cerrar modales al hacer clic fuera de ellos
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
    
    // Configurar formulario de usuario
    document.getElementById('usuario-form').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarUsuario();
    });
    
    // Configurar botones del modal de confirmación
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        eliminarUsuario(this.getAttribute('data-id'));
    });
    
    document.getElementById('cancel-delete-btn').addEventListener('click', function() {
        document.getElementById('confirm-modal').style.display = 'none';
    });
}

/**
 * Prepara el formulario para editar un usuario
 * 
 * @param {string} id ID del usuario a editar
 */
function editarUsuario(id) {
    fetchAPI(`../../backend/api/usuarios.php?id=${id}`)
        .then(data => {
            if (data.status === 'success') {
                const usuario = data.data;
                
                document.getElementById('modal-title').textContent = 'Editar Usuario';
                document.getElementById('usuario-id').value = usuario.id;
                document.getElementById('username').value = usuario.username;
                document.getElementById('email').value = usuario.email;
                document.getElementById('password').value = '';
                document.getElementById('password-hint').style.display = 'block';
                document.getElementById('rol').value = usuario.rol;
                document.getElementById('estado').value = usuario.estado;
                
                document.getElementById('usuario-modal').style.display = 'block';
            } else {
                mostrarNotificacion(data.message || 'Error al cargar datos del usuario', 'error');
            }
        })
        .catch(error => {
            console.error('Error al cargar datos del usuario:', error);
            mostrarNotificacion('Error de conexión. Por favor, intente nuevamente.', 'error');
        });
}

/**
 * Guarda los datos del usuario (crear o actualizar)
 */
function guardarUsuario() {
    const usuarioId = document.getElementById('usuario-id').value;
    const formData = {
        username: document.getElementById('username').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        rol: document.getElementById('rol').value,
        estado: document.getElementById('estado').value
    };
    
    // Si la contraseña está vacía y estamos editando, eliminarla del objeto
    if (!formData.password && usuarioId) {
        delete formData.password;
    }
    
    const method = usuarioId ? 'PUT' : 'POST';
    const url = usuarioId 
        ? `../../backend/api/usuarios.php?id=${usuarioId}`
        : '../../backend/api/usuarios.php';
    
    fetchAPI(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('usuario-modal').style.display = 'none';
                mostrarNotificacion(data.message || 'Usuario guardado correctamente', 'success');
                cargarUsuarios();
            } else {
                document.getElementById('error-message').textContent = data.message || 'Error al guardar usuario';
                document.getElementById('error-message').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error al guardar usuario:', error);
            document.getElementById('error-message').textContent = 'Error de conexión. Por favor, intente nuevamente.';
            document.getElementById('error-message').style.display = 'block';
        });
}

/**
 * Muestra el modal de confirmación para eliminar un usuario
 * 
 * @param {string} id ID del usuario a eliminar
 */
function confirmarEliminar(id) {
    document.getElementById('confirm-delete-btn').setAttribute('data-id', id);
    document.getElementById('confirm-modal').style.display = 'block';
}

/**
 * Elimina un usuario
 * 
 * @param {string} id ID del usuario a eliminar
 */
function eliminarUsuario(id) {
    fetchAPI(`../../backend/api/usuarios.php?id=${id}`, {
        method: 'DELETE'
    })
        .then(data => {
            document.getElementById('confirm-modal').style.display = 'none';
            
            if (data.status === 'success') {
                mostrarNotificacion(data.message || 'Usuario eliminado correctamente', 'success');
                cargarUsuarios();
            } else {
                mostrarNotificacion(data.message || 'Error al eliminar usuario', 'error');
            }
        })
        .catch(error => {
            document.getElementById('confirm-modal').style.display = 'none';
            console.error('Error al eliminar usuario:', error);
            mostrarNotificacion('Error de conexión. Por favor, intente nuevamente.', 'error');
        });
} 