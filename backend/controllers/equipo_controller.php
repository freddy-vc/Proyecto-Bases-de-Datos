<?php
/**
 * Controlador para manejar las operaciones relacionadas con Equipos
 */
class EquipoController {
    private $db;
    private $equipo;
    
    /**
     * Constructor del controlador
     * @param Database $database Objeto de conexión a la base de datos
     */
    public function __construct($database) {
        $this->db = $database->getConnection();
        require_once '../models/equipo.php';
        $this->equipo = new Equipo($this->db);
    }
    
    /**
     * Obtener todos los equipos
     * @return array Arreglo con todos los equipos
     */
    public function getAll() {
        $stmt = $this->equipo->read();
        $equipos = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formatear la imagen si existe
            if ($row['escudo']) {
                $row['escudo'] = base64_encode($row['escudo']);
            }
            
            $equipos[] = $row;
        }
        
        return $equipos;
    }
    
    /**
     * Obtener un equipo por su ID
     * @param int $id ID del equipo
     * @return array|null Datos del equipo o null si no existe
     */
    public function getById($id) {
        if ($this->equipo->readOne($id)) {
            $equipo = [
                'cod_equ' => $this->equipo->cod_equ,
                'nombre' => $this->equipo->nombre,
                'cod_ciu' => $this->equipo->cod_ciu,
                'escudo' => $this->equipo->escudo ? base64_encode($this->equipo->escudo) : null,
                'cod_dt' => $this->equipo->cod_dt,
                'ciudad_nombre' => $this->equipo->ciudad_nombre,
                'dt_nombres' => $this->equipo->dt_nombres,
                'dt_apellidos' => $this->equipo->dt_apellidos
            ];
            
            return $equipo;
        }
        
        return null;
    }
    
    /**
     * Crear un nuevo equipo
     * @param array $data Datos del equipo a crear
     * @return bool|string True si se creó correctamente, mensaje de error en caso contrario
     */
    public function create($data) {
        // Validar datos requeridos
        if (empty($data['nombre']) || empty($data['cod_ciu'])) {
            return "El nombre del equipo y la ciudad son obligatorios";
        }
        
        // Asignar valores al objeto equipo
        $this->equipo->nombre = $data['nombre'];
        $this->equipo->cod_ciu = $data['cod_ciu'];
        $this->equipo->cod_dt = !empty($data['cod_dt']) ? $data['cod_dt'] : null;
        
        // Procesar la imagen si existe
        if (!empty($data['escudo'])) {
            // Decodificar la imagen en base64
            $this->equipo->escudo = base64_decode($data['escudo']);
        } else {
            $this->equipo->escudo = null;
        }
        
        // Intentar crear el equipo
        if ($this->equipo->create()) {
            return true;
        }
        
        return "No se pudo crear el equipo. Intente nuevamente.";
    }
    
    /**
     * Actualizar un equipo existente
     * @param int $id ID del equipo a actualizar
     * @param array $data Datos actualizados del equipo
     * @return bool|string True si se actualizó correctamente, mensaje de error en caso contrario
     */
    public function update($id, $data) {
        // Verificar que el equipo existe
        if (!$this->equipo->readOne($id)) {
            return "Equipo no encontrado";
        }
        
        // Validar datos requeridos
        if (empty($data['nombre']) || empty($data['cod_ciu'])) {
            return "El nombre del equipo y la ciudad son obligatorios";
        }
        
        // Asignar valores al objeto equipo
        $this->equipo->nombre = $data['nombre'];
        $this->equipo->cod_ciu = $data['cod_ciu'];
        $this->equipo->cod_dt = !empty($data['cod_dt']) ? $data['cod_dt'] : null;
        
        // Procesar la imagen si existe
        if (isset($data['escudo'])) {
            if (!empty($data['escudo'])) {
                // Decodificar la imagen en base64
                $this->equipo->escudo = base64_decode($data['escudo']);
            } else {
                $this->equipo->escudo = null;
            }
        }
        
        // Intentar actualizar el equipo
        if ($this->equipo->update()) {
            return true;
        }
        
        return "No se pudo actualizar el equipo. Intente nuevamente.";
    }
    
    /**
     * Eliminar un equipo
     * @param int $id ID del equipo a eliminar
     * @return bool|string True si se eliminó correctamente, mensaje de error en caso contrario
     */
    public function delete($id) {
        // Verificar que el equipo existe
        if (!$this->equipo->readOne($id)) {
            return "Equipo no encontrado";
        }
        
        // Intentar eliminar el equipo
        if ($this->equipo->delete()) {
            return true;
        }
        
        return "No se pudo eliminar el equipo. Intente nuevamente.";
    }
    
    /**
     * Obtener equipos destacados
     * @param int $limit Número de equipos a obtener
     * @return array Arreglo con los equipos destacados
     */
    public function getDestacados($limit = 3) {
        $stmt = $this->equipo->getDestacados($limit);
        $equipos = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formatear la imagen si existe
            if ($row['escudo']) {
                $row['escudo'] = base64_encode($row['escudo']);
            }
            
            $equipos[] = $row;
        }
        
        return $equipos;
    }
}
?>