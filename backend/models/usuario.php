<?php
/**
 * Clase para manejar la entidad Usuario
 */
class Usuario {
    // Propiedades de la base de datos
    private $conn;
    private $table_name = "Usuarios";
    
    // Propiedades del objeto
    public $cod_user;
    public $username;
    public $email;
    public $password;
    public $rol;
    public $foto_perfil;
    
    /**
     * Constructor con conexión a la base de datos
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtener todos los usuarios
     * @return PDOStatement
     */
    public function read() {
        // Consulta para obtener todos los usuarios
        $query = "SELECT cod_user, username, email, rol, foto_perfil FROM " . $this->table_name . " ORDER BY username ASC";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtener un usuario por su ID
     * @param int $id ID del usuario
     * @return bool True si se encontró el usuario, False en caso contrario
     */
    public function readOne($id) {
        // Consulta para obtener un usuario por su ID
        $query = "SELECT cod_user, username, email, rol, foto_perfil FROM " . $this->table_name . " WHERE cod_user = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID
        $stmt->bindParam(1, $id);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Verificar si se encontró el usuario
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar valores a las propiedades del objeto
            $this->cod_user = $row['cod_user'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->rol = $row['rol'];
            $this->foto_perfil = $row['foto_perfil'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Crear un nuevo usuario
     * @return bool True si se creó el usuario, False en caso contrario
     */
    public function create() {
        // Consulta para insertar un nuevo usuario
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, rol) VALUES (?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->password);
        $stmt->bindParam(4, $this->rol);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualizar un usuario existente
     * @return bool True si se actualizó el usuario, False en caso contrario
     */
    public function update() {
        // Consulta para actualizar un usuario
        $query = "UPDATE " . $this->table_name . " SET username = ?, email = ?, rol = ? WHERE cod_user = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->rol);
        $stmt->bindParam(4, $this->cod_user);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualizar la contraseña de un usuario
     * @return bool True si se actualizó la contraseña, False en caso contrario
     */
    public function updatePassword() {
        // Consulta para actualizar la contraseña de un usuario
        $query = "UPDATE " . $this->table_name . " SET password = ? WHERE cod_user = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular los valores
        $stmt->bindParam(1, $this->password);
        $stmt->bindParam(2, $this->cod_user);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Eliminar un usuario
     * @return bool True si se eliminó el usuario, False en caso contrario
     */
    public function delete() {
        // Consulta para eliminar un usuario
        $query = "DELETE FROM " . $this->table_name . " WHERE cod_user = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID
        $stmt->bindParam(1, $this->cod_user);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Verificar si un usuario existe por su nombre de usuario o email
     * @param string $username Nombre de usuario
     * @param string $email Email
     * @return bool True si el usuario existe, False en caso contrario
     */
    public function userExists($username, $email) {
        // Consulta para verificar si un usuario existe
        $query = "SELECT cod_user, username, email, password, rol, foto_perfil FROM " . $this->table_name . " WHERE username = ? OR email = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular los valores
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Verificar si se encontró el usuario
        if ($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Verificar las credenciales de un usuario
     * @param string $username Nombre de usuario o email
     * @param string $password Contraseña
     * @return bool True si las credenciales son válidas, False en caso contrario
     */
    public function verifyCredentials($username, $password) {
        // Consulta para verificar las credenciales de un usuario SOLO por username y password
        $query = "SELECT cod_user, username, email, password, rol, foto_perfil FROM " . $this->table_name . " WHERE username = ? AND password = ?";
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        // Vincular los valores
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        // Ejecutar la consulta
        $stmt->execute();
        // Verificar si se encontró el usuario
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Asignar valores a las propiedades del objeto
            $this->cod_user = $row['cod_user'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->rol = $row['rol'];
            $this->foto_perfil = $row['foto_perfil'];
            return true;
        }
        return false;
    }
}