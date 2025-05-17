<?php
/**
 * Clase base para todos los modelos
 * Proporciona funcionalidad común para interactuar con la base de datos
 */

require_once __DIR__ . '/../db/conexion.php';

class BaseModel {
    protected $conexion;
    protected $pdo;
    protected $table;
    
    /**
     * Constructor
     * 
     * @param string $table Nombre de la tabla en la base de datos
     */
    public function __construct($table = null) {
        $this->conexion = new Conexion();
        $this->pdo = $this->conexion->getConexion();
        $this->table = $table;
    }
    
    /**
     * Destructor - cierra la conexión
     */
    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->cerrarConexion();
        }
    }
    
    /**
     * Ejecuta una consulta SQL y devuelve el resultado
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros para la consulta preparada
     * @return PDOStatement Resultado de la consulta
     */
    protected function executeQuery($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene todos los registros de la tabla
     * 
     * @return array Registros encontrados
     */
    public function getAll() {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->executeQuery($query);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene un registro por su ID
     * 
     * @param int $id ID del registro
     * @param string $idField Nombre del campo ID (por defecto 'id')
     * @return array|bool Registro encontrado o false
     */
    public function getById($id, $idField = 'id') {
        $query = "SELECT * FROM $this->table WHERE $idField = :id LIMIT 1";
        $stmt = $this->executeQuery($query, [':id' => $id]);
        
        return $stmt->fetch();
    }
    
    /**
     * Inserta un nuevo registro
     * 
     * @param array $data Datos a insertar
     * @return array|bool Registro insertado o false
     */
    public function insert($data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) { 
            return ":" . $field; 
        }, $fields);
        
        $query = sprintf(
            "INSERT INTO %s (%s) VALUES (%s) RETURNING *",
            $this->table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );
        
        $params = [];
        foreach ($data as $field => $value) {
            $params[':' . $field] = $value;
        }
        
        $stmt = $this->executeQuery($query, $params);
        
        return $stmt->fetch();
    }
    
    /**
     * Actualiza un registro existente
     * 
     * @param int $id ID del registro a actualizar
     * @param array $data Datos a actualizar
     * @param string $idField Nombre del campo ID (por defecto 'id')
     * @return array|bool Registro actualizado o false
     */
    public function update($id, $data, $idField = 'id') {
        $fields = array_keys($data);
        $setClause = [];
        
        foreach ($fields as $field) {
            $setClause[] = "$field = :$field";
        }
        
        $query = sprintf(
            "UPDATE %s SET %s WHERE %s = :id RETURNING *",
            $this->table,
            implode(', ', $setClause),
            $idField
        );
        
        $params = [];
        foreach ($data as $field => $value) {
            $params[':' . $field] = $value;
        }
        $params[':id'] = $id;
        
        $stmt = $this->executeQuery($query, $params);
        
        return $stmt->fetch();
    }
    
    /**
     * Elimina un registro
     * 
     * @param int $id ID del registro a eliminar
     * @param string $idField Nombre del campo ID (por defecto 'id')
     * @return bool Éxito de la operación
     */
    public function delete($id, $idField = 'id') {
        $query = "DELETE FROM $this->table WHERE $idField = :id";
        $stmt = $this->executeQuery($query, [':id' => $id]);
        
        return $stmt->rowCount() > 0;
    }
} 