<?php
/**
 * Clase para manejar la entidad Equipo
 */
class Equipo {
    // Propiedades de la base de datos
    private $conn;
    private $table_name = "Equipos";
    
    // Propiedades del objeto
    public $cod_equ;
    public $nombre;
    public $cod_ciu;
    public $escudo;
    public $cod_dt;
    
    // Propiedades adicionales para relaciones
    public $ciudad_nombre;
    public $dt_nombres;
    public $dt_apellidos;
    
    /**
     * Constructor con conexión a la base de datos
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtener todos los equipos
     * @return PDOStatement
     */
    public function read() {
        // Consulta para obtener todos los equipos con información de ciudad y director técnico
        $query = "SELECT e.cod_equ, e.nombre, e.cod_ciu, e.escudo, e.cod_dt, 
                  c.nombre as ciudad_nombre, 
                  d.nombres as dt_nombres, d.apellidos as dt_apellidos 
                  FROM " . $this->table_name . " e 
                  LEFT JOIN Ciudades c ON e.cod_ciu = c.cod_ciu 
                  LEFT JOIN Directores d ON e.cod_dt = d.cod_dt 
                  ORDER BY e.nombre ASC";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtener un equipo específico por ID
     * @param int $id ID del equipo
     * @return bool True si se encontró el equipo, False en caso contrario
     */
    public function readOne($id) {
        // Consulta para obtener un equipo específico
        $query = "SELECT e.cod_equ, e.nombre, e.cod_ciu, e.escudo, e.cod_dt, 
                  c.nombre as ciudad_nombre, 
                  d.nombres as dt_nombres, d.apellidos as dt_apellidos 
                  FROM " . $this->table_name . " e 
                  LEFT JOIN Ciudades c ON e.cod_ciu = c.cod_ciu 
                  LEFT JOIN Directores d ON e.cod_dt = d.cod_dt 
                  WHERE e.cod_equ = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID
        $stmt->bindParam(1, $id);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el registro
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si se encontró el equipo
        if($row) {
            // Asignar valores a las propiedades del objeto
            $this->cod_equ = $row['cod_equ'];
            $this->nombre = $row['nombre'];
            $this->cod_ciu = $row['cod_ciu'];
            $this->escudo = $row['escudo'];
            $this->cod_dt = $row['cod_dt'];
            $this->ciudad_nombre = $row['ciudad_nombre'];
            $this->dt_nombres = $row['dt_nombres'];
            $this->dt_apellidos = $row['dt_apellidos'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Crear un nuevo equipo
     * @return bool True si se creó correctamente, False en caso contrario
     */
    public function create() {
        // Consulta para insertar un nuevo equipo
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, cod_ciu, escudo, cod_dt) 
                  VALUES (?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->cod_ciu = htmlspecialchars(strip_tags($this->cod_ciu));
        // No sanitizamos escudo porque es un BYTEA
        $this->cod_dt = htmlspecialchars(strip_tags($this->cod_dt));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->nombre);
        $stmt->bindParam(2, $this->cod_ciu);
        $stmt->bindParam(3, $this->escudo);
        $stmt->bindParam(4, $this->cod_dt);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualizar un equipo existente
     * @return bool True si se actualizó correctamente, False en caso contrario
     */
    public function update() {
        // Consulta para actualizar un equipo
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = ?, cod_ciu = ?, escudo = ?, cod_dt = ? 
                  WHERE cod_equ = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->cod_ciu = htmlspecialchars(strip_tags($this->cod_ciu));
        // No sanitizamos escudo porque es un BYTEA
        $this->cod_dt = htmlspecialchars(strip_tags($this->cod_dt));
        $this->cod_equ = htmlspecialchars(strip_tags($this->cod_equ));
        
        // Vincular los valores
        $stmt->bindParam(1, $this->nombre);
        $stmt->bindParam(2, $this->cod_ciu);
        $stmt->bindParam(3, $this->escudo);
        $stmt->bindParam(4, $this->cod_dt);
        $stmt->bindParam(5, $this->cod_equ);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Eliminar un equipo
     * @return bool True si se eliminó correctamente, False en caso contrario
     */
    public function delete() {
        // Consulta para eliminar un equipo
        $query = "DELETE FROM " . $this->table_name . " WHERE cod_equ = ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar el ID
        $this->cod_equ = htmlspecialchars(strip_tags($this->cod_equ));
        
        // Vincular el ID
        $stmt->bindParam(1, $this->cod_equ);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Obtener equipos destacados (ejemplo de método personalizado)
     * @param int $limit Número de equipos a obtener
     * @return PDOStatement
     */
    public function getDestacados($limit = 3) {
        // En un caso real, podría ser una consulta más compleja que determine equipos destacados
        // por número de victorias, goles, etc.
        $query = "SELECT e.cod_equ, e.nombre, e.cod_ciu, e.escudo, e.cod_dt, 
                  c.nombre as ciudad_nombre 
                  FROM " . $this->table_name . " e 
                  LEFT JOIN Ciudades c ON e.cod_ciu = c.cod_ciu 
                  ORDER BY e.nombre ASC 
                  LIMIT ?";
        
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular el límite
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        return $stmt;
    }
}
?>