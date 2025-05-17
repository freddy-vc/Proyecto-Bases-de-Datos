<?php
/**
 * Controlador para manejar las operaciones relacionadas con Usuarios
 */
class UsuarioController {
    private $db;
    private $usuario;
    
    /**
     * Constructor del controlador
     * @param Database $database Objeto de conexión a la base de datos
     */
    public function __construct($database) {
        $this->db = $database->getConnection();
        require_once '../models/usuario.php';
        $this->usuario = new Usuario($this->db);
    }
    
    /**
     * Obtener todos los usuarios
     * @return array Arreglo con todos los usuarios
     */
    public function getAll() {
        $stmt = $this->usuario->read();
        $usuarios = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formatear la imagen si existe
            if ($row['foto_perfil']) {
                $row['foto_perfil'] = base64_encode($row['foto_perfil']);
            }
            
            $usuarios[] = $row;
        }
        
        return $usuarios;
    }
    
    /**
     * Obtener un usuario por su ID
     * @param int $id ID del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function getById($id) {
        if ($this->usuario->readOne($id)) {
            $usuario = [
                'cod_user' => $this->usuario->cod_user,
                'username' => $this->usuario->username,
                'email' => $this->usuario->email,
                'rol' => $this->usuario->rol,
                'foto_perfil' => $this->usuario->foto_perfil ? base64_encode($this->usuario->foto_perfil) : null
            ];
            
            return $usuario;
        }
        
        return null;
    }
    
    /**
     * Crear un nuevo usuario
     * @param array $data Datos del usuario
     * @return array Respuesta con el resultado de la operación
     */
    public function create($data) {
        // Verificar si los datos requeridos están presentes
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            return [
                'status' => 'error',
                'message' => 'Datos incompletos para crear el usuario'
            ];
        }
        
        // Verificar si el usuario ya existe
        if ($this->usuario->userExists($data['username'], $data['email'])) {
            return [
                'status' => 'error',
                'message' => 'El nombre de usuario o email ya está en uso'
            ];
        }
        
        // Asignar valores al objeto usuario
        $this->usuario->username = $data['username'];
        $this->usuario->email = $data['email'];
        $this->usuario->password = $data['password']; // Sin encriptación como solicitado
        $this->usuario->rol = isset($data['rol']) ? $data['rol'] : 'usuario';
        
        // Crear el usuario
        if ($this->usuario->create()) {
            return [
                'status' => 'success',
                'message' => 'Usuario creado exitosamente'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Error al crear el usuario'
        ];
    }
    
    /**
     * Actualizar un usuario existente
     * @param int $id ID del usuario
     * @param array $data Datos del usuario
     * @return array Respuesta con el resultado de la operación
     */
    public function update($id, $data) {
        // Verificar si el usuario existe
        if (!$this->usuario->readOne($id)) {
            return [
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ];
        }
        
        // Asignar valores al objeto usuario
        if (isset($data['username'])) {
            $this->usuario->username = $data['username'];
        }
        
        if (isset($data['email'])) {
            $this->usuario->email = $data['email'];
        }
        
        if (isset($data['rol'])) {
            $this->usuario->rol = $data['rol'];
        }
        
        // Actualizar el usuario
        if ($this->usuario->update()) {
            return [
                'status' => 'success',
                'message' => 'Usuario actualizado exitosamente'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Error al actualizar el usuario'
        ];
    }
    
    /**
     * Actualizar la contraseña de un usuario
     * @param int $id ID del usuario
     * @param string $password Nueva contraseña
     * @return array Respuesta con el resultado de la operación
     */
    public function updatePassword($id, $password) {
        // Verificar si el usuario existe
        if (!$this->usuario->readOne($id)) {
            return [
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ];
        }
        
        // Asignar la nueva contraseña
        $this->usuario->password = $password;
        
        // Actualizar la contraseña
        if ($this->usuario->updatePassword()) {
            return [
                'status' => 'success',
                'message' => 'Contraseña actualizada exitosamente'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Error al actualizar la contraseña'
        ];
    }
    
    /**
     * Eliminar un usuario
     * @param int $id ID del usuario
     * @return array Respuesta con el resultado de la operación
     */
    public function delete($id) {
        // Verificar si el usuario existe
        if (!$this->usuario->readOne($id)) {
            return [
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ];
        }
        
        // Eliminar el usuario
        if ($this->usuario->delete()) {
            return [
                'status' => 'success',
                'message' => 'Usuario eliminado exitosamente'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Error al eliminar el usuario'
        ];
    }
    
    /**
     * Verificar las credenciales de un usuario
     * @param string $username Nombre de usuario o email
     * @param string $password Contraseña
     * @return array Respuesta con el resultado de la operación
     */
    public function login($username, $password) {
        // Verificar las credenciales
        if ($this->usuario->verifyCredentials($username, $password)) {
            $usuario = [
                'cod_user' => $this->usuario->cod_user,
                'username' => $this->usuario->username,
                'email' => $this->usuario->email,
                'rol' => $this->usuario->rol,
                'foto_perfil' => $this->usuario->foto_perfil ? base64_encode($this->usuario->foto_perfil) : null
            ];
            
            return [
                'status' => 'success',
                'message' => 'Inicio de sesión exitoso',
                'data' => $usuario
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Usuario o contraseña incorrectos'
        ];
    }
}