<?php
/**
 * Clase para manejar la entidad Ciudad
 */
class Ciudad {
    // Propiedades de la base de datos
    private $conn;
    private $table_name = "Ciudades";
    
    // Propiedades del objeto
    public $cod_ciu;
    public $nombre;
    
    /**
     * Constructor con conexión a la base de datos
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtener todas las ciudades
     * @return PDOStatement
     */
    public function read() {
        // Consulta para obtener todas las ciudades
        $query = "SELECT cod_ciu, nombre FROM " . $this->table_name . " ORDER BY nombre ASC";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtener una ciudad específica por ID
     * @param int $id ID de la ciudad
     * @return bool True si se encontró la ciudad, False en caso contrario
     */
    public function readOne($id) {
        // Consulta para obtener una ciudad específica
        $query = "SELECT cod_ciu, nombre FROM " . $this->table_name . " WHERE cod_ciu = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID
        $stmt->bindParam(1, $id);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el registro
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si se encontró la ciudad
        if($row) {
            // Asignar valores a las propiedades del objeto
            $this->cod_ciu = $row['cod_ciu'];
            $this->nombre = $row['nombre'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Crear una nueva ciudad
     * @return bool True si se creó correctamente, False en caso contrario
     */
    public function create() {
        // Consulta para insertar una nueva ciudad
        $query = "INSERT INTO " . $this->table_name . " (nombre) VALUES (?)";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->nombre);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualizar una ciudad existente
     * @return bool True si se actualizó correctamente, False en caso contrario
     */
    public function update() {
        // Consulta para actualizar una ciudad
        $query = "UPDATE " . $this->table_name . " SET nombre = ? WHERE cod_ciu = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->cod_ciu = htmlspecialchars(strip_tags($this->cod_ciu));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->nombre);
        $stmt->bindParam(2, $this->cod_ciu);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Eliminar una ciudad
     * @return bool True si se eliminó correctamente, False en caso contrario
     */
    public function delete() {
        // Consulta para eliminar una ciudad
        $query = "DELETE FROM " . $this->table_name . " WHERE cod_ciu = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar el ID
        $this->cod_ciu = htmlspecialchars(strip_tags($this->cod_ciu));
        
        // Vincular el ID
        $stmt->bindParam(1, $this->cod_ciu);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Obtener ciudades con equipos
     * @return PDOStatement
     */
    public function getCiudadesConEquipos() {
        // Consulta para obtener ciudades que tienen equipos
        $query = "SELECT DISTINCT c.cod_ciu, c.nombre 
                  FROM " . $this->table_name . " c 
                  INNER JOIN Equipos e ON c.cod_ciu = e.cod_ciu 
                  ORDER BY c.nombre ASC";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        return $stmt;
    }
}
?>