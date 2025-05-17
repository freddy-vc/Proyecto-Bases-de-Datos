<?php
/**
 * Clase para manejar la entidad Jugador
 */
class Jugador {
    // Propiedades de la base de datos
    private $conn;
    private $table_name = "Jugadores";
    
    // Propiedades del objeto
    public $cod_jug;
    public $nombres;
    public $apellidos;
    public $posicion;
    public $dorsal;
    public $cod_equ;
    public $foto;
    
    // Propiedades adicionales para relaciones
    public $equipo_nombre;
    
    /**
     * Constructor con conexión a la base de datos
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtener todos los jugadores
     * @return PDOStatement
     */
    public function read() {
        // Consulta para obtener todos los jugadores con información del equipo
        $query = "SELECT j.cod_jug, j.nombres, j.apellidos, j.posicion, j.dorsal, j.cod_equ, j.foto, 
                  e.nombre as equipo_nombre 
                  FROM " . $this->table_name . " j 
                  LEFT JOIN Equipos e ON j.cod_equ = e.cod_equ 
                  ORDER BY j.apellidos ASC, j.nombres ASC";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtener jugadores por equipo
     * @param int $cod_equ ID del equipo
     * @return PDOStatement
     */
    public function readByEquipo($cod_equ) {
        // Consulta para obtener jugadores por equipo
        $query = "SELECT j.cod_jug, j.nombres, j.apellidos, j.posicion, j.dorsal, j.cod_equ, j.foto, 
                  e.nombre as equipo_nombre 
                  FROM " . $this->table_name . " j 
                  LEFT JOIN Equipos e ON j.cod_equ = e.cod_equ 
                  WHERE j.cod_equ = ? 
                  ORDER BY j.apellidos ASC, j.nombres ASC";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID del equipo
        $stmt->bindParam(1, $cod_equ);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtener un jugador por su ID
     * @param int $id ID del jugador
     * @return bool True si se encontró el jugador, False en caso contrario
     */
    public function readOne($id) {
        // Consulta para obtener un jugador por su ID
        $query = "SELECT j.cod_jug, j.nombres, j.apellidos, j.posicion, j.dorsal, j.cod_equ, j.foto, 
                  e.nombre as equipo_nombre 
                  FROM " . $this->table_name . " j 
                  LEFT JOIN Equipos e ON j.cod_equ = e.cod_equ 
                  WHERE j.cod_jug = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID
        $stmt->bindParam(1, $id);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Verificar si se encontró el jugador
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar valores a las propiedades del objeto
            $this->cod_jug = $row['cod_jug'];
            $this->nombres = $row['nombres'];
            $this->apellidos = $row['apellidos'];
            $this->posicion = $row['posicion'];
            $this->dorsal = $row['dorsal'];
            $this->cod_equ = $row['cod_equ'];
            $this->foto = $row['foto'];
            $this->equipo_nombre = $row['equipo_nombre'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Crear un nuevo jugador
     * @return bool True si se creó el jugador, False en caso contrario
     */
    public function create() {
        // Consulta para insertar un nuevo jugador
        $query = "INSERT INTO " . $this->table_name . " (nombres, apellidos, posicion, dorsal, cod_equ) VALUES (?, ?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->posicion = htmlspecialchars(strip_tags($this->posicion));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->nombres);
        $stmt->bindParam(2, $this->apellidos);
        $stmt->bindParam(3, $this->posicion);
        $stmt->bindParam(4, $this->dorsal);
        $stmt->bindParam(5, $this->cod_equ);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualizar un jugador existente
     * @return bool True si se actualizó el jugador, False en caso contrario
     */
    public function update() {
        // Consulta para actualizar un jugador
        $query = "UPDATE " . $this->table_name . " SET nombres = ?, apellidos = ?, posicion = ?, dorsal = ?, cod_equ = ? WHERE cod_jug = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->posicion = htmlspecialchars(strip_tags($this->posicion));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->nombres);
        $stmt->bindParam(2, $this->apellidos);
        $stmt->bindParam(3, $this->posicion);
        $stmt->bindParam(4, $this->dorsal);
        $stmt->bindParam(5, $this->cod_equ);
        $stmt->bindParam(6, $this->cod_jug);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualizar la foto de un jugador
     * @return bool True si se actualizó la foto, False en caso contrario
     */
    public function updateFoto() {
        // Consulta para actualizar la foto de un jugador
        $query = "UPDATE " . $this->table_name . " SET foto = ? WHERE cod_jug = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular los valores
        $stmt->bindParam(1, $this->foto);
        $stmt->bindParam(2, $this->cod_jug);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Eliminar un jugador
     * @return bool True si se eliminó el jugador, False en caso contrario
     */
    public function delete() {
        // Consulta para eliminar un jugador
        $query = "DELETE FROM " . $this->table_name . " WHERE cod_jug = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID
        $stmt->bindParam(1, $this->cod_jug);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}